<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PilotPassportAirfieldSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('pilot_passport_airfield')->insert([
             'id' => 'ATL',
             'latitude' => 33.6367,
             'longitude' => -84.427863888889,
             'elevation' => 1026,
             'name' => 'Hartsfield/Jackson Atlanta International Airport',
             'description' => "Located in Atlanta Georgia, Hartsfield/Jackson is the world's busiest airport by 
             both aircraft movements and passenger enplanements, and is home to Delta Air Lines. The airport is named 
             after former Atlanta Mayors William B. Hartsfield and Maynard Jackson who were both champions of the 
             airport and played a major role into growing it into the hub that it is today. ATL is also home to the 
             Delta Flight Museum; a must-see attraction for aviation enthusiasts."

         ]);
        DB::table('pilot_passport_airfield')->insert([
           'id' => 'CLT',
           'latitude' => 35.21375,
           'longitude' => -80.949055555556,
           'elevation' => 748,
           'name' => 'Charlotte/Douglas International Airport',
           'description' => 'Located in Charlotte North Carolina, CLT is named after former Charlotte Mayor Ben 
                Elbert Douglas. CLT is home to an American Airlines (formerly US Airways) hub and is the home of
                the regional carrier Piedmont Airlines. CLT is also home to the North Carolina Air National Guard
                145th Airlift Wing currently operating C-17s.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'MGM',
            'latitude' => 32.300638888889,
            'longitude' => -86.393972222222,
            'elevation' => 221,
            'name' => 'Montgomery Regional Airport (Dannelly Field)',
            'description' => 'Dannelly Field located in Montgomery Alabama originally opened as Gunter Army Air
                Field Auxiliary #6 in 1943 and functioned as a base for military pilot training and as a 
                commercial airport served by Eastern Airlines. Ensign Clarence Moore Dannelly Jr, a Montgomery
                native, was a US Navy pilot killed in a training accident. The tradition of civil and military
                operations continues at Dannelly Field today with Delta and American commercial air service and 
                the Alabama Air National Guard 187th Fighter Wing operating F-16s and F-35As.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'BHM',
            'latitude' => 33.563888888889,
            'longitude' => -86.752305555556,
            'elevation' => 650,
            'name' => 'Birmingham-Shuttlesworth International Airport',
            'description' => 'BHM is the busiest airport in the state of Alabama. Named after the human and 
                civil rights advocate Reverend Fred Shuttlesworth, it serves as an important regional airport 
                with occasional scheduled international flights. In the 1940s, Birmingham was considered to 
                host the southern Delta Air Lines hub (presently located at ATL) but was eliminated partially 
                due to a city-imposed aviation fuel tax and its location in the central time zone.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'MXF',
            'latitude' => 32.382944444444,
            'longitude' => -86.365777777778,
            'elevation' => 171,
            'name' => 'Maxwell Air Force Base'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'MCN',
            'latitude' => 32.692833333333,
            'longitude' => -83.649222222222,
            'elevation' => 354,
            'name' => 'Middle Georgia Regional Airport',
            'description' => 'MCN traces its origins to 1941 and was a training site for British Royal Air Force
                Cadets during World War II. When a tornado destroyed the previous municipal airport in 1947, 
                commercial operators relocated to MCN. Macon enjoyed regional carrier service by various 
                operators until Delta ceased operations to ATL in 2013. Currently, the airport is only served 
                by Contour with daily flights to BWI.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'MGE',
            'latitude' => 33.914441666667,
            'longitude' => -84.514247222222,
            'elevation' => 1069,
            'name' => 'Dobbins Air Reserve Base'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'RYY',
            'latitude' => 34.013166666667,
            'longitude' => -84.597027777778,
            'elevation' => 1040,
            'name' => 'Cobb County International Airport-McCollum Field',
            'description' => 'McCollum Field is an important General Aviation reliever airport in the metro 
                Atlanta Georgia area and is the third-busiest airport in the state of Georgia. The airport is 
                named after Herbert Clay McCollum, a former Cobb County Commissioner. RYY airport offers 
                friendly GA service and is a gateway to both Atlanta and the Appalachian Mountains.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'FTY',
            'latitude' => 33.779127777778,
            'longitude' => -84.521366666667,
            'elevation' => 841,
            'name' => 'Fulton County Executive Airport/Charlie Brown Field',
            'description' => 'Named after Atlanta City Councilman Charles M. Brown, Fulton County Airport, or 
                simply "County" as it is known, is a General Aviation reliever airport and is the closest GA 
                airport to downtown Atlanta. FTY is under the Atlanta Class B airspace and is situated in close 
                proximity to ATL, making it a challenging airport to operate in/out of.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'CHA',
            'latitude' => 35.035194444444,
            'longitude' => -85.203555555556,
            'elevation' => 683,
            'name' => 'Chattanooga Metropolitan Airport/Lovell Field',
            'description' => "Lovell Field is named after John Lovell, a local Kiwanis Club and American Red Cross
                leader who advocated for the construction of the airport. The local geography makes Chattanooga an 
                interesting airport to operate and control with many rolling hills and the city/airport nestled in 
                a valley. The GQO 'Choo Choo DME' pays homage to the city's important role as a regional railroad 
                depot."
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'LZU',
            'latitude' => 33.978091666667,
            'longitude' => -83.962363888889,
            'elevation' => 1062,
            'name' => 'Gwinnett County Airport-Briscoe Field'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'PDK',
            'latitude' => 33.876002777778,
            'longitude' => -84.302030555556,
            'elevation' => 998,
            'name' => 'Dekalb-Peachtree Airport',
            'description' => 'PDK is the second-busiest airport in the state of Georgia. It is an 
                important General Aviation reliever airport for ATL and the metro Atlanta area. The 
                airport automated weather goes by Chamblee which is the city that PDK is located 
                within. We all know that PDK hosts a number of flight schools and business aviation 
                focused FBOs, but did you know that PDK has scheduled passenger service to Cincinnati, 
                Memphis, and Destin?'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'TYS',
            'latitude' => 35.811091666667,
            'longitude' => -83.994066666667,
            'elevation' => 986,
            'name' => 'McGhee Tyson Airport',
            'description' => 'McGhee Tyson Airport serves the Knoxville, TN area and is named after 
                Charles McGhee Tyson, a US Naval Aviator killed in World War II. In addition to its 
                commercial terminal, TYS is home to McGhee Tyson Air National Guard Base and the 134th 
                Aerial Refueling Wing operating the KC-135. TYS is a short flight or drive to the 
                Great Smoky Mountains - a beautiful section of Appalachia.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'TRI',
            'latitude' => 36.475208333333,
            'longitude' => -82.407416666667,
            'elevation' => 1519,
            'name' => 'Tri-Cities Airport',
            'description' => 'The Tri-Cities refer to Johnson City TN, Kingsport, TN, and Bristol TN/VA - 
                three municipalities in close proximity to each other in the eastern mountains of 
                Tennessee/southwest Virginia. TRI is a popular airport for Appalachian mountain tourism 
                and driver/spectator attendance for NASCAR races at the nearby Bristol Motor Speedway.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'AVL',
            'latitude' => 35.436113888889,
            'longitude' => -82.542047222222,
            'elevation' => 2164,
            'name' => 'Asheville Regional Airport',
            'description' => 'AVL is nestled in a valley and surrounded by mountainous terrain on three 
                sides, making it a challenging airport for both pilots and controllers when visibility 
                is limited and at night. Did you know that a Concorde visited AVL in 1987? Did you 
                perhaps land on a taxiway? Check your scenery - AVLs runway was recently reconstructed 
                and moved west!'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'HKY',
            'latitude' => 35.741144444444,
            'longitude' => -81.38955,
            'elevation' => 1190,
            'name' => 'Hickory Regional Airport',
            'description' => 'HKY airport located near Hickory North Carolina is a busy towered field 
                with significant flight school, general aviation, and business aviation operations. 
                Delta Connection served HKY until 2005 with service to ATL. Hickory has seen a variety 
                of uses to include military pilot training and various commercial air services 
                throughout the years.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'INT',
            'latitude' => 36.133713888889,
            'longitude' => -80.221988888889,
            'elevation' => 969,
            'name' => 'Smith Reynolds Airport',
            'description' => 'INT is the primary general aviation airfield in the Winston-Salem North 
                Carolina area, but all scheduled passenger service is handled by nearby GSO. The 
                facility was originally named Miller Airport after an early donor and advocate for 
                aviation, but was renamed in 1940 as Smith Reynolds in honor of an early aviation 
                pioneer. Winston-Salem is known as both the Twin City and the Camel City, the former 
                due to the merger of Winston and Salem townships and the latter due to the RJ 
                Reynolds Tobacco Company.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'GSO',
            'latitude' => 36.101327777778,
            'longitude' => -79.941122222222,
            'elevation' => 926,
            'name' => 'Piedmont Triad International Airport',
            'description' => 'GSO airport serves the greater Greensboro and Winston-Salem North 
                Carolina region. GSO is home to the Honda Aircraft Company manufacturing the HondaJet 
                and the airport is a FedEx hub. The name Piedmont Triad refers to the metropolitan area 
                encompassing Greensboro, Winston-Salem, and High Point North Carolina in the Piedmont 
                geographical region of the state.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'VUJ',
            'latitude' => 35.416694444444,
            'longitude' => -80.150791666667,
            'elevation' => 609,
            'name' => 'Stanly County Airport'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'JQF',
            'latitude' => 35.387769444444,
            'longitude' => -80.709133333333,
            'elevation' => 704,
            'name' => 'Concord-Padgett Regional Airport',
            'description' => 'JQF airport is a general aviation reliever airport for the metro Charlotte 
                North Carolina area. Allegiant and Avelo both offer commercial flights from JQF. The 
                airport has a variety of General Aviation uses as well and serves as a base for several 
                NASCAR teams.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'SPA',
            'latitude' => 34.916416666667,
            'longitude' => -81.955777777778,
            'elevation' => 802,
            'name' => 'Spartanburg Downtown Memorial Airport/Simpson Field'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'GSP',
            'latitude' => 34.895672222222,
            'longitude' => -82.218858333333,
            'elevation' => 964,
            'name' => 'Greenville Spartanburg International Airport',
            'description' => 'The GSP airport is a shared project between the cities of Greenville and 
                Spartanburg South Carolina and is the 3rd busiest airport in the state. It is named 
                after Roger Milliken who brokered the deal between Greenville and Spartanburg to share 
                an airport in the interest of better air travel options for the region. Did you know 
                that GSP has scheduled Boeing 747F service from Germany to deliver parts for BMW?'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'GYH',
            'latitude' => 34.758313888889,
            'longitude' => -82.376413888889,
            'elevation' => 956,
            'name' => 'Donaldson Field Airport'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'WRB',
            'latitude' => 32.640172222222,
            'longitude' => -83.591922222222,
            'elevation' => 294,
            'name' => 'Robins Air Force Base'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'CSG',
            'latitude' => 32.516333333333,
            'longitude' => -84.938861111111,
            'elevation' => 398,
            'name' => 'Columbus Airport',
            'description' => 'CSG airport serves the second largest city in Georgia: Columbus. The 
                airport presently is served only by Delta Connection, but has an interesting past of 
                various carriers that used Columbus as an interim stops on longer routes. Columbus is 
                the home of Fort Moore - an important Army Airborne post.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'LSF',
            'latitude' => 32.331780555556,
            'longitude' => -84.987152777778,
            'elevation' => 227,
            'name' => 'Lawson Army Air Field'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'AGS',
            'latitude' => 33.369944444444,
            'longitude' => -81.9645,
            'elevation' => 146,
            'name' => 'Augusta Regional Airport at Bush Field',
            'description' => "Bush Field traces its roots to World War II as an Army Air Corps pilot 
                training facility. In modern times, it serves as an important regional airport for the 
                city of Augusta. During The Masters golf tournament, AGS is an extremely busy airport 
                with some commercial carriers switching from regional to mainline equipment to support 
                the increase in passenger traffic."
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'DNL',
            'latitude' => 33.466583333333,
            'longitude' => -82.039388888889,
            'elevation' => 422,
            'name' => 'Daniel Field Airport',
            'description' => "Augusta's general aviation airport is named Daniel Field after the 1920s 
                mayor Raleigh Daniel. Like many other airfields in the region, Daniel was used by the 
                Army Air Corps in World War II. Despite its relatively short runways, Daniel Field is 
                a very busy airport during The Masters golf tournament."
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'AUO',
            'latitude' => 32.615111111111,
            'longitude' => -85.434,
            'elevation' => 777,
            'name' => 'Auburn University Regional Airport',
            'description' => 'War Eagle! The Robert G. Pitts Field is owned by Auburn University and 
                hosts a sizeable flight school and FBO. This is a popular airport on college football 
                game days and it even has a temporary tower that operates to handle the increased 
                traffic!'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'AHN',
            'latitude' => 33.948641666667,
            'longitude' => -83.325913888889,
            'elevation' => 812,
            'name' => 'Athens/Ben Epps Airport',
            'description' => 'The Athens airport is named after the first aviator in Georgia: Ben T. 
                Epps. AHN has seen commercial air service in the past, but no operators currently have 
                scheduled flights from this airport. Despite the lack of air carriers, the airport does 
                still have a number of large aircraft charters supporting the University of Georgia 
                athletics programs.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'GMU',
            'latitude' => 34.847930555556,
            'longitude' => -82.349997222222,
            'elevation' => 1048,
            'name' => 'Greenville Downtown Airport',
            'description' => 'As with many airports in this region, GMU was an Army Air Corps base in 
                World War II, training many pilots for the war effort. Amelia Earhart flew 
                demonstration flights from the airport in 1931. In 1962, GSP airport opened and 
                commercial air service relocated to the new airport.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'PSK',
            'latitude' => 37.137336111111,
            'longitude' => -80.678480555556,
            'elevation' => 2105
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'HLX',
            'latitude' => 36.766111111111,
            'longitude' => -80.823561111111,
            'elevation' => 2694
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'MWK',
            'latitude' => 36.461361111111,
            'longitude' => -80.553211111111,
            'elevation' => 1269
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'ZEF',
            'latitude' => 38.266894444444,
            'longitude' => -77.449241666667,
            'elevation' => 85
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'N63',
            'latitude' => 36.3015261,
            'longitude' => -80.1483792,
            'elevation' => 631
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '8A7',
            'latitude' => 35.9149150,
            'longitude' => -80.4568064,
            'elevation' => 818
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'EXX',
            'latitude' => 35.781141666667,
            'longitude' => -80.303772222222,
            'elevation' => 733
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'HBI',
            'latitude' => 35.654525,
            'longitude' => -79.894736111111,
            'elevation' => 671,
            'name' => 'Asheboro Regional Airport',
            'description' => 'The Asheboro Regional Airport is located in central North Carolina 
                about half-way between Charlotte and Raleigh. It is a general aviation airport that 
                is quite typical of those found in the Atlantic southeast US. One unique feature is 
                that it is the location of the North Carolina Aviation Museum.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'RUQ',
            'latitude' => 35.645883333333,
            'longitude' => -80.520291666667,
            'elevation' => 772
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'EQY',
            'latitude' => 35.017416666667,
            'longitude' => -80.622055555556,
            'elevation' => 683
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'LKR',
            'latitude' => 34.722905555556,
            'longitude' => -80.854588888889,
            'elevation' => 486
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'N52',
            'latitude' => 34.8638167,
            'longitude' => -80.7479833,
            'elevation' => 602
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'AKH',
            'latitude' => 35.202622222222,
            'longitude' => -81.149875,
            'elevation' => 798
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'DCM',
            'latitude' => 34.789333333333,
            'longitude' => -81.195777777778,
            'elevation' => 657
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'UZA',
            'latitude' => 34.987833333333,
            'longitude' => -81.057166666667,
            'elevation' => 666
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'EHO',
            'latitude' => 35.25575,
            'longitude' => -81.600763888889,
            'elevation' => 847
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'EOE',
            'latitude' => 34.309333333333,
            'longitude' => -81.640666666667,
            'elevation' => 570
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '35A',
            'latitude' => 34.6869528,
            'longitude' => -81.6411667,
            'elevation' => 610
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'IPJ',
            'latitude' => 35.483141666667,
            'longitude' => -81.161505555556,
            'elevation' => 878
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'MRN',
            'latitude' => 35.820222222222,
            'longitude' => -81.611416666667,
            'elevation' => 1270
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'SVH',
            'latitude' => 35.764997222222,
            'longitude' => -80.9539,
            'elevation' => 968
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'UKF',
            'latitude' => 36.223602777778,
            'longitude' => -81.098605555556,
            'elevation' => 1303
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'MKJ',
            'latitude' => 36.89485,
            'longitude' => -81.349947222222,
            'elevation' => 2558,
            'name' => 'Mountain Empire Airport',
            'description' => 'Mountain Empire is located in the far western portion of Virginia along 
                Interstate 81. It is in a beautiful part of the state surrounded by rolling hills, 
                mountains, and lots of trees.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'JFZ',
            'latitude' => 37.063747222222,
            'longitude' => -81.798266666667,
            'elevation' => 2653
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'VJI',
            'latitude' => 36.687102777778,
            'longitude' => -82.033327777778,
            'elevation' => 2087,
            'name' => 'Virginia Highlands Airport',
            'description' => 'Around 1958 Appalachian Power Company began construction of its Clinch 
                River Power Plant at Carbo, Virginia in Russell County, Virginia which is located some 
                30 miles to the north of Abingdon, Virginia. An employee of the company building the 
                plant wanted to live in the Abingdon, VA area and fly to the construction site at 
                Carbo. At the time there were no nearby landing strips in the area so some farm land 
                was leased from what was known as the St. John farm, located about 2 miles west of 
                Abingdon next to US Highway #11. A small dirt strip was graded out of the pasture 
                field and that was the beginning of the airport. '
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '6A4',
            'latitude' => 36.4178453,
            'longitude' => -81.8251339,
            'elevation' => 2241
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'GEV',
            'latitude' => 36.432472222222,
            'longitude' => -81.4185,
            'elevation' => 3178
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'FQD',
            'latitude' => 35.428222222222,
            'longitude' => -81.935077777778,
            'elevation' => 1077
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'LUX',
            'latitude' => 34.507111111111,
            'longitude' => -81.946944444444,
            'elevation' => 698
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'SBO',
            'latitude' => 32.609138888889,
            'longitude' => -82.369944444444,
            'elevation' => 327
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '2J5',
            'latitude' => 32.8930278,
            'longitude' => -81.9652222,
            'elevation' => 240
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'BXG',
            'latitude' => 33.040836111111,
            'longitude' => -82.004263888889,
            'elevation' => 309
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '2J3',
            'latitude' => 32.9859444,
            'longitude' => -82.3848056,
            'elevation' => 328
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'GRD',
            'latitude' => 34.250394444444,
            'longitude' => -82.157808333333,
            'elevation' => 631
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '0A9',
            'latitude' => 36.3715579,
            'longitude' => -82.1727243,
            'elevation' => 1593
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'RVN',
            'latitude' => 36.457580555556,
            'longitude' => -82.885033333333,
            'elevation' => 1255
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'GCY',
            'latitude' => 36.195722222222,
            'longitude' => -82.811361111111,
            'elevation' => 1608
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'LQK',
            'latitude' => 34.809972222222,
            'longitude' => -82.702888888889,
            'elevation' => 1013
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'CEU',
            'latitude' => 34.672222222222,
            'longitude' => -82.885888888889,
            'elevation' => 891
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'AND',
            'latitude' => 34.494586111111,
            'longitude' => -82.709380555556,
            'elevation' => 782
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'EBA',
            'latitude' => 34.095402777778,
            'longitude' => -82.817491666667,
            'elevation' => 615
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'IIY',
            'latitude' => 33.778597222222,
            'longitude' => -82.814497222222,
            'elevation' => 646
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'HQU',
            'latitude' => 33.529730555556,
            'longitude' => -82.516952777778,
            'elevation' => 501
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'OKZ',
            'latitude' => 32.966375,
            'longitude' => -82.837488888889,
            'elevation' => 439
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'DBN',
            'latitude' => 32.564722222222,
            'longitude' => -82.984972222222,
            'elevation' => 311,
            'name' => 'W H Bud Barron Airport',
            'description' => "About the airport's namesake: W. H. 'Bud' Barron was born in 
                Wrightsville, Georgia. A self-taught pilot who flew for 14 years before he began 
                logging his flight hours, he was a pioneer crop-duster during the mid 1930s. As World 
                War II approached, Bud thought he would not get a chance to fly in the service of his 
                country due to his age. He was wrong. On December 31, 1941, shortly after the attack 
                on Pearl Harbor, Barron received a telegram from the Commanding Officer of the Army 
                Air Corps Ferrying Command at Nashville, Tennessee, stating he had been recommended 
                for the position of pilot. His military aviation career started on May 14, 1942, as a 
                Second Lieutenant with the Fourth Ferrying Group. During his service with the United 
                States Army Air Corps Ferrying Command, Barron was promoted to Lieutenant Colonel. As 
                an Air Corps pilot, he delivered bomber, cargo, and fighter planes to Africa, South 
                America, the South Pacific, and Australia. After delivery of the aircraft, Barron 
                transported occupational troops and personnel to Japan and returned wounded paws back 
                to the United States. He was awarded the American Theater Service Medal, the Asiatic 
                Pacific Service Medal, the European African Middle Eastern Service Medal, and the 
                World War II Victory Medal. Barron returned to Dublin, Georgia, following the war as 
                a Lieutenant Colonel and served as Commander of the Air Force Reserve Squadron at 
                Robins Air Force Base, Georgia. He secured a lease on the Laurens County military base 
                that had been built to serve the Naval Hospital in Dublin during the war. This was the 
                beginning of the Georgia Aviation School, the first crop-dusting flight school in the 
                state. Barron became the first manager during the 1950s. Having logged over 33,000 
                hours of flight after World War II and stating 'I could never get enough flying,'. 
                Barron retired on December 31, 1977. In his honor, the Laurens County Airport was 
                renamed the W.H. 'Bud' Barron Airport on January 3, 1978."
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '48A',
            'latitude' => 32.4006581,
            'longitude' => -83.2784064,
            'elevation' => 377
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'MLJ',
            'latitude' => 33.154225,
            'longitude' => -83.241408333333,
            'elevation' => 385
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'CPP',
            'latitude' => 33.597969444444,
            'longitude' => -83.138261111111,
            'elevation' => 689
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '52A',
            'latitude' => 33.6121250,
            'longitude' => -83.4604444,
            'elevation' => 694
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '18A',
            'latitude' => 34.3403611,
            'longitude' => -83.1308056,
            'elevation' => 890
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'AJR',
            'latitude' => 34.499852777778,
            'longitude' => -83.556663888889,
            'elevation' => 1448
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'TOC',
            'latitude' => 34.592811111111,
            'longitude' => -83.296372222222,
            'elevation' => 996
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '1A5',
            'latitude' => 35.2223122,
            'longitude' => -83.4199966,
            'elevation' => 2034
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '24A',
            'latitude' => 35.3172111,
            'longitude' => -83.2097036,
            'elevation' => 2857
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'GKT',
            'latitude' => 35.857758333333,
            'longitude' => -83.528702777778,
            'elevation' => 1014
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'MOR',
            'latitude' => 36.179388888889,
            'longitude' => -83.375444444444,
            'elevation' => 1313
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '3A2',
            'latitude' => 36.4088972,
            'longitude' => -83.5571858,
            'elevation' => 1179
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '0VG',
            'latitude' => 36.6540850,
            'longitude' => -83.2178467,
            'elevation' => 1411
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'JAU',
            'latitude' => 36.334088888889,
            'longitude' => -84.162966666667,
            'elevation' => 1180
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'DKX',
            'latitude' => 35.963833333333,
            'longitude' => -83.873666666667,
            'elevation' => 833
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'RHP',
            'latitude' => 35.195230555556,
            'longitude' => -83.863038888889,
            'elevation' => 1699,
            'name' => 'Western Carolina Regional Airport',
            'description' => 'The airport was constructed in 1946 by was known as Wood Field after 
                its owner. It opened with a grass runway. In the early 1960s, the airfield was 
                regularly used by Berkshire Fine Spinning Associates, Burlington Industries, and 
                the chicken industry. The U.S. Army and U.S. Forest Service used the airport as a 
                base of operations for training and fire spotting respectively.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'DZJ',
            'latitude' => 34.854430555556,
            'longitude' => -83.997319444444,
            'elevation' => 1907,
            'name' => 'Blairsville Airport',
            'description' => 'The Blairsville Airport is located in scenic northern Georgia near the 
                border with Tennessee. It is a great spot for sightseeing and Appalachian mountain 
                flying. Approach from the north and see beautiful Nottely Lake.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '1A3',
            'latitude' => 35.0158161,
            'longitude' => -84.3468311,
            'elevation' => 1789
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'GVL',
            'latitude' => 34.272627777778,
            'longitude' => -83.830225,
            'elevation' => 1277
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'CNI',
            'latitude' => 34.312219444444,
            'longitude' => -84.422152777778,
            'elevation' => 1219,
            'name' => 'Cherokee County Regional Airport',
            'description' => 'Cherokee County is located north of metro Atlanta in the foothills of 
                the Appalachian mountains. CNI (redesignated from 47A) now boasts a 5,003 foot runway, 
                fantastic facilities, and gorgeous views making this a natural stop on our tour.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'CVC',
            'latitude' => 33.632247222222,
            'longitude' => -83.846619444444,
            'elevation' => 820,
            'name' => 'Covington Municipal Airport',
            'description' => 'Although CVC is not technically a general aviation reliever for ATL, 
                it is located relatively close to the metro area and can be a good location to visit 
                the area while avoiding a busier or more costly trip to one of the central airfields. 
                CVC was built in 1963 and has new facilities and an expanded ramp for transient 
                aircraft.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'WDR',
            'latitude' => 33.982688888889,
            'longitude' => -83.667216666667,
            'elevation' => 934
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'D73',
            'latitude' => 33.7825278,
            'longitude' => -83.6928056,
            'elevation' => 875
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'HMP',
            'latitude' => 33.389902777778,
            'longitude' => -84.331036111111,
            'elevation' => 882,
            'name' => 'Atlanta Speedway Airport',
            'description' => 'The Atlanta Speedway Airport is immediately adjacent to Atlanta Motor 
                Speedway - a major track on the NASCAR circuit. It was previously known as Bear Creek 
                Airport, then Clayton County Airport - Tara Field, and then Henry County Airport. 
                Oddly enough Clayton County Georgia sold the airport to neighboring Henry County in 
                2011. HMP is near the real FAA Atlanta Air Route Traffic Control Center (ARTCC).'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '6A2',
            'latitude' => 33.2269722,
            'longitude' => -84.2749444,
            'elevation' => 958
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'OPN',
            'latitude' => 32.955002777778,
            'longitude' => -84.264086111111,
            'elevation' => 798
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'MAC',
            'latitude' => 32.821763888889,
            'longitude' => -83.561936111111,
            'elevation' => 437
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'PXE',
            'latitude' => 32.510580555556,
            'longitude' => -83.767347222222,
            'elevation' => 418,
            'name' => 'Perry-Houston County Airport',
            'description' => 'Opened in 1942, what was to become Perry-Houston County Airport (PXE) 
                was used as an auxiliary training facility for Army pilots. After WW2 ended, the City 
                of Perry, Georgia obtained the field and developed it into a municipal airport, 
                opening in 1947.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'ACJ',
            'latitude' => 32.110805555556,
            'longitude' => -84.188861111111,
            'elevation' => 468
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '53A',
            'latitude' => 32.3030633,
            'longitude' => -84.0074681,
            'elevation' => 345
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '6A1',
            'latitude' => 32.5682358,
            'longitude' => -84.2466875,
            'elevation' => 667
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'FFC',
            'latitude' => 33.357722222222,
            'longitude' => -84.572527777778,
            'elevation' => 808
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'CCO',
            'latitude' => 33.311566666667,
            'longitude' => -84.769755555556,
            'elevation' => 970
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'JZP',
            'latitude' => 34.453472222222,
            'longitude' => -84.457222222222,
            'elevation' => 1535
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '49A',
            'latitude' => 34.6281944,
            'longitude' => -84.5265556,
            'elevation' => 1486
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'MMI',
            'latitude' => 35.399194444444,
            'longitude' => -84.561777777778,
            'elevation' => 874
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'MNV',
            'latitude' => 35.545233333333,
            'longitude' => -84.3804,
            'elevation' => 1031
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'RKW',
            'latitude' => 35.922333333333,
            'longitude' => -84.689777777778,
            'elevation' => 1664
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'CSV',
            'latitude' => 35.951291666667,
            'longitude' => -85.084977777778,
            'elevation' => 1882
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '2A0',
            'latitude' => 35.4862500,
            'longitude' => -84.9310833,
            'elevation' => 718
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'RZR',
            'latitude' => 35.212336111111,
            'longitude' => -84.7992,
            'elevation' => 866
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'DNN',
            'latitude' => 34.722938888889,
            'longitude' => -84.870241666667,
            'elevation' => 709
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'CZL',
            'latitude' => 34.455402777778,
            'longitude' => -84.939155555556,
            'elevation' => 656
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '9A5',
            'latitude' => 34.6884033,
            'longitude' => -85.2903531,
            'elevation' => 776
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'RMG',
            'latitude' => 34.350777777778,
            'longitude' => -85.158666666667,
            'elevation' => 644
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'VPC',
            'latitude' => 34.123147222222,
            'longitude' => -84.848705555556,
            'elevation' => 759
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'PUJ',
            'latitude' => 33.912044444444,
            'longitude' => -84.940619444444,
            'elevation' => 1289
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '4A4',
            'latitude' => 34.0185292,
            'longitude' => -85.1447589,
            'elevation' => 974
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'CTJ',
            'latitude' => 33.631697222222,
            'longitude' => -85.152263888889,
            'elevation' => 1165
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'LGC',
            'latitude' => 33.00915,
            'longitude' => -85.073205555556,
            'elevation' => 694,
            'name' => 'LaGrange-Callaway Airport',
            'description' => 'LaGrange-Callaway Airport derives its name from nearby LaGrange Georgia 
                and Callaway Gardens - a popular get-away spot. This airport was developed in the 1950s
                and had scheduled DC-3 service. It is now a general aviation airport serving beautiful 
                west Georgia.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'PIM',
            'latitude' => 32.840694444444,
            'longitude' => -84.882444444444,
            'elevation' => 902,
            'name' => 'Harris County Airport',
            'description' => 'The Harris County Airport, also known as Pine Mountain Airport is a small 
                general aviation field in west Georgia. It is a popular location for weekend fly-ins 
                and $100 dollar hamburgers due to its close proximity to the Callaway Gardens Resort. A 
                free bus goes between the airfield and the lodge to transport pilots back and forth. On 
                Sundays the Callaway Gardens Lodge hosts a brunch that is hard to beat!'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '7A3',
            'latitude' => 32.8118433,
            'longitude' => -85.2294864,
            'elevation' => 631
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'EUF',
            'latitude' => 31.951305555556,
            'longitude' => -85.128916666667,
            'elevation' => 285
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '11A',
            'latitude' => 31.8833244,
            'longitude' => -85.4852247,
            'elevation' => 435
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '06A',
            'latitude' => 32.4604722,
            'longitude' => -85.6800278,
            'elevation' => 264,
            'name' => 'Moton Field Municipal Airport',
            'description' => 'Moton Field was the only primary flight facility for African-American 
                pilot candidates in the U.S. Army Air Corps (Army Air Forces) during World War II. It 
                was named for Robert Russa Moton, second president of Tuskegee Institute. The remaining
                facilities at this site are now managed by the National Park Service. You can read more 
                about Moton field here: https://www.nps.gov/museum/exhibits/tuskegee_airmen/moton_field.html'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '4A9',
            'latitude' => 34.4736944,
            'longitude' => -85.7213889,
            'elevation' => 877
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'PYP',
            'latitude' => 34.089916666667,
            'longitude' => -85.610083333333,
            'elevation' => 595
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'ANB',
            'latitude' => 33.588166666667,
            'longitude' => -85.858111111111,
            'elevation' => 612
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'ALX',
            'latitude' => 32.91475,
            'longitude' => -85.962944444444,
            'elevation' => 686,
            'name' => 'Thomas C Russell Field Airport',
            'description' => 'Alexander City Airport is located near the scenic Coosa River and Martin 
                Lake in central Alabama. If you are a seaplane pilot, load up here and follow the 
                river or lake through the hills for a great time!'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'TOI',
            'latitude' => 31.86,
            'longitude' => -86.013888888889,
            'elevation' => 397
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '08A',
            'latitude' => 32.5273333,
            'longitude' => -86.3310278,
            'elevation' => 197
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'SCD',
            'latitude' => 33.171833333333,
            'longitude' => -86.305527777778,
            'elevation' => 569
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'PLR',
            'latitude' => 33.558833333333,
            'longitude' => -86.249055555556,
            'elevation' => 485
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'ASN',
            'latitude' => 33.569505555556,
            'longitude' => -86.051202777778,
            'elevation' => 529,
            'name' => 'Talladega Municipal Airport',
            'description' => 'ASN is a significant general aviation airport in eastern Alabama that 
                primarily serves the Talladega Superspeedway. Visit ASN on a race day and you will have 
                an amazing experience with thousands of campers, race cars, and fans. At night, the 
                lights of the nearby racetrack will make you feel like you are landing in a black hole!'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'GAD',
            'latitude' => 33.97265,
            'longitude' => -86.089083333333,
            'elevation' => 569
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '8A0',
            'latitude' => 34.2291111,
            'longitude' => -86.2557500,
            'elevation' => 1032
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '20A',
            'latitude' => 33.9712778,
            'longitude' => -86.3803889,
            'elevation' => 1125
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '4A6',
            'latitude' => 34.6887044,
            'longitude' => -86.0059319,
            'elevation' => 651
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'EKY',
            'latitude' => 33.312611111111,
            'longitude' => -86.926305555556,
            'elevation' => 700
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'EET',
            'latitude' => 33.177777777778,
            'longitude' => -86.783222222222,
            'elevation' => 586
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '02A',
            'latitude' => 32.8504845,
            'longitude' => -86.6114325,
            'elevation' => 585
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '1A9',
            'latitude' => 32.4387222,
            'longitude' => -86.5126944,
            'elevation' => 225
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'PRN',
            'latitude' => 31.845694444444,
            'longitude' => -86.61075,
            'elevation' => 451
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'MVC',
            'latitude' => 31.458055555556,
            'longitude' => -87.351027777778,
            'elevation' => 419
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '61A',
            'latitude' => 31.9797500,
            'longitude' => -87.3391111,
            'elevation' => 143
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'SEM',
            'latitude' => 32.343947222222,
            'longitude' => -86.987805555556,
            'elevation' => 166,
            'name' => 'Craig Field Airport',
            'description' => 'SEM is the site of the former Craig Air Force Base (closed in 1977), 
                which was a primary pilot training location operating the T-37 Tweet and T-38 Talon. 
                The SEM identifier is due to nearby Selma Alabama, home of the Edmund Pettus Bridge
                which was the site of a significant civil rights protest in 1965 over the right of 
                African Americans to vote.'
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'A08',
            'latitude' => 32.5105000,
            'longitude' => -87.3847778,
            'elevation' => 220
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'JFX',
            'latitude' => 33.902,
            'longitude' => -87.313833333333,
            'elevation' => 483
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'TCL',
            'latitude' => 33.220627777778,
            'longitude' => -87.611402777778,
            'elevation' => 170,
            'name' => 'Tuscaloosa National Airport',
            'description' => "Home of the University of Alabama 'Roll Tide! TCL does not have scheduled 
                commercial air service, but it does feature many charters that bring sports teams to 
                and from Tuscaloosa. If you made an approach to runway 30, you might have noticed 
                Bryant-Denny Stadium on your right side."
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '09A',
            'latitude' => 32.1193056,
            'longitude' => -88.1274722,
            'elevation' => 134
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'DYA',
            'latitude' => 32.463833333333,
            'longitude' => -87.954055555556,
            'elevation' => 112
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => 'GE99',
            'latitude' => 34.9141833,
            'longitude' => -83.4594250,
            'elevation' => 2735,
            'name' => "Heaven's Landing Airport",
            'description' => "Heaven's landing is a unique field in our challenge as the only private 
                airfield and the only estate airpark. Nestled on a ridgeline, this airport offers 
                great views, an interesting approach, and an experience unlike any other."
        ]);
        DB::table('pilot_passport_airfield')->insert([
            'id' => '14A',
            'latitude' => 35.6138694,
            'longitude' => -80.8994306,
            'elevation' => 839,
            'name' => 'Lake Norman Airpark',
            'description' => 'The Lake Norman Airpark near Mooresville GA just north of Atlanta is a 
                fun airfield due to its proximity to its namesake lake at the end of the runway. It is 
                also below the Atlanta Class B shelf and a resident airpark, which make operations here 
                even a bit more interesting!'
        ]);
    }
}
