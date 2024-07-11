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
          "create-object", "object-create", "upload",
          "delete-object", "object-delete", "delete",
          "download-object", "object-download", "download",
          'create-bucket', 'delete-bucket',
          'bucket-create', 'bucket-delete',
          'upload', 'download',
          'list', 'ls'
      ) ) );
    }

    public function routeAliases() {
      return array(
          "PiranhaObjectStorage"=>"PiranhaObjectStorage", "piranha-objectstorage"=>"PiranhaObjectStorage",
          "piranhaobjectstorage"=>"PiranhaObjectStorage", "objectstorage"=>"PiranhaObjectStorage",
          "object"=>"PiranhaObjectStorage", "objects"=>"PiranhaObjectStorage", "s3"=>"PiranhaObjectStorage"
      );
    }


    public function helpDefinition() {
       $help = <<<"HELPDATA"
    This is an extension provided for Handling Piranha Object Storage

    s3, object, objects,  objectstorage, piranha-objectstorage

        - create-bucket, bucket-create
        Create a Repository in Piranha ObjectStorage
        example: piranha objectstorage create-bucket -yg --name=examplebucket --description="Piranha Object Storage Bucket" 
        
        - delete-bucket, bucket-delete
        Lets you delete a bucket from Piranha Object Storage
        example: piranha objectstorage delete-bucket -yg --name=examplebucket

        - create-object, object-create, upload
        Create a Repository in Piranha ObjectStorage
        example: piranha objectstorage create-object -yg --name=examplebucket --description="Piranha Object Storage Bucket" 
        
        - delete-object, object-delete
        Lets you delete an object from Piranha Object Storage
        example: piranha objectstorage object-delete -yg --bucket-name=examplebucket --file=example.png
        
        - download-object, object-download, download
        Lets you download from a Piranha Object Storage Bucket
        example: piranha objectstorage download -yg --bucket=examplebucket --path=example.png --destination=/tmp/example.png
                
        - list
        Will display data about your Piranha Object Storage Entities
        example: piranha objectstorage list -yg --type=bucket

HELPDATA;
      return $help ;
    }

}