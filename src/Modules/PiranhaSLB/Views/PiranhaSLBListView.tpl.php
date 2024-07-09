<?php


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
        $outVar = \Model\ViewHelpers::getListAsCLITable($pageVars["piranhaResult"]['data']['records']) ;
    }
    echo $outVar;

}

?>
