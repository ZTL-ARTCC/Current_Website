<?php

namespace App\Console\Commands;

use App\Event;
use App\EventPosition;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class SupportEvents extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Events:UpdateSupportEvents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates any neighbor support events that aren\'t already in the DB.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    // Pulled from the vatSpy datafile - update this list if airports are addded/removed to KZID, KZDC, KZJX, KZHU, KZME, KZMA, or KZNY
    protected array $event_pull_lut = [
        'all_events' => [
            // KZID
            'KZID' => ['KAAS', 'KAID', 'KAJG', 'KAMT', 'KAOH', 'KAXV', 'KBAK', 'KBFR', 'KBKW', 'KBLF', 'KBMG', 'KBRY', 'KBYL', 'KCEV', 'KCFJ', 'KCMH', 'KCPF', 'KCQA', 'KCRW', 'KCUL', 'KCVG', 'KCYO', 'KDAY', 'KDCY', 'KDLZ', 'KDVK', 'KDWU', 'KEBD', 'KEDJ', 'KEHR', 'KEKQ', 'KEKX', 'KEOP', 'KEVV', 'KEYE', 'KFFO', 'KFFT', 'KFGX', 'KFRH', 'KFTK', 'KGAS', 'KGEO', 'KGEZ', 'KGFD', 'KGPC', 'KHAO', 'KHBE', 'KHFY', 'KHLB', 'KHNB', 'KHOC', 'KHTS', 'KHTW', 'KHUF', 'KILN', 'KIMS', 'KIND', 'KIOB', 'KJKL', 'KJQD', 'KJVY', 'KLCK', 'KLEX', 'KLHQ', 'KLNP', 'KLOU', 'KLOZ', 'KLSD', 'KLUK', 'KLWV', 'KMGY', 'KMIE', 'KMQJ', 'KMRT', 'KMWO', 'KOSU', 'KOVO', 'KOWB', 'KOXD', 'KPBX', 'KPKB', 'KPLD', 'KPMH', 'KPRG', 'KRGA', 'KRID', 'KRLX', 'KRSV', 'KRZT', 'KSCA', 'KSCX', 'KSDF', 'KSER', 'KSGH', 'KSIV', 'KSJS', 'KSME', 'KSXL', 'KSYM', 'KTEL', 'KTZR', 'KUMP', 'KUNI', 'KUSW', 'KUWL', 'KUYF', 'KVES', 'KVNW', 'KVTA', 'KZZV'],
            // KZDC
            'KZDC' => ['KACY', 'KACZ', 'KADW', 'KAKQ', 'KANP', 'KAPG', 'KAPH', 'KASJ', 'KAVC', 'KBCB', 'KBKT', 'KBUY', 'KBWI', 'KCGE', 'KCGS', 'KCHO', 'KCJR', 'KCPK', 'KCTZ', 'KCXE', 'KDAA', 'KDAN', 'KDCA', 'KDCA', 'KDCA', 'KDCA', 'KDOV', 'KDPL', 'KECG', 'KEDE', 'KEKN', 'KEMV', 'KESN', 'KETC', 'KEWN', 'KEYF', 'KEZF', 'KFAF', 'KFAY', 'KFBG', 'KFCI', 'KFDK', 'KFFA', 'KFKN', 'KFME', 'KFRR', 'KFVX', 'KFYJ', 'KGAI', 'KGED', 'KGSB', 'KGVE', 'KGWW', 'KHAT', 'KHEF', 'KHFF', 'KHGR', 'KHNZ', 'KHRJ', 'KHSE', 'KHSP', 'KHWY', 'KIAD', 'KIGX', 'KILM', 'KISO', 'KIXA', 'KJGG', 'KJNX', 'KJWX', 'KJYO', 'KLBT', 'KLFI', 'KLHZ', 'KLKU', 'KLUA', 'KLVL', 'KLWB', 'KLYH', 'KMCZ', 'KMEB', 'KMFV', 'KMHX', 'KMIV', 'KMQI', 'KMRB', 'KMRH', 'KMTN', 'KMTV', 'KNAK', 'KNCA', 'KNDY', 'KNFE', 'KNGU', 'KNHK', 'KNIS', 'KNJM', 'KNKT', 'KNLT', 'KNTU', 'KNUI', 'KNYG', 'KOAJ', 'KOBI', 'KOCW', 'KOFP', 'KOKV', 'KOMH', 'KONX', 'KORF', 'KOXB', 'KPGV', 'KPHF', 'KPMZ', 'KPOB', 'KPTB', 'KPVG', 'KRCZ', 'KRDU', 'KRIC', 'KRJD', 'KRMN', 'KROA', 'KRWI', 'KRZZ', 'KSBY', 'KSCR', 'KSFQ', 'KSHD', 'KSIF', 'KSOP', 'KSSU', 'KTDF', 'KTGI', 'KTTA', 'KVBW', 'KVKX', 'KVQN', 'KWAL', 'KWWD', 'KXSA'],
            // KZJX
            'KZJX' => ['KAAF', 'KABY', 'KAIK', 'KAMG', 'KAQQ', 'KAQX', 'KARW', 'KAYS', 'KAZE', 'KBBP', 'KBGE', 'KBHC', 'KBIJ', 'KBKV', 'KBNL', 'KBQK', 'KCAE', 'KCDK', 'KCDN', 'KCEW', 'KCGC', 'KCHS', 'KCKF', 'KCKI', 'KCLW', 'KCOF', 'KCPC', 'KCQF', 'KCQW', 'KCRE', 'KCRG', 'KCTY', 'KCUB', 'KCWV', 'KCXU', 'KDAB', 'KDED', 'KDHN', 'KDLC', 'KDQH', 'KDTS', 'KDYB', 'KECP', 'KEDN', 'KEGI', 'KEVB', 'KEZM', 'KFDW', 'KFHB', 'KFIN', 'KFLO', 'KFZG', 'KGGE', 'KGNV', 'KGZH', 'KHEG', 'KHEY', 'KHOE', 'KHRT', 'KHVS', 'KHXD', 'KHYW', 'KIGC', 'KINF', 'KISM', 'KJAX', 'KJES', 'KJKA', 'KJYL', 'KJZI', 'KLCQ', 'KLEE', 'KLHW', 'KLOR', 'KLRO', 'KMAI', 'KMAO', 'KMCO', 'KMGR', 'KMHP', 'KMKS', 'KMLB', 'KMMT', 'KMNI', 'KMQW', 'KMUL', 'KMYR', 'KNAE', 'KNBC', 'KNBJ', 'KNBQ', 'KNDZ', 'KNEN', 'KNEX', 'KNFD', 'KNFJ', 'KNGS', 'KNIP', 'KNPA', 'KNRB', 'KNRQ', 'KNSE', 'KNZC', 'KOCF', 'KOGB', 'KOMN', 'KORL', 'KOZR', 'KPAM', 'KPHH', 'KPNS', 'KPYG', 'KRBW', 'KRRF', 'KRVJ', 'KSAV', 'KSFB', 'KSGJ', 'KSMS', 'KSSC', 'KSSI', 'KSUT', 'KSVN', 'KSYV', 'KTBR', 'KTIX', 'KTLH', 'KTMA', 'KTTS', 'KTVI', 'KUDG', 'KVAD', 'KVDF', 'KVDI', 'KVLD', 'KVPS', 'KVQQ', 'KXNO', 'KZPH'],
            // KZHU
            'KZHU' => ['KACP', 'KAEX', 'KALI', 'KAPS', 'KAPY', 'KAQO', 'KARA', 'KARM', 'KASD', 'KAUS', 'KAXH', 'KBAZ', 'KBBD', 'KBCG', 'KBEA', 'KBFM', 'KBIX', 'KBKS', 'KBMQ', 'KBMT', 'KBPT', 'KBRO', 'KBTR', 'KBVE', 'KBXA', 'KBYY', 'KCFD', 'KCLL', 'KCOT', 'KCRP', 'KCVB', 'KCWF', 'KCXO', 'KCZT', 'KDKR', 'KDLF', 'KDRI', 'KDRT', 'KDWH', 'KDZB', 'KEBG', 'KECU', 'KEDC', 'KEFD', 'KELA', 'KERV', 'KESF', 'KEYQ', 'KGAO', 'KGLS', 'KGNI', 'KGPT', 'KGRK', 'KGTU', 'KGYB', 'KHBG', 'KHBV', 'KHDC', 'KHDO', 'KHEZ', 'KHLR', 'KHOU', 'KHPY', 'KHRL', 'KHRL', 'KHSA', 'KHUM', 'KHYI', 'KHZR', 'KIAH', 'KIAH', 'KIKG', 'KILE', 'KIWS', 'KIYA', 'KJAS', 'KJCT', 'KLBX', 'KLCH', 'KLFK', 'KLFT', 'KLHB', 'KLIX', 'KLRD', 'KLVJ', 'KLZZ', 'KMCB', 'KMFE', 'KMJD', 'KMKV', 'KMOB', 'KMSY', 'KNBG', 'KNEW', 'KNGP', 'KNGT', 'KNGW', 'KNMT', 'KNOG', 'KNQI', 'KNVT', 'KNWL', 'KOPL', 'KORG', 'KOZA', 'KPCU', 'KPEZ', 'KPGL', 'KPIB', 'KPIL', 'KPKV', 'KPOE', 'KPQL', 'KPSX', 'KPTN', 'KRAS', 'KRBO', 'KRCK', 'KREG', 'KRFG', 'KRKP', 'KRND', 'KRPE', 'KRWV', 'KRYW', 'KSAT', 'KSEQ', 'KSGR', 'KSKF', 'KSLJ', 'KSOA', 'KSSF', 'KTFP', 'KTME', 'KTPL', 'KTXW', 'KUTS', 'KUVA', 'KUXL', 'KVCT', 'KVRX', 'KVUW'],
            // KZME
            'KZME' => ['KADF', 'KAIV', 'KAPT', 'KARG', 'KASG', 'KAWM', 'KAYX', 'KAZU', 'KBBG', 'KBDQ', 'KBGF', 'KBNA', 'KBPK', 'KBVX', 'KBWG', 'KBYH', 'KCBM', 'KCCA', 'KCEY', 'KCGI', 'KCHQ', 'KCIR', 'KCKM', 'KCKV', 'KCMD', 'KCRT', 'KCRX', 'KCVK', 'KCXW', 'KDRP', 'KDXE', 'KDYR', 'KEIW', 'KEOD', 'KFCY', 'KFLP', 'KFSM', 'KFSM', 'KFYE', 'KFYM', 'KFYV', 'KGHM', 'KGLH', 'KGLW', 'KGNF', 'KGTR', 'KGWO', 'KGZS', 'KHAB', 'KHBZ', 'KHEE', 'KHKA', 'KHKS', 'KHOP', 'KHOT', 'KHRO', 'KHUA', 'KHVC', 'KHZD', 'KIDL', 'KJAN', 'KJBR', 'KJSV', 'KJVW', 'KJWN', 'KLIT', 'KLLQ', 'KLMS', 'KLRF', 'KLUG', 'KLUL', 'KLZK', 'KMAW', 'KMBO', 'KMBT', 'KMDQ', 'KMEI', 'KMEM', 'KMEZ', 'KMKL', 'KMMS', 'KMPE', 'KMPJ', 'KMQY', 'KMRC', 'KMSL', 'KMXA', 'KNJW', 'KNMM', 'KNQA', 'KOLV', 'KORK', 'KOSX', 'KPAH', 'KPBF', 'KPGR', 'KPHT', 'KPLK', 'KPMU', 'KPOF', 'KPVE', 'KPYN', 'KRBM', 'KRKR', 'KRNC', 'KRNV', 'KROG', 'KRUE', 'KSGT', 'KSIK', 'KSLG', 'KSNH', 'KSRB', 'KSRC', 'KSTF', 'KSUZ', 'KSYI', 'KSZY', 'KTGC', 'KTHA', 'KTKX', 'KTQH', 'KTUP', 'KTVR', 'KTWT', 'KTZV', 'KUBS', 'KUCY', 'KUNO', 'KUOS', 'KUOX', 'KUTA', 'KVBT', 'KVKS', 'KXNA', 'KXNX']
        ],
        'fno_or_live_only' => [
            // KZMA
            'KZMA' => ['KAGR', 'KAPF', 'KAVO', 'KBCT', 'KBOW', 'KCHN', 'KCOI', 'KEYW', 'KFLL', 'KFMY', 'KFPR', 'KFXE', 'KGIF', 'KHST', 'KHWO', 'KIMM', 'KLAL', 'KLNA', 'KMCF', 'KMIA', 'KMKY', 'KMTH', 'KNQX', 'KOBE', 'KOPF', 'KPBI', 'KPCM', 'KPGD', 'KPHK', 'KPIE', 'KPMP', 'KRSW', 'KSEF', 'KSPG', 'KSRQ', 'KSUA', 'KTBW', 'KTMB', 'KTNT', 'KTPA', 'KTPF', 'KVNC', 'KVRB', 'KXMR', 'MBAC', 'MBNC', 'MBPI', 'MBSC', 'MBSY'],
            // KZNY
            'KZNY' => ['KABE', 'KAVP', 'KBDR', 'KBGM', 'KBLM', 'KCDW', 'KCKZ', 'KCTO', 'KCXY', 'KCZG', 'KDMW', 'KDXR', 'KDYL', 'KELM', 'KEVY', 'KEWR', 'KFOK', 'KFRG', 'KFWN', 'KHPN', 'KHTO', 'KHVN', 'KHWV', 'KHZL', 'KILG', 'KIPT', 'KISP', 'KJFK', 'KJRB', 'KLDJ', 'KLGA', 'KLHV', 'KLNS', 'KLOM', 'KMDT', 'KMGJ', 'KMJX', 'KMMU', 'KMPO', 'KMQS', 'KMUI', 'KNEL', 'KOQN', 'KOXC', 'KPHL', 'KPNE', 'KPOU', 'KPSB', 'KPTW', 'KRDG', 'KRVL', 'KSEG', 'KSMQ', 'KSWF', 'KTEB', 'KTHV', 'KTTN', 'KUKT', 'KUNV', 'KVAY', 'KWBW', 'KWRI', 'KXLL', 'KZER']
        ]
    ];

    protected array $event_position_preset = [
        "STBY | Standby",
        "ZTL | Atlanta Center",
        "A80 | Sattelite ATCT",
        "A80 | Atlanta TRACON",
        "ATL | Atlanta ATCT",
        "CLT | Satellite ATCT",
        "CLT | Charlotte TRACON",
        "CLT | Charlotte ATCT",
        "CIC/TMU",
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $this->info("-- Updating support events - this may take a while --");

        $this->info("Building ARTCC LUTs");

        $all_events_lut = [];
        $fno_or_live_only_lut = [];
        $inverse_lut = [];

        foreach ($this->event_pull_lut["all_events"] as $artcc => $airports) {
            foreach ($airports as $airport) {
                $all_events_lut[] = $airport;
                $inverse_lut[$airport] = $artcc;
            }
        }
        foreach ($this->event_pull_lut["fno_or_live_only"] as $artcc => $airports) {
            foreach ($airports as $airport) {
                $fno_or_live_only_lut[] = $airport;
                $inverse_lut[$airport] = $artcc;
            }
        }


        $client = new Client();

        $this->info('Downloading event info from VATSIM');

        $res = $client->get('https://my.vatsim.net/api/v1/events/all');

        $this->info('Parsing event info JSON');

        $result = json_decode($res->getBody());
        if (is_null($result)) {
            return;
        }

        foreach ($result->data as $event) {
            // If an even already exists associated with this myVATSIM event, skip it
            // This won't filter out our events, but this is done shortly
            $existing = Event::where('vatsim_id', $event->id)->first();
            if ($existing !== null) {
                $this->info("Skipping already processed event ".$event->id);
                continue;
            } // this event has already been processed

            $pull_this_event = false;
            $organizer = null;

            // parse times
            $start_time = Carbon::parse($event->start_time);

            if (Carbon::now()->diffInMonths($start_time) > 4) {
                continue; // skip events that are too far out or too far in the past
            }

            $end_time = Carbon::parse($event->end_time);

            // Airport check: is this a facility we care about?
            // Checked based off the list of airports pulled from vatSpy datafile.
            foreach ($event->airports as $airport) {
                if (in_array($airport->icao, $all_events_lut)) {
                    $pull_this_event = true;
                    $organizer = $inverse_lut[$airport->icao];
                    break;
                }
                if (in_array($airport->icao, $fno_or_live_only_lut)) {
                    // is it a live event?
                    if (str_contains(strtolower($event->name), 'live')) {
                        // live event - pull this event too
                        $pull_this_event = true;
                        $organizer = $inverse_lut[$airport->icao];
                        break;
                    }
                    // is it on a friday? (FNO)
                    if ($start_time->dayOfWeek == 6) { // 06: Friday
                        // FNO - pull this event too
                        $pull_this_event = true;
                        $organizer = $inverse_lut[$airport->icao];
                        break;
                    }
                }
            }

            if (!$pull_this_event) {
                continue;
            }

            $this->info('Creating support event with vatsim id '.$event->id);

            // create the event in our database

            $this->info($event->id.': Saving to database');

            $new_event = new Event;
            $new_event->name = $event->name;
            $new_event->host = $organizer;
            $new_event->description = $event->description;
            $new_event->date = $start_time->format("m/d/Y");
            $new_event->start_time = $start_time->toTimeString('minute');
            $new_event->end_time = $end_time->toTimeString('minute');

            $new_event->banner_path = $event->banner;

            $new_event->status = 0;
            $new_event->reg = 0;
            $new_event->type = 2; // auto - unverified
            $new_event->vatsim_id = $event->id;
            $new_event->save();

            $new_event_id = Event::where('vatsim_id', $event->id)->first()->id;

            foreach ($this->event_position_preset as $position_name) {
                $new_event_position = new EventPosition;
                $new_event_position->event_id = $new_event_id;
                $new_event_position->name = $position_name;
                $new_event_position->save();
            }

            $this->info('Created ' . $event->id);
        }
    }
}
