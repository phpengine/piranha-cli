<?php


function getListAsCLITable($list_entries) {
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
        if (strlen($key) > $value) {
            $max_lengths[$key] = strlen($key)+1 ;
        }
    }
//    var_dump($max_lengths);
    // get titles
    foreach($max_lengths as $key=>$value) {
        $outvar .= "$key".getSpaceString($value-strlen($key)) ;
    }
    $outvar .= "\n" ;
    // get
    foreach($list_entries as $list_entry) {
        foreach($list_entry as $key=>$value) {
            if (is_string($value)) {
                $chars = $max_lengths[$key] - strlen($value) ;
                $outvar .= "$value".getSpaceString($chars) ;
            } else {
                if (isset($max_lengths[$key])) {
                    $chars = $max_lengths[$key] - strlen( "Array") ;
                    $outvar .= "Array".getSpaceString($chars) ;
                } else {
                    $chars = $max_lengths[$key] - strlen( "NULL") ;
                    $outvar .= "NULL".getSpaceString($chars) ;
                }
            }
        }
        $outvar .= "\n" ;
    }
    return $outvar ;
}
function getSpaceString($chars) {
    $string = '' ;
    for ($i=0; $i<$chars; $i++) {
        $string .= ' ';
    }
    return $string;
}

if (isset($pageVars["piranhaResult"]['error'])) {

    echo $pageVars["piranhaResult"]['error'];

} else {
//    var_dump($pageVars["piranhaResult"]) ;
    $outVar = "" ;

//    if(in_array($resultKey, array("Health-check", "HealthChecks", 'healthcheck', 'healthchecks'))) {
//        $outVar .=$resultKey." : \n\n";
//        foreach($pageVars["piranhaResult"][$resultKey]['HealthChecks'] as $key=>$value) {
//            $outVar .= $key.")";
//            $outVar .="Id: ".$value['Id']."\n";
//            $outVar .="CallerReference: ".$value['CallerReference']."\n";
//            $outVar .="HealthCheckConfig \n";
//            foreach($value['HealthCheckConfig'] as $k => $v) {
//                $outVar .= $k." : ".$v."\n";
//            }
//            $outVar .="HealthCheckVersion: ".$value['ResourceRecordSetCount']."\n \n";
//        }
//    }

    if(array_key_exists('records', $pageVars["piranhaResult"]['data'])) {
        $outVar = getListAsCLITable($pageVars["piranhaResult"]['data']['records']) ;
    }
    echo $outVar;

}

?>
