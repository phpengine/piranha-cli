<?php

Namespace Model;

class PiranhaAccessAccessKey extends BasePiranhaAccessAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("AccessKey");

    public function askWhetherToCreateAccessKey($params=null) {
        return $this->performPiranhaAccessCreateAccessKey($params);
    }


    public function askWhetherToDeleteAccessKey($params=null) {

        return $this->performPiranhaAccessDeleteAccessKey($params);
    }



    protected function performPiranhaAccessCreateAccessKey($params=null){
        if ($this->askForAddExecute() != true) { return false; }
        $this->initialisePiranha();
        $this->getAccessKeyName();
        $this->getAccessKeyDescription();
        $this->getUserID();
        $unique= md5(uniqid(rand(), true));

        try {

            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Finding Access Key {$this->params["name"]}", $this->getModuleName());
//
            $keysetExists = $this->doesAccessKeyExist() ;
            if ($keysetExists !== false) {

                $logging->log("Found Existing Access Key {$this->params["name"]}", $this->getModuleName());

            } else {

                $logging->log("Access Key {$this->params["name"]} Not Found, creating...", $this->getModuleName());
                $p_api_vars['api_uri'] = '/api/sam/user/keyset/create';
                $p_api_vars['region'] = 'dc' ;
                $p_api_vars['name'] = $this->params["name"] ;
                $p_api_vars['user_id'] = $this->params["user-id"] ;
                $result = $this->performRequest($p_api_vars);

//                var_dump($result);

                $logging->log("Creation Status is : {$result['status']}", $this->getModuleName());
                if ($result['status'] === 'OK') {
                    $logging->log("Created Name is : {$result['name']}", $this->getModuleName());
                }
                $logging->log("Looking for created keyset {$this->params["name"]}", $this->getModuleName());
                $keysetExists = $this->doesAccessKeyExist() ;
                if ($keysetExists === true) {
                    $logging->log("Found Access Key {$this->params["name"]}, creation confirmed ", $this->getModuleName());
                } else {
                    $logging->log("Unable to find Access Key {$this->params["name"]}, creation failed ", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
                }

            }

        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return true ;

    }

    protected function doesAccessKeyExist() {
        $p_api_vars['api_uri'] = '/api/sam/user/account';
        $p_api_vars['page'] = 'all' ;
        $p_api_vars['id'] = $this->params["user-id"] ;
        $p_api_vars['keysets'] = "true" ;
        $list = $this->performRequest($p_api_vars);
//        var_dump('doesAccessKeyExist list');
//        var_dump($list);
        foreach ($list['keysets'] as $keyset) {
            if ($keyset['name'] === $this->params["name"]) {
                return true ;
            }
        }
        return false ;
    }

    protected function doesAccessKeyExistByID() {
        $p_api_vars['api_uri'] = '/api/sam/user/account';
        $p_api_vars['page'] = 'all' ;
        $p_api_vars['id'] = $this->params["user-id"] ;
        $p_api_vars['keysets'] = "true" ;
        $list = $this->performRequest($p_api_vars);
//        var_dump('doesAccessKeyExist list');
//        var_dump($list);
        foreach ($list['keysets'] as $keyset) {
            if ($keyset['id'] === $this->params["id"]) {
                return true ;
            }
        }
        return false ;
    }



    protected function performPiranhaAccessDeleteAccessKey($params=null){
        if ($this->askForDeleteExecute() != true) { return false; }
        $this->initialisePiranha();
        $this->getAccessKeyID();
        $this->getUserID();
        $unique= md5(uniqid(rand(), true));
        $result = null ;
        try{

            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);

            $keysetExists = $this->doesAccessKeyExistByID() ;
            if ($keysetExists === false) {

                $logging->log("Access Key with id {$this->params["id"]} Not Found", $this->getModuleName());

            } else {

                $logging->log("Access Key with id {$this->params["id"]} Found, deleting...", $this->getModuleName());
                $p_api_vars['api_uri'] = '/api/sam/user/keyset/delete';
                $p_api_vars['region'] = 'dc' ;
                $p_api_vars['id'] = $this->params["id"] ;
                $p_api_vars['user_id'] = $this->params["user-id"] ;
                $result = $this->performRequest($p_api_vars);

//                var_dump($result);

                $logging->log("Deletion Status is : {$result['status']}", $this->getModuleName());
                if ($result['status'] === 'OK') {
                    $logging->log("Deleted Name is : {$result['keyset']['name']}", $this->getModuleName());
                }
                $logging->log("Looking for deleted keyset {$this->params["id"]}", $this->getModuleName());
                $keysetExists = $this->doesAccessKeyExistByID() ;
                if ($keysetExists === false) {
                    $logging->log("Access Key with id {$this->params["id"]} not found, deletion confirmed ", $this->getModuleName());
                } else {
                    $logging->log("Access Key with id {$this->params["id"]} exists, deletion failed ", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
                }

            }


        } catch(\Exception $e) {
            echo $e->getMessage();
        }

        return $result;
    }

    protected function askForAddExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Add ?';
        return self::askYesOrNo($question);
    }
    protected function askForDeleteExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Delete ?';
        return self::askYesOrNo($question);
    }

    
    protected function  getAccessKeyName()
    {
        if (isset($this->params["name"])) { return ; }
        $question = 'Enter keyset name: ';
        $this->params["name"]= self::askForInput($question, true);
    }

    protected function  getAccessKeyID()
    {
        if (isset($this->params["id"])) { return ; }
        $question = 'Enter keyset id: ';
        $this->params["id"] = self::askForInput($question, true);
    }

    protected function  getUserID()
    {
        if (isset($this->params["user-id"])) { return ; }
        $question = 'Enter User ID: ';
        $this->params["user-id"]= self::askForInput($question, true);
    }

    protected function getAccessKeyDescription() {
        if (isset($this->params["keyset-description"])) { return ; }
        if (isset($this->params["description"])) {
            $this->params["keyset-description"] = $this->params["description"] ;
            return ;
        }
        if (isset($this->params["guess"])) {
            $this->params["keyset-description"] = '' ;
            return ; }
        $question = 'Enter an optional Access Key Description';
        $this->params["keyset-description"] = self::askForInput($question, true);
    }


}
