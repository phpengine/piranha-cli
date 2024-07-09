<?php

Namespace Model;

class PiranhaVPCRecord extends BasePiranhaVPCAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Record");

    public function askWhetherToCreateRecord($params=null) {
        return $this->performPiranhaSLBCreateRecord($params);
    }

    public function askWhetherToDeleteRecord($params=null) {
        return $this->performPiranhaSLBDeleteRecord($params);
    }

    protected function performPiranhaSLBCreateRecord($params=null){

        if ($this->askForRecordAddDeleteExecute() != true) {
            return false; }
        $this->initialisePiranha();

        $this->getInstanceID() ;
        $this->getRecordName() ;
        $this->getIncludeHTTP() ;
        $this->getIncludeHTTPS() ;
        $this->getTargetHTTPPort() ;
        $this->getTargetHTTPSPort() ;
        $this->getUseProxyProtocol() ;
        $this->getAlternateHostnames() ;

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Performing Record Ensure", $this->getModuleName());
        $unique= md5(uniqid(rand(), true));

        $p_api_vars['api_uri'] = '/api/slb/record/create';
        $p_api_vars['region'] = 'dc' ;
        $p_api_vars['identifier'] = '' ;

        $p_api_vars['instance_id'] = $this->params["instance-id"] ;
        $p_api_vars['friendly_name'] = $this->params["record-name"] ;
        $p_api_vars['include_http'] = $this->params["include-http"] ;
        $p_api_vars['include_https'] = $this->params["include-https"] ;
        $p_api_vars['target_http_port'] = $this->params["target-http-port"] ;
        $p_api_vars['target_https_port'] = $this->params["target-https-port"] ;
        $p_api_vars['use_proxy_protocol'] = $this->params["use-proxy-protocol"] ;
        $p_api_vars['alternate_hostnames'] = $this->params["alternate-hostnames"] ;

        $result = $this->performRequest($p_api_vars);

//        echo "Before Result\n\n" ;
//
//        var_dump($result);
//
//        echo "After Result\n\n" ;

        return $result;

    }


    protected function performPiranhaSLBDeleteRecord($params=null){
        if ($this->askForRecordAddDeleteExecute() != true) {
            return false; }
        $this->initialisePiranha();
        $this->getRecordID() ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Performing Record Deletion", $this->getModuleName());

        $p_api_vars['api_uri'] = '/api/slb/record/delete';
        $p_api_vars['id'] = $this->params["record-id"] ;
        $result = $this->performRequest($p_api_vars);

//        echo "Before Result\n\n" ;
//
//        var_dump($result);
//
//        echo "After Result\n\n" ;

        return $result;

    }




    protected function askForRecordAddDeleteExecute() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Ensure Piranha SMP Record?';
        return self::askYesOrNo($question);
    }

    protected function getInstanceID() {
        if (isset($this->params["instance-id"])) { return ; }
        $question = 'Enter Instance ID: ';
        $this->params["instance-id"]= self::askForInput($question, true);
    }

    protected function getRecordID() {
        if (isset($this->params["record-id"])) { return ; }
        $question = 'Enter Record ID';
        $this->params["record-id"] = self::askForInput($question, true);
    }

    protected function getRecordName() {
        if (isset($this->params["record-name"])) { return ; }
        $question = 'Enter Record Name';
        $this->params["record-name"] = self::askForInput($question, true);
    }

    protected function getIncludeHTTP() {
        if (isset($this->params["include-http"])) { return ; }
        $question = 'Include HTTP Target';
        $this->params["include-http"] = self::askForInput($question, true);
    }

    protected function getIncludeHTTPS() {
        if (isset($this->params["include-https"])) { return ; }
        $question = 'Include HTTPS Target';
        $this->params["include-https"] = self::askForInput($question, true);
    }

    protected function getTargetHTTPPort() {
        if (isset($this->params["target-http-port"])) { return ; }
        $question = 'Enter Target HTTP Port';
        $this->params["target-http-port"] = self::askForInput($question, true);
    }

    protected function getTargetHTTPSPort() {
        if (isset($this->params["target-https-port"])) { return ; }
        $question = 'Enter Target HTTPS Port';
        $this->params["target-https-port"] = self::askForInput($question, true);
    }

    protected function getUseProxyProtocol() {
        if (isset($this->params["use-proxy-protocol"])) { return ; }
        $question = 'Use Proxy Protocol';
        $this->params["use-proxy-protocol"] = self::askForInput($question, true);
    }

    protected function getAlternateHostnames() {
        if (isset($this->params["alternate-hostnames"])) { return ; }
        $question = 'Enter Alternate Hostnames';
        $this->params["alternate-hostnames"] = self::askForInput($question, true);
    }

}
