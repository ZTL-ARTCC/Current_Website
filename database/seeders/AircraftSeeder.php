<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AircraftSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('aircraft')->insert([
            'ac_type' => 'A19N',
            'icao_wtc' => 'M',
            'equipment' => 'SDE3FGHIRWY',
            'transponder' => 'LB1',
            'perf_cat' => 'C',
            'pbn' => 'A1B1C1D1O1S1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'A20N',
            'icao_wtc' => 'M',
            'equipment' => 'SDE2E3FGHIJ1RWXY',
            'transponder' => 'LB1',
            'perf_cat' => 'C',
            'pbn' => 'A1B1C1D1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'A21N',
            'icao_wtc' => 'M',
            'equipment' => 'SDE3FGHIRWY',
            'transponder' => 'LB1',
            'perf_cat' => 'C',
            'pbn' => 'A1B1C1D1O1S1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'A306',
            'icao_wtc' => 'H',
            'equipment' => 'SDE3FGHIRWY',
            'transponder' => 'LB1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1L1O1S1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'A30B',
            'icao_wtc' => 'H',
            'equipment' => 'SDE3FGHIRWY',
            'transponder' => 'LB1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1L1O1S1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'A310',
            'icao_wtc' => 'H',
            'equipment' => 'SDFGHIM1RTUWXYZ',
            'transponder' => 'LB1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1L1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'A318',
            'icao_wtc' => 'M',
            'equipment' => 'SDE3FGHIRWY',
            'transponder' => 'LB1',
            'perf_cat' => 'C',
            'pbn' => 'A1B1C1D1O1S1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'A319',
            'icao_wtc' => 'M',
            'equipment' => 'SDE3FGHIRWY',
            'transponder' => 'LB1',
            'perf_cat' => 'C',
            'pbn' => 'A1B1C1D1O1S1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'A320',
            'icao_wtc' => 'M',
            'equipment' => 'SDE3FGHIRWY',
            'transponder' => 'LB1',
            'perf_cat' => 'C',
            'pbn' => 'A1B1C1D1O1S1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'A321',
            'icao_wtc' => 'M',
            'equipment' => 'SDE3FGHIRWY',
            'transponder' => 'LB1',
            'perf_cat' => 'C',
            'pbn' => 'A1B1C1D1O1S1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'A332',
            'icao_wtc' => 'H',
            'equipment' => 'SDE3GHIJ2J3J5M1RVWXYZ',
            'transponder' => 'LB2D1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'A333',
            'icao_wtc' => 'H',
            'equipment' => 'SDE3GHIJ2J3J5M1RVWXYZ',
            'transponder' => 'LB2D1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'A338',
            'icao_wtc' => 'H',
            'equipment' => 'SADE1E2E3GHIJ1J3J5M1P2RWXY',
            'transponder' => 'LB2D1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1L1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'A339',
            'icao_wtc' => 'H',
            'equipment' => 'SADE1E2E3GHIJ1J3J5M1P2RWXY',
            'transponder' => 'LB2D1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1L1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'A342',
            'icao_wtc' => 'H',
            'equipment' => 'SDE2E3FGHIJ4J5M1RWXYZ',
            'transponder' => 'LB1D1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'A343',
            'icao_wtc' => 'H',
            'equipment' => 'SDE2E3FGHIJ4J5M1RWXYZ',
            'transponder' => 'LB1D1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'A345',
            'icao_wtc' => 'H',
            'equipment' => 'SDE2E3FGHIJ3J4J5M1RWXYZ',
            'transponder' => 'LB1D1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1L1O1S1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'A346',
            'icao_wtc' => 'H',
            'equipment' => 'SDE2E3FGHIJ3J4J5M1RWXYZ',
            'transponder' => 'LB1D1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1L1O1S1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'A359',
            'icao_wtc' => 'H',
            'equipment' => 'SDE2E3GHIJ1J3J4J5LM1ORWXYZ',
            'transponder' => 'LB1D1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1L1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'A35K',
            'icao_wtc' => 'H',
            'equipment' => 'SDE2E3GHIJ1J3J4J5LM1ORWXYZ',
            'transponder' => 'LB1D1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1L1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'A388',
            'icao_wtc' => 'H',
            'equipment' => 'SADE2E3FGHIJ3J4J5M1RWXYZ',
            'transponder' => 'LB1D1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1L1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B37M',
            'icao_wtc' => 'M',
            'equipment' => 'SDE1FGHILORVWXY',
            'transponder' => 'HB1',
            'perf_cat' => 'C',
            'pbn' => 'A1B1C1D1L1O1S1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B38M',
            'icao_wtc' => 'M',
            'equipment' => 'SDE1FGHILORVWXY',
            'transponder' => 'HB1',
            'perf_cat' => 'C',
            'pbn' => 'A1B1C1D1L1O1S1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B39M',
            'icao_wtc' => 'M',
            'equipment' => 'SDE1FGHILORVWXY',
            'transponder' => 'HB1',
            'perf_cat' => 'C',
            'pbn' => 'A1B1C1D1L1O1S1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B712',
            'icao_wtc' => 'M',
            'equipment' => 'SDFGHRWY',
            'transponder' => 'C',
            'perf_cat' => 'C',
            'pbn' => 'A1B2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B737',
            'icao_wtc' => 'M',
            'equipment' => 'SDE2E3FGHIRWXY',
            'transponder' => 'LB1',
            'perf_cat' => 'C',
            'pbn' => 'A1B1C1D1S1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B738',
            'icao_wtc' => 'M',
            'equipment' => 'SDE2E3FGHIRWXY',
            'transponder' => 'LB1',
            'perf_cat' => 'C',
            'pbn' => 'A1B1C1D1S1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B739',
            'icao_wtc' => 'M',
            'equipment' => 'SDE2E3FGHIRWXY',
            'transponder' => 'LB1',
            'perf_cat' => 'C',
            'pbn' => 'A1B1C1D1S1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B74F',
            'icao_wtc' => 'H',
            'equipment' => 'SDE3FGHIM1M2RWXYZ',
            'transponder' => 'LB1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1D1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B748',
            'icao_wtc' => 'H',
            'equipment' => 'SDE3FGHIM1M2RWXYZ',
            'transponder' => 'LB1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1D1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B48F',
            'icao_wtc' => 'H',
            'equipment' => 'SDE3FGHIM1M2RWXYZ',
            'transponder' => 'LB1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1D1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B752',
            'icao_wtc' => 'M',
            'equipment' => 'SDE2E3FGHIJ3J4J7RWXY',
            'transponder' => 'LB1',
            'perf_cat' => 'C',
            'pbn' => 'A1B1D1L1O1S1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B753',
            'icao_wtc' => 'M',
            'equipment' => 'SDE2E3FGHIJ3J4J7RWXY',
            'transponder' => 'LB1',
            'perf_cat' => 'C',
            'pbn' => 'A1B1D1L1O1S1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B762',
            'icao_wtc' => 'H',
            'equipment' => 'SDFGIRWY',
            'transponder' => 'S',
            'perf_cat' => 'D',
            'pbn' => 'A1B1D1O1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B763',
            'icao_wtc' => 'H',
            'equipment' => 'SDE3FGHIM3RWXYZ',
            'transponder' => 'LB1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1L1O1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B76F',
            'icao_wtc' => 'H',
            'equipment' => 'SDE3FGHIM3RWXYZ',
            'transponder' => 'LB1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1L1O1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B764',
            'icao_wtc' => 'H',
            'equipment' => 'SDE3FGHIM3RWXY',
            'transponder' => 'LB1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1L1O1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B772',
            'icao_wtc' => 'H',
            'equipment' => 'SDE2E3FGHIJ5LORVWXY',
            'transponder' => 'LB1D1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1D1S2T1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B77F',
            'icao_wtc' => 'H',
            'equipment' => 'SDE1E2E3FGHIJ2J3J4J5M1RWXYZ',
            'transponder' => 'LB1D1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1L1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B77L',
            'icao_wtc' => 'H',
            'equipment' => 'SDE1E2E3FGHIJ2J3J4J5M1RWXYZ',
            'transponder' => 'LB1D1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1L1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B77W',
            'icao_wtc' => 'H',
            'equipment' => 'SDE1E2E3FGHIJ2J3J4J5M1RWXYZ',
            'transponder' => 'LB1D1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1L1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B788',
            'icao_wtc' => 'H',
            'equipment' => 'SDE1E2E3FGHIJ2J3J4J5M1RWXYZ',
            'transponder' => 'LB1D1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1L1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B789',
            'icao_wtc' => 'H',
            'equipment' => 'SDE1E2E3FGHIJ2J3J4J5M1RWXYZ',
            'transponder' => 'LB1D1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1L1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'B78X',
            'icao_wtc' => 'H',
            'equipment' => 'SDE1E2E3FGHIJ2J3J4J5M1RWXYZ',
            'transponder' => 'LB1D1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1L1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'DC10',
            'icao_wtc' => 'H',
            'equipment' => 'SDE1E2E3FGHIJ2J3J4J5M1RWXYZ',
            'transponder' => 'LB1D1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1L1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'MD11',
            'icao_wtc' => 'H',
            'equipment' => 'SDE1E2E3FGHIJ2J3J4J5M1RWXYZ',
            'transponder' => 'LB1D1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1L1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'MD1F',
            'icao_wtc' => 'H',
            'equipment' => 'SDE1E2E3FGHIJ2J3J4J5M1RWXYZ',
            'transponder' => 'LB1D1',
            'perf_cat' => 'D',
            'pbn' => 'A1B1C1D1L1O1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'CRJ2',
            'icao_wtc' => 'M',
            'equipment' => 'SDFGIRWZ',
            'transponder' => 'S',
            'perf_cat' => 'C',
            'pbn' => 'D1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'CRJ5',
            'icao_wtc' => 'M',
            'equipment' => 'SDFGIRWYZ',
            'transponder' => 'S',
            'perf_cat' => 'C',
            'pbn' => 'D1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'CRJ7',
            'icao_wtc' => 'M',
            'equipment' => 'SDFGIRWYZ',
            'transponder' => 'S',
            'perf_cat' => 'C',
            'pbn' => 'D1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'CRJ9',
            'icao_wtc' => 'M',
            'equipment' => 'SDFGIRWYZ',
            'transponder' => 'S',
            'perf_cat' => 'C',
            'pbn' => 'D1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'CRJX',
            'icao_wtc' => 'M',
            'equipment' => 'SDFGIRWYZ',
            'transponder' => 'S',
            'perf_cat' => 'C',
            'pbn' => 'D1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'E135',
            'icao_wtc' => 'M',
            'equipment' => 'SDFGIRWZ',
            'transponder' => 'S',
            'perf_cat' => 'C',
            'pbn' => 'D1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'E140',
            'icao_wtc' => 'M',
            'equipment' => 'SDFGIRWZ',
            'transponder' => 'S',
            'perf_cat' => 'C',
            'pbn' => 'D1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'E145',
            'icao_wtc' => 'M',
            'equipment' => 'SDFGIRWZ',
            'transponder' => 'S',
            'perf_cat' => 'C',
            'pbn' => 'D1'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'E170',
            'icao_wtc' => 'M',
            'equipment' => 'SDE3GILORVW',
            'transponder' => 'S',
            'perf_cat' => 'C',
            'pbn' => 'B2B3B4B5C1D1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'E175',
            'icao_wtc' => 'M',
            'equipment' => 'SDE3GILORVW',
            'transponder' => 'S',
            'perf_cat' => 'C',
            'pbn' => 'B2B3B4B5C1D1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'E190',
            'icao_wtc' => 'M',
            'equipment' => 'SDE3GILORVW',
            'transponder' => 'S',
            'perf_cat' => 'C',
            'pbn' => 'B2B3B4B5C1D1S2'
        ]);
        DB::table('aircraft')->insert([
            'ac_type' => 'E195',
            'icao_wtc' => 'M',
            'equipment' => 'SDE3GILORVW',
            'transponder' => 'S',
            'perf_cat' => 'C',
            'pbn' => 'B2B3B4B5C1D1S2'
        ]);
    }
}
