<?php

// var_dump($pageVars) ;

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
    if (is_object($pageVars["piranhaResult"])) {
        if ($pageVars["piranhaResult"]->status == "created")
        {
            echo json_encode($pageVars["piranhaResult"], JSON_PRETTY_PRINT) ;
        }
    }
    else {
        echo json_encode(['error' => "No Object."], JSON_PRETTY_PRINT);
    }
}
if ($pageVars["route"]["action"]=="ensure-record-exists") {
    if (is_object($pageVars["piranhaResult"])) {
        if ($pageVars["piranhaResult"]->status == "created")
        {
            echo json_encode($pageVars["piranhaResult"], JSON_PRETTY_PRINT) ;
        }
    }
    else {
        echo json_encode(['error' => "No Object."], JSON_PRETTY_PRINT);
    }
}

?>