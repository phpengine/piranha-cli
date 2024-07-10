<?php

Namespace Info;

class PiranhaObjectStorageInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Piranha Object Storage Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PiranhaObjectStorage" => array_merge(parent::routesAvailable(), array(
          'create-bucket',  'delete-bucket',
          'upload', 'download',
          'list', 'ls'
          ) ) );
    }

    public function routeAliases() {
      return array(
          "PiranhaObjectStorage"=>"PiranhaObjectStorage", "piranha-objectstorage"=>"PiranhaObjectStorage",
          "piranhaobjectstorage"=>"PiranhaObjectStorage", "objectstorage"=>"PiranhaObjectStorage"
      );
    }


    public function helpDefinition() {
       $help = <<<"HELPDATA"
    This is an extension provided for Handling Piranha Source Control Managed Repositories.

    storage, objectstorage, piranha-objectstorage

        - create-bucket, create-bucket
        Create a Repository in Piranha ObjectStorage
        example: piranha objectstorage create-bucket -yg --name=examplebucket --description="Piranha Object Storage Bucket" 
        
        - delete-bucket, delete-bucket
        Lets you delete a bucket from Piranha Object Storage
        example: piranha objectstorage delete-bucket -yg --name=examplebucket
        
        - list
        Will display data about your Piranha Object Storage Entities
        example: piranha objectstorage list -yg --type=bucket

HELPDATA;
      return $help ;
    }

}