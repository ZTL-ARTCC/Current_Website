<?php

namespace App\Livewire;

use App\Enums\SessionVariables;
use App\Mail\VisitorMail;
use App\User;
use App\Visitor;
use Carbon\Carbon;
use Config;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Mail;

class VisitRequest extends Component {
    private const RATING_REQUIRED = 4; // 4 = S3, IAW DP001
    private const RATING_CONSOLIDATION_HOURS = 50; // DP001
    public null|object $vatsim_user = null;
    private null|object $vatusa_checklist = null;
    public string $atm_name = 'Undefined';
    public null|int $cid = null;
    public string $email_redacted = '';
    public bool $vatusa_controller = false;
    public null|object $checklist = null;
    public null|string $error_message = null;
    public bool $acknowledge = false;
    public string $justification;
    public ?string $recaptchaToken = null;

    public function render() {
        $this->fetch_atm_name();
        return view('livewire.visit-request');
    }

    public function checkCID() {
        $this->error_message = null;
        if ($this->check_existing_visit_request()) {
            $this->error_message = "Unable to complete your request because you already have an 
                active visit request, check your email for details.";
            return false;
        } elseif ($this->check_existing_roster()) {
            $this->error_message = "Unable to complete your request because you are already on 
                the roster for this facility.";
            return false;
        }

        $validated = $this->validate([
            'cid' => 'required|integer|between:800000,9999999'
        ]);
        $this->fetch_user_info();
        if (is_null($this->vatsim_user)) {
            return false;
        }
        if ($this->vatsim_user->rating < 1) {
            $this->error_message = "Unable to complete your request because your account is 
                suspended or inactive. Please contact VATSIM at support.vatsim.net";
            return false;
        }
        if ($this->vatsim_user->division == 'USA' && $this->vatsim_user->rating > 2) {
            $this->fetch_vatusa_checklist();
        }
        $this->build_checklist();
        $this->clean_email($this->vatsim_user->email);
    }

    public function checklistIcon(string $checklist_item): string {
        if (is_null($this->checklist->{$checklist_item})) {
            return '<i class="fa-solid fa-question ms-2 text-warning"></i>';
        }
        if ($this->checklist->{$checklist_item}) {
            return '<i class="fa-solid fa-check ms-2 text-success"></i>';
        }
        if (!$this->checklist->{$checklist_item}) {
            return '<i class="fa-solid fa-x ms-2 text-danger"></i>';
        }
        return '<i class="fa-solid fa-question ms-2 text-warning"></i>';
    }

    public function submitVisitRequest() {
        $validated = $this->validate([
            'acknowledge' => 'accepted',
            'justification' => 'required|string'
        ]);

        if (!$this->evaluate_recapcha()) {
            return false;
        }

        $visit = Visitor::updateOrCreate(
            ['cid' => $this->vatsim_user->id],
            [
                'name' => $this->vatsim_user->name_first . ' ' . $this->vatsim_user->name_last,
                'email' => $this->vatsim_user->email,
                'rating' => $this->vatsim_user->rating,
                'home' => $this->get_home_facility(),
                'reason'=> $this->justification,
                'status'=> 0
            ]
        );

        Mail::to($visit->email)->cc(config('artcc.email_datm'))->send(new VisitorMail('new', $visit));
        Artisan::call('app:moodle-sync ' . $this->vatsim_user->id);
        return redirect('/')->with(SessionVariables::SUCCESS->value, 'Thank you for your interest in the ' . config('artcc.id') . ' ARTCC! Your visit request has been submitted.');
    }

    private function fetch_vatusa_checklist() {
        $client = new Client();
        $response = $client->request('GET', Config::get('vatusa.base').'/v2/user/' . $this->cid . '/transfer/checklist?apikey='.Config::get('vatusa.api_key'), [
            'http_errors' => false
        ]);
        if ($response->getStatusCode() == "200") {
            $this->vatusa_checklist = json_decode($response->getBody());
        } else {
            $this->error_message = "Unable to complete your request at this time - please try again later.";
        }
    }

    private function fetch_user_info() {
        $client = new Client();
        $response = $client->request('GET', 'https://api.vatsim.net/api/ratings/' . $this->cid, [
            'headers' => [
                'Authorization' => 'Token ' . Config::get('vatsim.api_key'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'http_errors' => false
        ]);
        if ($response->getStatusCode() == "200") {
            $this->vatsim_user = json_decode($response->getBody());
        } else {
            $this->error_message = "Unable to complete your request at this time - please try again later.";
        }
    }

    private function fetch_rating_info() {
        $client = new Client();
        $response = $client->request('GET', 'https://api.vatsim.net/api/ratings/' . $this->cid . '/rating_times/', [
            'headers' => [
                'Authorization' => 'Token ' . Config::get('vatsim.api_key'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'http_errors' => false
        ]);
        if ($response->getStatusCode() == "200") {
            return json_decode($response->getBody());
        } else {
            $this->error_message = "Unable to complete your request at this time - please try again later.";
        }
        return null;
    }

    private function clean_email(string $email) {
        $this->email_redacted = substr($email, 0, 1) . '***@' . substr(strchr($email, '@'), 1);
    }

    private function fetch_atm_name() {
        $users = User::with('roles')->where('status', '1')->get();
        $atm = $users->filter(function ($user) {
            return $user->hasRole('atm');
        });
        if (count($atm)) {
            $this->atm_name = $atm->first()->full_name;
        }
    }

    private function check_existing_visit_request(): bool {
        $visit = Visitor::where('cid', $this->cid)->first();
        return (bool) $visit;
    }

    private function check_existing_roster(): bool {
        $roster = User::where('id', $this->cid)->where('status', 1)->first();
        return (bool) $roster;
    }

    private function build_checklist() {
        if (!is_null($this->vatusa_checklist)) {
            $this->checklist = $this->vatusa_checklist->data;
            $this->vatusa_controller = true;
            return true;
        }
        // VATUSA checklist unavailable or not a VATUSA controller - build checklist from VATSIM
        $c = new \stdClass;
        $c->hasHome = null; // Unable to know if a controller is on an active roster outside of VATUSA
        $c->hasRating = ($this->vatsim_user->rating >= self::RATING_REQUIRED);
        $c->{"50hrs"} = $this->compute_rating_consolidation();
        $c->{"60days"} = null; // Unable to know when last visit request was approved outside of VATUSA
        $c->{"90days"} = (Carbon::parse($this->vatsim_user->lastratingchange)->diffInDays(Carbon::now()) >= 90);
        $c->visiting = ($c->hasRating && $c->{"50hrs"} && $c->{"90days"});
        $this->checklist = $c;
    }

    private function compute_rating_consolidation(): bool {
        $stats = $this->fetch_rating_info();
        if (is_null($stats)) {
            return false;
        }
        $consolidated = match ($this->vatsim_user->rating) {
            -1, 0, 1 => false,
            2 => ($stats->s1 > self::RATING_CONSOLIDATION_HOURS),
            3 => ($stats->s2 > self::RATING_CONSOLIDATION_HOURS),
            4 => ($stats->s3 > self::RATING_CONSOLIDATION_HOURS),
            5 => ($stats->c1 > self::RATING_CONSOLIDATION_HOURS),
            6, 7, 8, 9, 10, 11, 12 => true
        };
        return $consolidated;
    }

    private function get_home_facility(): string {
        if (!is_null($this->vatusa_checklist) && $this->vatusa_checklist->data->homeController) {
            $client = new Client();
            $response = $client->request('GET', Config::get('vatusa.base').'/v2/user/' . $this->cid . '?apikey='.Config::get('vatusa.api_key'), [
                'http_errors' => false
            ]);
            if ($response->getStatusCode() == "200") {
                $vatusa_user = json_decode($response->getBody());
                return $vatusa_user->data->facility;
            } else {
                return 'Unknown VATUSA';
            }

        }
        if (is_null($this->vatsim_user)) {
            return 'Unknown';
        }
        if ($this->vatsim_user->subdivision != '') {
            return $this->vatsim_user->subdivision;
        }
        if ($this->vatsim_user->subdivision != '') {
            return $this->vatsim_user->division;
        }
        if ($this->vatsim_user->subdivision != '') {
            return $this->vatsim_user->region;
        }
        return 'Unknown';
    }

    private function evaluate_recapcha(): bool {
        // Do not attempt to eval recapcha on local environments
        if (Config::get('app.env') == 'local') {
            return true;
        }
        // 1. Validate reCAPTCHA token exists
        if (!$this->recaptchaToken) {
            throw ValidationException::withMessages([
                'recaptchaToken' => 'Please complete the reCAPTCHA challenge.',
            ]);
        }

        // 2. Verify token with Google's API
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => config('services.recaptcha.secret'),
            'response' => $this->recaptchaToken,
            'remoteip' => request()->ip(),
        ]);

        if (!$response->json('success')) {
            throw ValidationException::withMessages([
                'recaptchaToken' => 'ReCAPTCHA verification failed. Please try again.',
            ]);
        }
        return true;
    }
}
