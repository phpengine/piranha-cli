<?php

Namespace Info;

class PiranhaSLBInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Piranha SLB Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PiranhaSLB" => array_merge(parent::routesAvailable(), array(
          "delete-health-check","list","create-health-check",
          'ensure-record-exists', 'ensure-record-empty',
          'create-record', 'delete-record'
          ) ) );
    }

    public function routeAliases() {
      return array(
          "PiranhaSLB"=>"PiranhaSLB", "piranha-slb"=>"PiranhaSLB",
          "piranhaslb"=>"PiranhaSLB","slb"=>"PiranhaSLB"
      );
    }

    public function boxProviderName() {
        return "PiranhaSLB";
    }

    public function helpDefinition() {
       $help = <<<"HELPDATA"
    This is an extension provided for Handling Piranha Simple Load Balancer Records.

    PiranhaSLB, PiranhaSLB, piranha-slb

        - create-record, ensure-record-exists
        Lets you add SLB Records to Piranha SLB
        example: piranha PiranhaSLB ensure-record-exists -yg 
        
        - delete-record, ensure-record-empty
        Lets you delete SLB Records from Piranha SLB
        example: piranha PiranhaSLB ensure-record-empty -yg 
        
        - create-health-check
        Lets you add Health Check to Piranha SLB
        example: piranha PiranhaSLB create-health-check -yg 
        
         - delete-health-check
        Lets you delete Health Check to Piranha SLB
        example: piranha PiranhaSLB delete-health-check -yg 

        - list
        Will display data about your Piranha SLB Records
        example: piranha PiranhaSLB list -yg --list-type=records

HELPDATA;
      return $help ;
    }

}