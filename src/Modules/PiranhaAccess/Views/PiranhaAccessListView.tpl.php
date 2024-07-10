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

//    var_dump($pageVars);

    $outVar = "" ;
    if(array_key_exists('users', $pageVars["piranhaResult"]['data'])) {
        $repo_count = count($pageVars["piranhaResult"]['data']['users']) ;
        for ($irow = 0; $irow <= $repo_count ; $irow++) {
            unset($pageVars["piranhaResult"]['data']['users'][$irow]['tokens']);
            if (isset($pageVars["piranhaResult"]['data']['users'][$irow]['default_admin'])) {
                $pageVars["piranhaResult"]['data']['users'][$irow]['default_admin'] =
                    ($pageVars["piranhaResult"]['data']['users'][$irow]['default_admin'] === true)
                        ? "true" : "false" ;
            }
        }
        $outVar = getListAsCLITable($pageVars["piranhaResult"]['data']['users']) ;
    }
    if(array_key_exists('groups', $pageVars["piranhaResult"]['data'])) {
        $outVar = getListAsCLITable($pageVars["piranhaResult"]['data']['groups']) ;
    }
    if(array_key_exists('policies', $pageVars["piranhaResult"]['data'])) {
        $repo_count = count($pageVars["piranhaResult"]['data']['policies']) ;
        for ($irow = 0; $irow <= $repo_count ; $irow++) {
            unset($pageVars["piranhaResult"]['data']['policies'][$irow]['tokens']);
            if (isset($pageVars["piranhaResult"]['data']['policies'][$irow]['default'])) {
                $pageVars["piranhaResult"]['data']['policies'][$irow]['default'] =
                    ($pageVars["piranhaResult"]['data']['policies'][$irow]['default'] === true)
                        ? "true" : "false" ;
            }
            $perms_ray = [] ;
            $perm_str_ray = [] ;
            if (isset($pageVars["piranhaResult"]['data']['policies'][$irow]['permissions'])) {

//                var_dump($pageVars["piranhaResult"]['data']['policies'][$irow]['permissions']);

                foreach ($pageVars["piranhaResult"]['data']['policies'][$irow]['permissions'] as $permission_key => $permission_values) {
                    $str =  "$permission_key: " ;
                    $perms_ray = [] ;
                    foreach ($permission_values as $permission_value_key => $permission_value_value) {
                        $val_string = ($permission_value_value === true) ? "true" : "false" ;
                        $perms_ray[] = "$permission_value_key:$val_string" ;
                    }
                    $perm_str_ray[] = $str . join(', ', $perms_ray) ;
                }
                $pageVars["piranhaResult"]['data']['policies'][$irow]['permissions'] = join(' :: ', $perm_str_ray) ;
            }
        }
        $outVar = getListAsCLITable($pageVars["piranhaResult"]['data']['policies']) ;
    }
    echo $outVar;

}

?>
