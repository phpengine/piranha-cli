<?php


if (isset($pageVars["piranhaResult"]['error'])) {

    echo $pageVars["piranhaResult"]['error'];

} else {
//    var_dump($pageVars["params"]) ;
    $outVar = "" ;


    if(in_array($pageVars["params"]['list-type'], array("vpc", 'vpcs'))) {
        if(array_key_exists('vpcs', $pageVars["piranhaResult"]['data'])) {
            $keypairs = $pageVars["piranhaResult"]['data']['vpcs'] ;
            $new_keypairs = array();
            foreach($keypairs as $key=>$value) {
                unset($value['config']) ;
                unset($value['used_by']) ;
                unset($value['locations']) ;
                $new_keypairs[$key] = $value ;
            }
            $outVar = \Model\ViewHelpers::getListAsCLITable($new_keypairs) ;
        }
    }

    if(in_array($pageVars["params"]['list-type'], array("vpc-full", 'vpcs-full'))) {

        if(array_key_exists('vpcs', $pageVars["piranhaResult"]['data'])) {
            $outVar = \Model\ViewHelpers::getListAsCLITable($pageVars["piranhaResult"]['data']['vpcs']) ;
        }
    }


    echo $outVar;

}

?>
