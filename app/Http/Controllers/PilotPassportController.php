<?php

namespace App\Http\Controllers;

ini_set('memory_limit', '512M');

use App\Mail\PilotPassportMail;
use App\PilotPassport;
use App\PilotPassportAward;
use App\PilotPassportEnrollment;
use App\PilotPassportLog;
use App\RealopsPilot;
use App\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mail;

class PilotPassportController extends Controller {
    public function index(Request $request) {
        if ($request) {
            $tab = (in_array($request->tab, ['information', 'enrollments', 'passport_book', 'achievements', 'settings'])) ? $request->tab : 'information';
            $pg = (is_numeric($request->pg)) ? $request->pg : null;
        }
        $privacy = null;
        $challenges = PilotPassport::get();
        $enrollments = $achievements = null;
        if (auth()->guard('realops')->user()) {
            $pilot = auth()->guard('realops')->user();
            $enrollments = PilotPassportEnrollment::where('cid', $pilot->id)->get();
            $achievements = PilotPassportAward::where('cid', $pilot->id)->get();
            $privacy = intval($pilot->privacy);
        }
        return view('site.pilot_passport')->with('challenges', $challenges)->with('enrollments', $enrollments)
            ->with('achievements', $achievements)->with('privacy', $privacy)->with('tab', $tab)
            ->with('view_challenge', $pg);
    }

    public function enroll(Request $request) {
        $pilot = auth()->guard('realops')->user();
        if (!$pilot) {
            return redirect('/pilot_passport/login');
        }
        $valid_enrollment = true;
        if (!is_numeric($request->challenge_id)) {
            $valid_enrollment = false;
        }
        $challenge = PilotPassport::find($request->challenge_id);
        if (!$challenge) {
            $valid_enrollment = false;
        }
        if (!$valid_enrollment) {
            return redirect(route('pilotPassportIndex'))->withInput(['tab' => 'enrollments'])->with('error', 'Enrollment data invalid. Please contact wm@ztlartcc.org for assistance.');
        }
        $enrollment = PilotPassportEnrollment::where('cid', $pilot->id)->where('challenge_id', $request->challenge_id)->get();
        if ($enrollment->isEmpty()) {
            $enrollment = new PilotPassportEnrollment;
            $enrollment->cid = $pilot->id;
            $enrollment->challenge_id = $challenge->id;
            $enrollment->save();
            Mail::to($pilot->email)->send(new PilotPassportMail('enroll', $pilot, $challenge));
            return redirect(route('pilotPassportIndex'))->withInput(['tab' => 'enrollments'])->with('success', 'You are now enrolled in the ZTL Pilot Passport program!');
        }
        return redirect(route('pilotPassportIndex'))->withInput(['tab' => 'enrollments'])->with('error', 'You are already enrolled in this challenge.');
    }

    public function setPrivacy(Request $request) {
        $pilot = auth()->guard('realops')->user();
        if (is_null($pilot)) {
            return redirect(route('pilotPassportIndex'))->withInput(['tab' => 'settings'])->with('error', 'Unable to adjust privacy settings - Invalid CID.');
        }
        $pilot->privacy = $request->privacy;
        $pilot->save();
        return redirect(route('pilotPassportIndex'))->withInput(['tab' => 'settings'])->with('success', 'Your privacy preferences have been saved.');
    }

    public function purgeData(Request $request) {
        if (strtolower($request->input('confirm_text')) != "confirm - purge all") {
            return redirect()->back()->with('error', 'Data not purged. Please type in the required message to continue');
        }
        $pilot = auth()->guard('realops')->user();
        $pilot->delete();
        $enrollments = PilotPassportEnrollment::where('cid', $pilot->id);
        foreach($enrollments as $enrollment) {
            $enrollment->delete();
        }
        $achievements = PilotPassportAward::where('cid', $pilot->id);
        foreach($achievements as $achievement) {
            $achievement->delete();
        }
        $logs = PilotPassportLog::where('cid', $pilot->id);
        foreach($logs as $log) {
            $log->delete();
        }
        return redirect('/logout');
    }

    public static function fetchRecentPilotAccomplishments() {
        $accomplishments = PilotPassportAward::where('awarded_on', '>', now()->subDays(90)->endOfDay())
            ->orderByRaw('RAND()')->take(10)->get();
        $recent_accomplishments = null;
        foreach ($accomplishments as $accomplishment) {
            $accomplishment_for_display = (object) [
                'pilot_name' => $accomplishment->pilot_public_name,
                'challenge_name' => $accomplishment->challenge_title
            ];
            $recent_accomplishments[] = $accomplishment_for_display;
        }
        return $recent_accomplishments;
    }

    public function generateCertificate($id) {
        $award = PilotPassportAward::find($id);
        $error_html = '<p>An error has occured - please contact <a href="emailto:wm@ztlartcc.org">wm@ztlartcc.org</a></p>';
        if (!$award) {
            return pdf()->html($error_html)->download();
        }
        $pilot = RealopsPilot::find($award->cid);
        $staff_users = User::with('roles')->where('status', '1')->get();
        $atm = $staff_users->filter(function ($staff_user) {
            return $staff_user->hasRole('atm');
        });
        if (!$atm) {
            $atm = $staff_users->filter(function ($staff_user) {
                return $staff_user->hasRole('datm');
            });
        }
        $atm = $atm->first();
        $params = [
            'achievement' => $id,
            'pilot_name' => $pilot->fname . ' ' . $pilot->lname,
            'phase_title' => $award->challenge_title,
            'award_date' => Carbon::parse($award->awarded_on),
            'atm_name' => $atm->full_name
        ];
        $pdf = Pdf::loadView('pdf.pilot_passport_certificate', $params)->setPaper('letter', 'landscape');
        return $pdf->download('ztl_pilot_passport_challenge_' . $award->challenge_id . '.pdf');
    }

    public function generateStamp($id) {
        $img_path = 'photos/pilot_passport/pilot_passport_stamp.png';
        $gd = imagecreatefrompng($img_path);
        imagealphablending($gd, false);
        $transparency = imagecolorallocatealpha($gd, 0, 0, 0, 127);
        imagefill($gd, 0, 0, $transparency);
        imagesavealpha($gd, true);
        $red = imagecolorallocate($gd, 255, 0, 0);
        $font = 'font/trebuc.ttf';
        $font_size = 200;
        $x_start = $this->getGdTextCenter($gd, $font_size, $font, $id);
        imagettftext($gd, $font_size, 0, $x_start, 650, $red, $font, $id);
        ob_start();
        imagepng($gd);
        imagedestroy($gd);
        $image_data = ob_get_contents();
        ob_end_clean();
        $response = \Response::make($image_data, 200);
        $response->header("Content-Type", "image/png");
        return $response;
    }

    public function generateMedal($id) {
        $a = PilotPassportAward::find($id);
        $img_path = 'photos/pilot_passport/challenge_medal.png';
        $gd = imagecreatefrompng($img_path);
        imagealphablending($gd, false);
        $transparency = imagecolorallocatealpha($gd, 0, 0, 0, 127);
        imagefill($gd, 0, 0, $transparency);
        imagesavealpha($gd, true);
        $bronze = imagecolorallocate($gd, 53, 32, 11);
        $font = 'font/trebuc.ttf';
        $font_size = 200;
        $title_words = explode(' ', $a->challenge_title);
        $x_start = $this->getGdTextCenter($gd, $font_size, $font, $title_words[0]);
        $y_start = 1700;
        imagettftext($gd, $font_size, 0, $x_start, $y_start, $bronze, $font, $title_words[0]);
        if($title_words[1]) {
            $x_start = $this->getGdTextCenter($gd, $font_size, $font, $title_words[1]);
            imagettftext($gd, 200, 0, $x_start, ($y_start + $font_size + 100), $bronze, $font, $title_words[1]);
        }
        ob_start();
        imagepng($gd);
        imagedestroy($gd);
        $image_data = ob_get_contents();
        ob_end_clean();
        $response = \Response::make($image_data, 200);
        $response->header("Content-Type", "image/png");
        return $response;
    }

    private function getGdTextCenter($image, $font_size, $font, $text) {
        $width = imagesx($image);
        $centerX = $width / 2;
        list($left, $bottom, $right, , , $top) = imageftbbox($font_size, 0, $font, $text);
        $left_offset = ($right - $left) / 2;
        return ($centerX - $left_offset);
    }
}
