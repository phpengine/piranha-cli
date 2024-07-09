<?php

Namespace Model;

class PiranhaDNSList extends BasePiranhaDNSAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Listing") ;

    public function askWhetherToListData($params=null) {

        return $this->performPiranhaDNSListData($params);
    }

    protected function performPiranhaDNSListData($params=null){
        if ($this->askForListExecute() != true) { return false; }
        $this->initialisePiranha();
        $dataToList = $this->askForDataTypeToList();
        return $this->getDataListFromPiranhaDNS($dataToList);
    }

    protected function askForListExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'List Data?';
        return self::askYesOrNo($question);
    }

    protected function askForDataTypeToList(){
        $question = 'Please choose a data type to list:';
        if (isset($this->params["list-type"])) {
            return $this->params["list-type"] ; }
        else if (isset($this->params["type"])) {
            return $this->params["type"] ; }
        $options = array("Domain","Hosted-Zone","HealthChecks","ResourceRecordSets");
        return self::askForArrayOption($question, $options, true);
    }

    protected function  getHostedZoneId() {
        if (isset($this->params["zone-id"])) {
            $this->params["hosted-zone-id"] = $this->params["zone-id"] ; }
        if (isset($this->params["zone"])) {
            $this->params["hosted-zone-id"] = $this->params["zone"] ; }
        if (isset($this->params["zoneid"])) {
            $this->params["hosted-zone-id"] = $this->params["zone-id"] ; }
        if (isset($this->params["hosted-zone-id"])) { return ; }
        $question = 'Enter Hosted zone Id:';
        $this->params["hosted-zone-id"] = self::askForInput($question, true);
    }

    public function getDataListFromPiranhaDNS($dataToList) {
        $list = [] ;
        try {
            if(in_array($dataToList, array("Hosted-Zone", 'Domain', 'domain', 'Domains', 'domains','hosted-zones', 'zones'))) {
                $p_api_vars['api_uri'] = '/api/dns/domain/all';
                $p_api_vars['page'] = 'all' ;
                $list = $this->performRequest($p_api_vars);
            }
            if(in_array($dataToList, array("Health-check", "HealthChecks", 'healthcheck', 'healthchecks'))) {
//                $list = $this->piranhaClient->listHealthChecks();
            }
            if(in_array($dataToList, array("ResourceRecordSets", 'records', 'Records'))) {
                $this->getHostedZoneId();
                $list = $this->piranhaClient->listResourceRecordSets(array('HostedZoneId' =>   $this->params["hosted-zone-id"]));

                $p_api_vars['api_uri'] = '/api/dns/domain/all';
                $p_api_vars['page'] = 'all' ;
                $list = $this->performRequest($p_api_vars);
            }
        } catch (\Exception $e) {
//            var_dump($e->getMessage()) ;
            return array('error' => $e->getMessage());
//            debug_print_backtrace() ;
        }
        return array('data' => $list);
    }

}