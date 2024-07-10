<?php

Namespace Model;

class PiranhaAccessList extends BasePiranhaAccessAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Listing") ;

    public function askWhetherToListData($params=null) {

        return $this->performPiranhaAccessListData($params);
    }

    protected function performPiranhaAccessListData($params=null){
        if ($this->askForListExecute() != true) { return false; }
        $this->initialisePiranha();
        $dataToList = $this->askForDataTypeToList();
        return $this->getDataListFromPiranhaAccess($dataToList);
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
        $options = array('repository', 'AccessKey', 'repo', 'Repo');
        return self::askForArrayOption($question, $options, true);
    }

    public function getDataListFromPiranhaAccess($dataToList) {
        $list = [] ;
        try {
            if(in_array($dataToList, array('key', 'accesskey'))) {
                $p_api_vars['api_uri'] = '/api/scm/repository/all';
                $p_api_vars['page'] = 'all' ;
                $list = $this->performRequest($p_api_vars);
            }
            if(in_array($dataToList, array('user'))) {
                $p_api_vars['api_uri'] = '/api/sam/user/all';
                $p_api_vars['page'] = 'all' ;
                $list = $this->performRequest($p_api_vars);
            }
            if(in_array($dataToList, array('group'))) {
                $p_api_vars['api_uri'] = '/api/sam/group/all';
                $p_api_vars['page'] = 'all' ;
                $list = $this->performRequest($p_api_vars);
            }
            if(in_array($dataToList, array('role'))) {
                $p_api_vars['api_uri'] = '/api/sam/role/all' ;
                $p_api_vars['page'] = 'all' ;
                $list = $this->performRequest($p_api_vars);
            }
            if(in_array($dataToList, array('policy'))) {
                $p_api_vars['api_uri'] = '/api/sam/policy/all';
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