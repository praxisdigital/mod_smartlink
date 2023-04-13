<?php

namespace mod_smartlink;

use Exception;
use stdClass;

class helper {

    public static function get_available_languages() 
    {
        $langlist = array();

        $eng = new \stdClass();
        $eng->code = 'en';
        $eng->name = get_string('eng', 'iso6392');
        array_push($langlist, $eng);

        $swe = new \stdClass();
        $swe->code = 'sv';
        $swe->name = get_string('swe', 'iso6392');
        array_push($langlist, $swe);

        $dan = new \stdClass();
        $dan->code = 'da';
        $dan->name = get_string('dan', 'iso6392');
        array_push($langlist, $dan);

        return $langlist;
    }
}

?>