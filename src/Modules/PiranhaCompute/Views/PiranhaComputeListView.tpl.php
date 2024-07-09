<?php


if (isset($pageVars["piranhaResult"]['error'])) {

    echo $pageVars["piranhaResult"]['error'];

} else {
//    var_dump($pageVars["piranhaResult"]) ;
    $outVar = "" ;

    if(in_array($pageVars["piranhaResult"]['type'], array("instance", "instances", 'vm', 'vms'))) {

        if(array_key_exists('vms', $pageVars["piranhaResult"]['data'])) {
            $instances = $pageVars["piranhaResult"]['data']['vms'] ;
            $new_instances = array();
            foreach($instances as $key=>$value) {
                $new_value = [] ;
                $new_value['name'] = $value['name'] ;
                $new_value['id'] = $value['vm_id'] ;
                $new_value['image'] = $value['image'] ;
                $new_value['memory'] = $value['config_data']['memory'] ;
                $new_value['cores'] = $value['config_data']['cores'] ;
                $new_value['hdd'] = $value['config_data']['hdd'] ;
                $new_value['os_name'] = $value['config']['image.os'] ;
                $new_value['os_release'] = $value['config']['image.release'] ;
                $new_instances[] = $new_value ;
            }
            $outVar = \Model\ViewHelpers::getListAsCLITable($new_instances) ;
        }
    }

    if(in_array($pageVars["piranhaResult"]['type'], array("size", 'sizes'))) {

        if(array_key_exists('sizes', $pageVars["piranhaResult"]['data'])) {
            $outVar = \Model\ViewHelpers::getListAsCLITable($pageVars["piranhaResult"]['data']['sizes']) ;
        }
    }

    if(in_array($pageVars["piranhaResult"]['type'], array("image", 'images'))) {

        if(array_key_exists('images', $pageVars["piranhaResult"]['data'])) {
            $outVar = \Model\ViewHelpers::getListAsCLITable($pageVars["piranhaResult"]['data']['images']) ;
        }
    }

    if(in_array($pageVars["piranhaResult"]['type'], array("keypair", 'keypairs'))) {
        if(array_key_exists('keypairs', $pageVars["piranhaResult"]['data'])) {
            $keypairs = $pageVars["piranhaResult"]['data']['keypairs'] ;
            $new_keypairs = array();
            foreach($keypairs as $key=>$value) {
                unset($value['data']) ;
                unset($value['owner']) ;
                $new_keypairs[$key] = $value ;
            }
            $outVar = \Model\ViewHelpers::getListAsCLITable($new_keypairs) ;
        }
    }

    if(in_array($pageVars["piranhaResult"]['type'], array("keypair-full", 'keypairs-full'))) {

        if(array_key_exists('keypairs', $pageVars["piranhaResult"]['data'])) {
            $outVar = \Model\ViewHelpers::getListAsCLITable($pageVars["piranhaResult"]['data']['keypairs']) ;
        }
    }

    echo $outVar;

}

?>
