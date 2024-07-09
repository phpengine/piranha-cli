<?php

Namespace Info;

class PiranhaVPCInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Piranha VPC Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PiranhaVPC" => array_merge(parent::routesAvailable(), array(
          "list",
          ) ) );
    }

    public function routeAliases() {
      return array(
          "PiranhaVPC"=>"PiranhaVPC", "piranha-vpc"=>"PiranhaVPC",
          "piranhavpc"=>"PiranhaVPC","vpc"=>"PiranhaVPC"
      );
    }

    public function boxProviderName() {
        return "PiranhaVPC";
    }

    public function helpDefinition() {
       $help = <<<"HELPDATA"
    This is an extension provided for Handling Piranha Simple Load Balancer Records.

    PiranhaVPC, PiranhaVPC, piranha-vpc

        - list
        Will display data about your Piranha VPC Records
        example: piranha PiranhaVPC list -yg --list-type=vpc,vpc-full

HELPDATA;
      return $help ;
    }

}