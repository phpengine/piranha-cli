<?php

Namespace Model;

class PiranhaDatabaseList extends BasePiranhaDatabaseAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Listing") ;

    public function askWhetherToListData($params=null) {

        return $this->performPiranhaDatabaseListData($params);
    }

    protected function performPiranhaDatabaseListData($params=null){
        if ($this->askForListExecute() != true) { return false; }
        $this->initialisePiranha();
        $dataToList = $this->askForDataTypeToList();
        return $this->getDataListFromPiranhaDatabase($dataToList);
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
        $options = array("instance", 'keypair', 'size', 'image', "instances", 'sizes', 'images');
        return self::askForArrayOption($question, $options, true);
    }

    public function getDataListFromPiranhaDatabase($dataToList) {
        $list = [] ;
        try {
            if(in_array($dataToList, array('size', 'sizes'))) {
                $p_api_vars['api_uri'] = '/api/database/size/all';
                $p_api_vars['page'] = 'all' ;
                $list = $this->performRequest($p_api_vars);
            }
            if(in_array($dataToList, array('image', 'images'))) {
                $p_api_vars['api_uri'] = '/api/database/image/all';
//                $p_api_vars['page'] = 'all' ;
                $list = $this->performRequest($p_api_vars);
            }
            if(in_array($dataToList, array('instance', 'instances'))) {
                $p_api_vars['api_uri'] = '/api/database/instance/all';
                $p_api_vars['page'] = 'all' ;
                $list = $this->performRequest($p_api_vars);
            }
            if(in_array($dataToList, array('instance-full', 'instances-full'))) {
                $p_api_vars['api_uri'] = '/api/database/instance/all';
                $p_api_vars['page'] = 'all' ;
                $list = $this->performRequest($p_api_vars);
            }
        } catch (\Exception $e) {
//            var_dump($e->getMessage()) ;
            return array('error' => $e->getMessage());
//            debug_print_backtrace() ;
        }
        return array(
            'type' => $dataToList,
            'data' => $list
        );
    }

}