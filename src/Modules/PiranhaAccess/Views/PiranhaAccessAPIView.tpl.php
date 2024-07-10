<?php

if ($pageVars["route"]["action"]=="delete-hosted-zone") {
    if (is_object($pageVars["piranhaResult"])) {
        if ($pageVars["piranhaResult"]->status == "deleted")
        {
            echo "Requested hosted zone  {$pageVars["piranhaResult"]->requested} deleted " ;
        }
    }
    else {
        echo "No Object.";
    }
}
if ($pageVars["route"]["action"]=="delete-health-check") {
    if (is_object($pageVars["piranhaResult"])) {
        if ($pageVars["piranhaResult"]->status == "deleted")
        {
            echo "Requested Health Check {$pageVars["piranhaResult"]->requested} deleted " ;
        }
    }
    else {
        echo "No Object.";
    }
}
if ($pageVars["route"]["action"]=="create-hosted-zone") {
    if (is_object($pageVars["piranhaResult"])) {
        if ($pageVars["piranhaResult"]->status == "created")
        {
            echo "Requested  Hosted zone {$pageVars["piranhaResult"]->requested} created " ;
        }
    }
    else {
        echo "No Object.";
    }
}
if ($pageVars["route"]["action"]=="create-health-check") {
    if (is_object($pageVars["piranhaResult"])) {
        if ($pageVars["piranhaResult"]->status == "created")
        {
            echo "Requested  Health check  created " ;
        }
    }
    else {
        echo "No Object.";
    }
}
if ($pageVars["route"]["action"]=="ensure-domain-exists") {
//    echo "action: {$pageVars["route"]["action"]}\n" ;
    if (is_object($pageVars["piranhaResult"])) {
        if ($pageVars["piranhaResult"]->status == 'created') {
            echo 'Success' ;
        } else {
            echo 'Failure' ;
        }
//        foreach ($pageVars["piranhaResult"] as $key => $value) {
//            echo "$key: $value\n" ;
//        }
    } else {
        echo "Error: No Object" ;
    }
}
if ($pageVars["route"]["action"]=="ensure-record-exists") {
//    echo "action: {$pageVars["route"]["action"]}\n" ;
    if (is_object($pageVars["piranhaResult"])) {
        if ($pageVars["piranhaResult"]->status == 'created') {
            echo 'Success' ;
        } else {
            echo 'Failure' ;
        }
//        foreach ($pageVars["piranhaResult"] as $key => $value) {
//            echo "$key: $value\n" ;
//        }
    } else {
        echo "Error: No Object" ;
    }
}

?>