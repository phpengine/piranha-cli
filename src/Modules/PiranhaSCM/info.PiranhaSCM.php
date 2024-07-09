<?php

Namespace Info;

class PiranhaSCMInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Piranha SCM Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PiranhaSCM" => array_merge(parent::routesAvailable(), array(
          'create-repository',  'delete-repository',
          'create-repo', 'delete-repository',
          'list'
          ) ) );
    }

    public function routeAliases() {
      return array(
          "PiranhaSCM"=>"PiranhaSCM", "piranha-scm"=>"PiranhaSCM",
          "piranhascm"=>"PiranhaSCM", "scm"=>"PiranhaSCM"
      );
    }


    public function helpDefinition() {
       $help = <<<"HELPDATA"
    This is an extension provided for Handling Piranha Source Control Managed Repositories.

    PiranhaSCM, PiranhaSCM, piranha-scm

        - create-repository, create-repo
        Create a Repository in Piranha SCM
        example: piranha scm create-repo -yg --name=examplerepo --description="Piranha SCM Repository" 
        
        - delete-repository, delete-repo
        Lets you delete a repository from Piranha SCM
        example: piranha scm delete-repo -yg --name=examplerepo
        

        - list
        Will display data about your Piranha SCM Records
        example: piranha PiranhaSCM list -yg --list-type=records

HELPDATA;
      return $help ;
    }

}