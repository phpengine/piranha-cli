<?php

Namespace Model;

class PiranhaSCMRecord extends BasePiranhaSCMAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Record");

    public function askWhetherToCreateRecord($params=null) {
        return $this->performPiranhaSMPCreateRecord($params);
    }

    public function askWhetherToDeleteRecord($params=null) {
        return $this->performPiranhaSMPDeleteRecord($params);
    }

    protected function performPiranhaSMPCreateRecord($params=null){

        if ($this->askForRecordAddDeleteExecute() != true) {
            return false; }
        $this->initialisePiranha();
        $this->getInstanceID() ;
        $this->getRecordName() ;
        $this->getTargetPort() ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Performing Record Ensure", $this->getModuleName());

        $p_api_vars['api_uri'] = '/api/smp/record/create';
        $p_api_vars['region'] = 'dc' ;
        $p_api_vars['instance_id'] = $this->params["instance-id"] ;
        $p_api_vars['friendly_name'] = $this->params["record-name"] ;
        $p_api_vars['target_proxy_port'] = $this->params["target-port"];
        $result = $this->performRequest($p_api_vars);

//        echo "Before Result\n\n" ;
//
//        var_dump($result);
//
//        echo "After Result\n\n" ;

        return $result;

    }


    protected function performPiranhaSMPDeleteRecord($params=null){
        if ($this->askForRecordAddDeleteExecute() != true) {
            return false; }
        $this->initialisePiranha();
        $this->getRecordID() ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Performing Record Deletion", $this->getModuleName());

        $p_api_vars['api_uri'] = '/api/smp/record/delete';
        $p_api_vars['id'] = $this->params["record-id"] ;
        $result = $this->performRequest($p_api_vars);

        echo "Before Result\n\n" ;

        var_dump($result);

        echo "After Result\n\n" ;

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

    protected function getTargetPort() {
        if (isset($this->params["target-port"])) { return ; }
        $question = 'Enter Target Port';
        $this->params["target-port"] = self::askForInput($question, true);
    }


}
