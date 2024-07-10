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

    $outVar = "" ;
    if(array_key_exists('repositories', $pageVars["piranhaResult"]['data'])) {
        $repo_count = count($pageVars["piranhaResult"]['data']['repositories']) ;
        for ($irow = 0; $irow <= $repo_count ; $irow++) {
            unset($pageVars["piranhaResult"]['data']['repositories'][$irow]['url_git']);
            unset($pageVars["piranhaResult"]['data']['repositories'][$irow]['settings']);
            unset($pageVars["piranhaResult"]['data']['repositories'][$irow]['features']);
            unset($pageVars["piranhaResult"]['data']['repositories'][$irow]['project-owner']);
            unset($pageVars["piranhaResult"]['data']['repositories'][$irow]['project-slug']);
            unset($pageVars["piranhaResult"]['data']['repositories'][$irow]['project-name']);
            unset($pageVars["piranhaResult"]['data']['repositories'][$irow]['id']);
        }
        $outVar = getListAsCLITable($pageVars["piranhaResult"]['data']['repositories']) ;
    }
    echo $outVar;

}

?>
