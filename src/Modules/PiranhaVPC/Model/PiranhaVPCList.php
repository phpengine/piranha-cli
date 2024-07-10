<?php

Namespace Model;

class PiranhaVPCList extends BasePiranhaVPCAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Listing") ;

    public function askWhetherToListData($params=null) {
        return $this->performPiranhaVPCListData($params);
    }

    protected function performPiranhaVPCListData($params=null){
        if ($this->askForListExecute() != true) { return false; }
        $this->initialisePiranha();
        $dataToList = $this->askForDataTypeToList();
        return $this->getDataListFromPiranhaVPC($dataToList);
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
            $this->params["list-type"] = $this->params["type"] ;
            return $this->params["type"] ; }
        $options = array("vpc");
        return self::askForArrayOption($question, $options, true);
    }

    public function getDataListFromPiranhaVPC($dataToList) {
        $list = [] ;
        try {
            if(in_array($dataToList, array('vpc', 'vpcs'))) {
                $p_api_vars['api_uri'] = '/api/svpc/vpc/all';
                $p_api_vars['page'] = 'all' ;
                $list = $this->performRequest($p_api_vars);
            }
        } catch (\Exception $e) {
//            var_dump($e->getMessage()) ;
            return array('error' => $e->getMessage());
//            debug_print_backtrace() ;
        }
        return array(
            'data' => $list,
            'type' => $this->params["list-type"]
        );
    }

}