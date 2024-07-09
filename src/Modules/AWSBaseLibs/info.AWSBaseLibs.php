<?php

Namespace Info;

class AWSBaseLibsInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "AWS Base Libraries";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "AWSBaseLibs" => array() );
    }

    public function routeAliases() {
      return array("awslibs"=>"AWSBaseLibs", "awsbaselibs"=>"AWSBaseLibs");
    }


    public function helpDefinition() {
       $help = <<<"HELPDATA"
    This is an extension provided for providing SDK Libraries to AWS Modules

    AWSBaseLibs


HELPDATA;
      return $help ;
    }

}