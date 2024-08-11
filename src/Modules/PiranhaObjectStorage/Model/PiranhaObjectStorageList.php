<?php

Namespace Model;

class PiranhaObjectStorageList extends BasePiranhaObjectStorageAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Listing") ;

    public function askWhetherToListData($params=null) {

        return $this->performPiranhaObjectStorageListData($params);
    }

    protected function performPiranhaObjectStorageListData($params=null){
        if ($this->askForListExecute() != true) { return false; }
        $this->initialisePiranha();
        $dataToList = $this->askForDataTypeToList();
        return $this->getDataListFromPiranhaObjectStorage($dataToList);
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
        $options = array('bucket', 'file', 'object');
        return self::askForArrayOption($question, $options, true);
    }

    protected function getBucketName()
    {
        if (isset($this->params["bucket-name"])) { return ; }
        if (isset($this->params["name"])) {
            $this->params["bucket-name"] = $this->params["name"] ;
            return ;
        }
        if (isset($this->params["bucket"])) {
            $this->params["bucket-name"] = $this->params["bucket"] ;
            return ;
        }
        $question = 'Enter bucket name: ';
        $this->params["bucket-name"]= self::askForInput($question, true);
    }

    public function getDataListFromPiranhaObjectStorage($dataToList) {
        $list = [] ;
        try {
            if (in_array($dataToList, array('bucket', 'buckets'))) {
                $p_api_vars['api_uri'] = '/api/ss3/bucket/all';
                $p_api_vars['page'] = 'all' ;
                $list = $this->performRequest($p_api_vars);
            }
            if (in_array($dataToList, array('file', 'files', 'object', 'objects'))) {
                $this->getBucketName() ;
                $p_api_vars['api_uri'] = '/api/ss3/object/all';
                $p_api_vars['bucket_name'] = $this->params["bucket-name"] ;
                $p_api_vars['page'] = 'all' ;
                $list = $this->performRequest($p_api_vars);
            }

//            var_dump($list);

//            if (in_array($dataToList, array('role'))) {
//                $p_api_vars['api_uri'] = '/api/sam/role/all';
//                $p_api_vars['page'] = 'all' ;
//                $list = $this->performRequest($p_api_vars);
//            }
//            if (in_array($dataToList, array('policy'))) {
//                $p_api_vars['api_uri'] = '/api/sam/policy/all';
//                $p_api_vars['page'] = 'all' ;
//                $list = $this->performRequest($p_api_vars);
//            }
        } catch (\Exception $e) {
//            var_dump($e->getMessage()) ;
            return array('error' => $e->getMessage());
//            debug_print_backtrace() ;
        }
        return array('data' => $list);
    }

}