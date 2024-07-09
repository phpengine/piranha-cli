<?php

//var_dump($pageVars["route"]["action"]);
//var_dump( $pageVars["piranhaResult"]);

if ($pageVars["route"]["action"]=="create-record") {
    if (array_key_exists('record', $pageVars["piranhaResult"])) {
        echo \Model\ViewHelpers::getListAsCLITable([$pageVars["piranhaResult"]['record']]) ;
    }
}

if ($pageVars["route"]["action"]=="delete-record") {
    if (array_key_exists('record', $pageVars["piranhaResult"])) {
        $del_record = [
            'id' => $pageVars["piranhaResult"]["record_id"],
            'name' => $pageVars["piranhaResult"]["record_name"],
        ];
        echo \Model\ViewHelpers::getListAsCLITable([$del_record]) ;
    }
}

?>