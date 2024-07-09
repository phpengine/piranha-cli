<?php

echo "Welcome to the Piranha Cloud CLI\n" ;
echo "The following commands are available \n\n" ;

$ignore_modules = [
    'AWSBaseLibs', 'Autopilot', 'AutopilotDSL', 'AutopilotYAML', 'EnvironmentConfig',
    'Index', 'Logging', 'PTConfigureRequired', 'Project', 'Requirements', 'SystemDetection',
    'VariableGroups'
] ;

foreach ($pageVars['modulesInfo'] as $moduleInfo) {
    if (!in_array($moduleInfo['command'], $ignore_modules)) {
        $comm = str_replace('Piranha', '', $moduleInfo['command']) ;
        $comm = strtolower($comm) ;
        echo $comm.' - '.$moduleInfo['name']."\n" ;
    }
}


//$all = json_encode($pageVars, true) ;
//echo $all ;