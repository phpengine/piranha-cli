<?php

Namespace Model;

class PiranhaSLBList extends BasePiranhaSLBAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Listing") ;

    public function askWhetherToListData($params=null) {

        return $this->performPiranhaSLBListData($params);
    }

    protected function performPiranhaSLBListData($params=null){
        if ($this->askForListExecute() != true) { return false; }
        $this->initialisePiranha();
        $dataToList = $this->askForDataTypeToList();
        return $this->getDataListFromPiranhaSLB($dataToList);
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
        $options = array("records");
        return self::askForArrayOption($question, $options, true);
    }

    public function getDataListFromPiranhaSLB($dataToList) {
        $list = [] ;
        try {
//            if(in_array($dataToList, array("Health-check", "HealthChecks", 'healthcheck', 'healthchecks'))) {
////                $list = $this->piranhaClient->listHealthChecks();
//            }
            if(in_array($dataToList, array('records', 'Records'))) {
                $p_api_vars['api_uri'] = '/api/slb/record/all';
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