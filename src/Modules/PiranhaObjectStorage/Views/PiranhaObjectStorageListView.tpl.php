<?php


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

//    var_dump(array_keys($pageVars["piranhaResult"]['data']));

    $outVar = "" ;
    if(array_key_exists('buckets', $pageVars["piranhaResult"]['data'])) {
        $repo_count = count($pageVars["piranhaResult"]['data']['buckets']) ;
        for ($irow = 0; $irow < $repo_count ; $irow++) {
            unset($pageVars["piranhaResult"]['data']['buckets'][$irow]['ss3_bucket_id']);

            if (isset($pageVars["piranhaResult"]['data']['buckets'][$irow]['public'])) {
                $pageVars["piranhaResult"]['data']['buckets'][$irow]['public'] =
                    ($pageVars["piranhaResult"]['data']['buckets'][$irow]['public'] === true)
                        ? "true" : "false" ;
            }

            if (is_null($pageVars["piranhaResult"]['data']['buckets'][$irow]['description'])) {
                $pageVars["piranhaResult"]['data']['buckets'][$irow]['description'] = 'NULL' ;
            }

//            if (is_bool($pageVars["piranhaResult"]['data']['buckets'][$irow]['description'])) {
//                $pageVars["piranhaResult"]['data']['buckets'][$irow]['description'] =
//                    ($pageVars["piranhaResult"]['data']['buckets'][$irow]['description'] === true)
//                        ? "true" : "false" ;
//            }

        }
        $outVar = \Model\PiranhaBaseLibs::getListAsCLITable($pageVars["piranhaResult"]['data']['buckets']) ;
    }
    if(
        array_key_exists('bucket', $pageVars["piranhaResult"]['data']) &&
        array_key_exists('objects', $pageVars["piranhaResult"]['data']) ) {
        $object_count = count($pageVars["piranhaResult"]['data']['objects']) ;
        for ($irow = 0; $irow < $object_count ; $irow++) {
//        for ($irow = 0; $irow < 300 ; $irow++) {
            unset($pageVars["piranhaResult"]['data']['objects'][$irow]['size_raw']);
            unset($pageVars["piranhaResult"]['data']['objects'][$irow]['id']);
            $pageVars["piranhaResult"]['data']['objects'][$irow]['last_modified'] = trim($pageVars["piranhaResult"]['data']['objects'][$irow]['last_modified']);
//
//            if (isset($pageVars["piranhaResult"]['data']['buckets'][$irow]['public'])) {
//                $pageVars["piranhaResult"]['data']['buckets'][$irow]['public'] =
//                    ($pageVars["piranhaResult"]['data']['buckets'][$irow]['public'] === true)
//                        ? "true" : "false" ;
//            }

//            if (is_null($pageVars["piranhaResult"]['data']['objects'][$irow]['description'])) {
//                $pageVars["piranhaResult"]['data']['objects'][$irow]['description'] = 'NULL' ;
//            }

        }
//        $pageVars["piranhaResult"]['data']['objects'] = array_slice($pageVars["piranhaResult"]['data']['objects'], 100,200) ;
        $outVar = \Model\PiranhaBaseLibs::getListAsCLITable($pageVars["piranhaResult"]['data']['objects']) ;
    }
    echo $outVar;

}

?>
