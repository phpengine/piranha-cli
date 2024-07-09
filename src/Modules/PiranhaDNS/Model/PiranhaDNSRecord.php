<?php

Namespace Model;

class PiranhaDNSRecord extends BasePiranhaDNSAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Record");

    public function askWhetherToCreateRecord($params=null) {
        return $this->performPiranhaDNSCreateRecord($params);
    }

    public function askWhetherToDeleteRecord($params=null) {
        return $this->performPiranhaDNSDeleteRecord($params);
    }

    protected function performPiranhaDNSCreateRecord($params=null){

        if ($this->askForRecordAddDeleteExecute() != true) {
            return false; }
        $this->initialisePiranha();
        $this->getDomainName() ;
        $this->getRecordName() ;
        $this->getRecordType() ;
        $this->getRecordData() ;
        $this->getRecordTTL() ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Performing Record Ensure", $this->getModuleName());
        $unique= md5(uniqid(rand(), true));
        $domain_exists = $this->doesDomainExist() ;
        if ($domain_exists === false) {
            $logging->log("Domain Not Found", $this->getModuleName());
            return false ;
        }
        $p_api_vars['api_uri'] = '/api/dns/record/create';
        $p_api_vars['region'] = 'dc' ;
        $p_api_vars['identifier'] = '' ;
        $p_api_vars['domain_name'] = $this->params["domain-name"] ;
        $p_api_vars['record_name'] = $this->params["record-name"] ;
        $p_api_vars['record_value'] = $this->params["record-data"];
        $p_api_vars['record_type'] = $this->params["record-type"] ;
        $result = $this->performRequest($p_api_vars);

        echo "Before Result\n\n" ;

        var_dump($result);

        echo "After Result\n\n" ;

        return $result;

    }


    protected function performPiranhaDNSDeleteRecord($params=null){
//        if ($this->askForRecordAddDeleteExecute() != true) { return false; }
//        $this->initialisePiranha();
//        $this->getHostedZoneId();
//
//
//        try{
//        $result = $this->piranhaClient->deleteRec(
//            array(
//                'Id' => $this->params["Hosted-zone-id"]
//
//            )
//
//
//        );
//        }catch(\Aws\Route53\Exception\Route53Exception  $e)
//        {
//            echo $e->getMessage();
//            die();
//        }
//
//        $bucketResult = new \StdClass() ;
//        $bucketResult->status = "deleted" ;
//        $bucketResult->requested =  $this->params["Hosted-zone-id"];
//
//        return $bucketResult;

    }


    protected function doesDomainExist() {
        $p_api_vars['api_uri'] = '/api/dns/domain/all';
        $p_api_vars['page'] = 'all' ;
        $list = $this->performRequest($p_api_vars);
        $found = false ;
        foreach ($list['domains'] as $domain) {
            if ($domain['dns_domain_name'] === $this->params["domain-name"]) {
                $found = true ;
            }
        }
        return $found ;
    }

    protected function askForRecordAddDeleteExecute() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Ensure Piranha Records?';
        return self::askYesOrNo($question);
    }

    protected function specifyEndPointByIp() {
        if (isset($this->params["yes-ip"]) && $this->params["yes-ip"]==true) { return true ; }
        $question = 'specify ip address by Ip?';
        return self::askYesOrNo($question);
    }

    protected function  getIpAddress() {
        if (isset($this->params["ip-address"])) { return ; }
        $question = 'Enter Ip address: ';
        $this->params["ip-address"]= self::askForInput($question, true);
    }

    protected function  getDomainName() {
        if (isset($this->params["domain-name"])) { return ; }
        $question = 'Enter domain name: ';
        $this->params["domain-name"] = self::askForInput($question, true);
    }

    protected function getRecordName() {
        if (isset($this->params["record-name"])) { return ; }
        $question = 'Enter Record Name';
        $this->params["record-name"] = self::askForInput($question, true);
    }

    protected function getRecordType() {
        if (isset($this->params["record-type"])) { return ; }
        $question = 'Enter Record Type';
        $this->params["record-type"] = self::askForInput($question, true);
    }

    protected function getRecordData() {
        if (isset($this->params["record-data"])) { return ; }
        $question = 'Enter Record Data';
        $this->params["record-data"] = self::askForInput($question, true);
    }

    protected function getRecordTTL() {
        if (isset($this->params["record-ttl"])) { return ; }
        $question = 'Enter Record TTL';
        $this->params["record-ttl"] = self::askForInput($question, true);
    }

}
