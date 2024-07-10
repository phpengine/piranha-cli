<?php

Namespace Info;

class PiranhaAccessInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Piranha Access Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PiranhaAccess" => array_merge(parent::routesAvailable(), array(
          'create-user', 'delete-user', 'user-create', 'user-delete',
          'create-accesskey', 'delete-accesskey', 'accesskey-create', 'accesskey-delete',
          'create-group', 'delete-group', 'group-create', 'group-delete',
          'create-role', 'delete-role', 'role-create', 'role-delete',
          'create-policy', 'delete-policy', 'policy-create', 'policy-delete',
          'list'
          ) ) );
    }

    public function routeAliases() {
      return array(
          "PiranhaAccess"=>"PiranhaAccess", "piranha-access"=>"PiranhaAccess",
          "piranhaaccess"=>"PiranhaAccess", "access"=>"PiranhaAccess"
      );
    }


    public function helpDefinition() {
       $help = <<<"HELPDATA"
    This is an extension provided for Handling Piranha Source Control Managed Repositories.

    access, PiranhaAccess, piranha-access

        - create-accesskey, accesskey-create
        Create an accesskey Piranha Access
        example: piranha access create-accesskey -yg --name=examplekey 
        
        - delete-accesskey, accesskey-delete
        Lets you delete an accesskey from Piranha Access
        example: piranha access delete-accesskey -yg --name=examplekey
        

        - list
        Will display data about your Piranha Access Records
        example: piranha access list -yg --type=user
        example: piranha access list -yg --type=group
        example: piranha access list -yg --type=policy
        example: piranha access list -yg --type=

HELPDATA;
      return $help ;
    }

}