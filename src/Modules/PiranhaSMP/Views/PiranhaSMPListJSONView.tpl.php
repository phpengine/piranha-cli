<?php

if (isset($pageVars["piranhaResult"]['error'])) {
    $error = $pageVars["piranhaResult"]['error'] ;
    $outVar = array('error' => $error) ;
    echo json_encode($outVar, JSON_PRETTY_PRINT) ;
} elseif (isset($pageVars["piranhaResult"]['data'])) {
    $data = $pageVars["piranhaResult"]['data'] ;
    $outVar = array('HostedZones' => $data['HostedZones']) ;
    echo json_encode($outVar, JSON_PRETTY_PRINT) ;
}
//    $data = $pageVars["piranhaResult"]['data'] ;
//    var_dump($data['HostedZones']) ;
//    $outVar = array('HostedZones' => $data['HostedZones']) ;
//    echo json_encode($outVar, JSON_PRETTY_PRINT) ;
//    if(in_array($resultKey, array("Health-check", "HealthChecks", 'healthcheck', 'healthchecks'))) {
//        $outVar .=$resultKey." : \n\n";
//
//        foreach($pageVars["piranhaResult"][$resultKey]['HealthChecks'] as $key=>$value)
//        {
//
//            $outVar .= $key.")";
//            $outVar .="Id: ".$value['Id']."\n";
//
//            $outVar .="CallerReference: ".$value['CallerReference']."\n";
//            $outVar .="HealthCheckConfig \n";
//            foreach($value['HealthCheckConfig'] as $k => $v)
//            {
//                $outVar .= $k." : ".$v."\n";
//            }
//            $outVar .="HealthCheckVersion: ".$value['ResourceRecordSetCount']."\n \n";
//
//
//
//        }
//
//
//    }
//    if(in_array($resultKey, array("ResourceRecordSets", 'records', 'Records'))) {
//        $outVar .=$resultKey." : \n\n";
//
//        foreach($pageVars["piranhaResult"][$resultKey]['ResourceRecordSets'] as $key=>$value)
//        {
//
//            $outVar .= $key.")";
//            $outVar .="Name: ".$value['Name']."\n";
//
//            $outVar .="Type: ".$value['Type']."\n";
//            $outVar .="TTL: ".$value['TTL']."\n";
//            $outVar .="ResourceRecords \n";
//            foreach($value['ResourceRecords'] as $k => $v)
//            {
//                $outVar .= $k." : ".$v['Value']."\n";
//            }
//
//
//
//        }
//
//
//    }

//    echo $outVar;



?>