<?php

Namespace Model;

class PiranhaSCMList extends BasePiranhaSCMAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Listing") ;

    public function askWhetherToListData($params=null) {

        return $this->performPiranhaSCMListData($params);
    }

    protected function performPiranhaSCMListData($params=null){
        if ($this->askForListExecute() != true) { return false; }
        $this->initialisePiranha();
        $dataToList = $this->askForDataTypeToList();
        return $this->getDataListFromPiranhaSCM($dataToList);
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
        $options = array('repository', 'Repository', 'repo', 'Repo');
        return self::askForArrayOption($question, $options, true);
    }

    public function getDataListFromPiranhaSCM($dataToList) {
        $list = [] ;
        try {
            if(in_array($dataToList, array('repository', 'Repository', 'repo', 'Repo'))) {
                $p_api_vars['api_uri'] = '/api/scm/repository/all';
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