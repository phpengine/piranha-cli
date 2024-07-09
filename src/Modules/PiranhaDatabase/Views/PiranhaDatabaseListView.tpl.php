<?php


if (isset($pageVars["piranhaResult"]['error'])) {

    echo $pageVars["piranhaResult"]['error'];

} else {
//    var_dump($pageVars["piranhaResult"]) ;
    $outVar = "" ;

    if(in_array($pageVars["piranhaResult"]['type'], array("instance", "instances", 'vm', 'vms'))) {

        if(array_key_exists('instances', $pageVars["piranhaResult"]['data'])) {
            $instances = $pageVars["piranhaResult"]['data']['instances'] ;
            $new_instances = array();
            foreach($instances as $key=>$value) {
                $new_value = [] ;
                $new_value['name'] = $value['name'] ;
                $new_value['id'] = $value['vm_id'] ;
                $new_value['image'] = $value['image'] ;
                $new_value['image-slug'] = $value['expanded_config']['image.description'] ;
                $new_value['memory'] = $value['config_data']['memory'] ;
                $new_value['cores'] = $value['config_data']['cores'] ;
                $new_value['hdd'] = $value['config_data']['hdd'] ;
                $new_value['engine'] = $value['engine'] ;
                $new_value['size_slug'] = $value['size_slug'] ;
                $new_value['vpc_enabled'] = ($value['vpc_enabled'] === true) ? 'true' : 'false' ;
                $new_value['internet_enabled'] = ($value['internet_enabled'] === true) ? 'true' : 'false' ;
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

    echo $outVar;

}

?>
