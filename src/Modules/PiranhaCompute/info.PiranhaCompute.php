<?php

Namespace Info;

class PiranhaComputeInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Piranha Compute Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PiranhaCompute" => array_merge(parent::routesAvailable(), array(
          "list",
          "delete-health-check","create-health-check",
//          'ensure-instance-exists', 'ensure-record-empty',
          'ensure-instance-exists', 'create-instance', 'instance-create',
          'ensure-instance-empty', 'delete-instance','instance-delete',
          'ensure-keypair-exists', 'create-keypair', 'keypair-create',
          'ensure-keypair-empty', 'delete-keypair','keypair-delete',
          ) ) );
    }

    public function routeAliases() {
      return array(
          "PiranhaCompute"=>"PiranhaCompute", "piranha-compute"=>"PiranhaCompute",
          "piranhacompute"=>"PiranhaCompute", "compute"=>"PiranhaCompute"
      );
    }

    public function boxProviderName() {
        return "PiranhaCompute";
    }

    public function helpDefinition() {
       $help = <<<"HELPDATA"
    This is an extension provided for Handling Piranha Simple Load Balancer Records.

    compute, PiranhaCompute, PiranhaCompute, piranha-compute

        - create-instance, ensure-instance-exists
        Lets you create Compute Instances in Piranha
        example: piranha compute create-instance -yg 
            --keypair="abc12345" # keypair id
        
        - delete-instance
        Lets you delete Compute Instances from Piranha
        example: piranha compute delete-instance -yg 

        - list
        Will display data about your Piranha Compute Instances
        example: piranha compute list -yg --list-type=instances # List Readable Instance Data
        example: piranha compute list -yg --list-type=instances-full # List Complete Instance Data
        example: piranha compute list -yg --list-type=image # List available VM Images
        example: piranha compute list -yg --list-type=size # List available VM Sizes
        example: piranha compute list -yg --list-type=keypairs # List available VM Keypairs
        

HELPDATA;
      return $help ;
    }

}