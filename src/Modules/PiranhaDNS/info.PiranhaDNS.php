<?php

Namespace Info;

class PiranhaDNSInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Piranha DNS Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PiranhaDNS" => array_merge(parent::routesAvailable(), array(
          "delete-health-check","delete-hosted-zone","list","create-hosted-zone","create-health-check",
          "delete-domain","create-domain",
          'ensure-record-exists', 'ensure-record-empty', 'ensure-domain-exists', 'ensure-domain-empty',
          'create-record', 'delete-record'
          ) ) );
    }

    public function routeAliases() {
      return array(
          "PiranhaDNS"=>"PiranhaDNS", "piranha-dns"=>"PiranhaDNS",
          "piranhadns"=>"PiranhaDNS", "dns"=>"PiranhaDNS"
      );
    }

    public function boxProviderName() {
        return "PiranhaDNS";
    }

    public function helpDefinition() {
       $help = <<<"HELPDATA"
    This is an extension provided for Handling Piranha DNS.

    dns, PiranhaDNS, PiranhaDNS, piranha-dns

        - create-hosted-zone, ensure-domain-exists
        Lets you add Hosted zone to Piranha DNS
        example: piranha dns create-hosted-zone -yg 
        
        - delete-hosted-zone, ensure-domain-empty
        Lets you delete Hosted zone to Piranha DNS
        example: piranha dns delete-hosted-zone -yg 

        - create-record, ensure-record-exists
        Lets you add DNS Records to Piranha DNS
        example: piranha dns ensure-record-exists -yg 
        
        - delete-record, ensure-record-empty
        Lets you delete DNS Records from Piranha DNS
        example: piranha dns ensure-record-empty -yg 
        
        - create-health-check
        Lets you add Health Check to Piranha DNS
        example: piranha dns create-health-check -yg 
        
         - delete-health-check
        Lets you delete Health Check to Piranha DNS
        example: piranha dns delete-health-check -yg 

        - list
        Will display data about your Piranha DNS Records or Domains
        example: piranha dns list
                    --yes
                    --guess

        Note: region must be one of the following...
        us-east-1, ap-northeast-1, sa-east-1, ap-southeast-1, ap-southeast-2, us-west-2, us-gov-west-1, us-west-1, cn-north-1,
        eu-west-1, eu-west-2, eu-west-3

HELPDATA;
      return $help ;
    }

}