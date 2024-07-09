<?php

if (isset($pageVars["piranhaResult"]['error'])) {

    echo $pageVars["piranhaResult"]['error'];

} else {
//var_dump($pageVars["piranhaResult"]) ;
    $outVar = "" ;
    $resultKeys = array_keys($pageVars["piranhaResult"]);
    $resultKey = $resultKeys[0] ;
    if(in_array($resultKey, array("Hosted-Zone", 'domains','hosted-zones', 'zones'))) {
        $outVar .=$resultKey." : \n\n";
        foreach($pageVars["piranhaResult"][$resultKey]['HostedZones'] as $key=>$value) {
            $outVar .= $key.")";
            $outVar .="Id: ".$value['Id']."\n";
            $outVar .="Name: ".$value['Name']."\n";
            $outVar .="CallerReference: ".$value['CallerReference']."\n";
            $outVar .="Config ";
            foreach($value['Config'] as $k => $v) {
                $outVar .= $k." : ".$v."\n";
            }
            $outVar .="ResourceRecordSetCount: ".$value['ResourceRecordSetCount']."\n \n";
        }
    }

    if(in_array($resultKey, array("Health-check", "HealthChecks", 'healthcheck', 'healthchecks'))) {
        $outVar .=$resultKey." : \n\n";
        foreach($pageVars["piranhaResult"][$resultKey]['HealthChecks'] as $key=>$value) {
            $outVar .= $key.")";
            $outVar .="Id: ".$value['Id']."\n";
            $outVar .="CallerReference: ".$value['CallerReference']."\n";
            $outVar .="HealthCheckConfig \n";
            foreach($value['HealthCheckConfig'] as $k => $v) {
                $outVar .= $k." : ".$v."\n";
            }
            $outVar .="HealthCheckVersion: ".$value['ResourceRecordSetCount']."\n \n";
        }
    }

    if(in_array($resultKey, array("ResourceRecordSets", 'records', 'Records'))) {
        $outVar .=$resultKey." : \n\n";
        foreach($pageVars["piranhaResult"][$resultKey]['ResourceRecordSets'] as $key=>$value) {
            $outVar .= $key.")";
            $outVar .="Name: ".$value['Name']."\n";
            $outVar .="Type: ".$value['Type']."\n";
            $outVar .="TTL: ".$value['TTL']."\n";
            $outVar .="ResourceRecords \n";
            foreach($value['ResourceRecords'] as $k => $v) {
                $outVar .= $k." : ".$v['Value']."\n";
            }
        }
    }
    echo $outVar;

}

?>

------------------------------
Piranha DNS Listing Finished