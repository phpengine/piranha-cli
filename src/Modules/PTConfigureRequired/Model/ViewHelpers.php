<?php

namespace Model;

class ViewHelpers {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("ViewHelpers") ;

    public static $store ;


    public static function isAssoc($array)
    {
        $array = array_keys($array); return ($array !== array_keys($array));
    }

    public static function getListAsCLITable($list_entries) {
        $outvar = '' ;
        $max_lengths = [] ;
        // get max lengths
        foreach($list_entries as $list_entry) {
            foreach($list_entry as $key=>$value) {
                if (is_string($value)) {
                    if (isset($max_lengths[$key])) {
                        $max_lengths[$key] = (strlen($value) > $max_lengths[$key]) ? strlen($value) : $max_lengths[$key] ;
                        $max_lengths[$key] = $max_lengths[$key] + 1;
                    } else {
                        $max_lengths[$key] = strlen($value) + 1;
                    }
                } else {
                    $max_lengths[$key] = strlen($key) + 1;
                }
            }
        }
        // use title length if longer
        foreach($max_lengths as $key=>$value) {
            if (strlen($key) >= $value) {
                $max_lengths[$key] = strlen($key)+1 ;
            }
        }
//    var_dump($max_lengths);
        // get titles
        foreach($max_lengths as $key=>$value) {
            $outvar .= "$key".self::getSpaceString($value-strlen($key)) ;
        }
        $outvar .= "\n" ;
        // get
        foreach($list_entries as $list_entry) {
            foreach($list_entry as $key=>$value) {
                if (is_string($value)) {
                    $chars = $max_lengths[$key] - strlen($value) ;
                    $outvar .= "$value".self::getSpaceString($chars) ;
                } else {
                    if (isset($max_lengths[$key])) {
                        if (is_array($value)) {

                            if (self::isAssoc($value)) {
                                $pre_flat = [] ;
                                foreach($value as $subkey=>$subvalue) {
                                    if (is_string($subvalue)) {
                                        $pre_flat[] = $subkey.'='.$subvalue ;
                                    } else {
                                        $pre_flat[] = $subkey.'=VALUE' ;
                                    }
                                }
                                $flat = join(', ', $pre_flat) ;
                                $chars = $max_lengths[$key] - strlen( $flat) ;
                                $outvar .= $flat.self::getSpaceString($chars) ;
                            } else {
                                $flat = implode(', ', $value) ;
                                $chars = $max_lengths[$key] - strlen( $flat) ;
                                $outvar .= $flat.self::getSpaceString($chars) ;
                            }
                        } else {

                            $chars = $max_lengths[$key] - strlen( "NULL") ;
                            $outvar .= "NULL".self::getSpaceString($chars) ;
//                            $chars = $max_lengths[$key] - strlen( "$value") ;
//                            $outvar .= "$value".self::getSpaceString($chars) ;
                        }
                    } else {
                        $chars = $max_lengths[$key] - strlen( "NULL") ;
                        $outvar .= "NULL".self::getSpaceString($chars) ;
                    }
                }
            }
            $outvar .= "\n" ;
        }
        return $outvar ;
    }
    public static function getSpaceString($chars) {
        $string = '' ;
        for ($i=0; $i<$chars; $i++) {
            $string .= ' ';
        }
        return $string;
    }

}