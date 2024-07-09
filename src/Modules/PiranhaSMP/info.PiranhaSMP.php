<?php

Namespace Info;

class PiranhaSMPInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Piranha SMP Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PiranhaSMP" => array_merge(parent::routesAvailable(), array(
          "delete-health-check","list","create-health-check",
          'ensure-record-exists', 'ensure-record-empty',
          'create-record', 'delete-record'
          ) ) );
    }

    public function routeAliases() {
      return array(
          "PiranhaSMP"=>"PiranhaSMP", "piranha-smp"=>"PiranhaSMP",
          "piranhasmp"=>"PiranhaSMP", "smp"=>"PiranhaSMP"
      );
    }

    public function boxProviderName() {
        return "PiranhaSMP";
    }

    public function helpDefinition() {
       $help = <<<"HELPDATA"
    This is an extension provided for Handling Piranha Simple Load Balancer Records.

    PiranhaSMP, PiranhaSMP, piranha-smp

        - create-record, ensure-record-exists
        Lets you add SMP Records to Piranha SMP
        example: piranha PiranhaSMP ensure-record-exists -yg 
        
        - delete-record, ensure-record-empty
        Lets you delete SMP Records from Piranha SMP
        example: piranha PiranhaSMP ensure-record-empty -yg 
        
        - create-health-check
        Lets you add Health Check to Piranha SMP
        example: piranha PiranhaSMP create-health-check -yg 
        
         - delete-health-check
        Lets you delete Health Check to Piranha SMP
        example: piranha PiranhaSMP delete-health-check -yg 

        - list
        Will display data about your Piranha SMP Records
        example: piranha PiranhaSMP list -yg --list-type=records

HELPDATA;
      return $help ;
    }

}