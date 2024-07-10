<?php

Namespace Info;

class PiranhaDatabaseInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Piranha Database Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PiranhaDatabase" => array_merge(parent::routesAvailable(), array(
          "list",
          "delete-health-check","create-health-check",
//          'ensure-instance-exists', 'ensure-record-empty',
          'ensure-instance-exists', 'create-instance', 'instance-create',
          'ensure-instance-empty', 'delete-instance', 'instance-delete',
          'delete-instance'
          ) ) );
    }

    public function routeAliases() {
      return array(
          "PiranhaDatabase"=>"PiranhaDatabase", "piranha-database"=>"PiranhaDatabase",
          "piranhadatabase"=>"PiranhaDatabase", "database"=>"PiranhaDatabase"
      );
    }

    public function boxProviderName() {
        return "PiranhaDatabase";
    }

    public function helpDefinition() {
       $help = <<<"HELPDATA"
    This is an extension provided for Handling Piranha Simple Load Balancer Records.

    database, PiranhaDatabase, PiranhaDatabase, piranha-database

        - create-instance, ensure-instance-exists
        Lets you create Database Instances in Piranha
        example: piranha database create-instance -yg 
        
        - delete-instance
        Lets you delete Database Instances from Piranha
        example: piranha database delete-instance -yg 

        - list
        Will display data about your Piranha Database Instances
        example: piranha database list -yg --list-type=instances # List Human Readable Instance Data
        example: piranha database list -yg --list-type=instances-full # List Complete Instance Data
        example: piranha database list -yg --list-type=image # List available Database Images
        example: piranha database list -yg --list-type=size # List available Database Sizes
        

HELPDATA;
      return $help ;
    }

}