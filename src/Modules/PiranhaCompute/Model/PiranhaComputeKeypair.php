<?php

Namespace Model;

class PiranhaComputeKeypair extends BasePiranhaComputeAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Keypair");

    public function askWhetherToCreateKeypair($params=null) {
        return $this->performPiranhaComputeCreateKeypair($params);
    }

    public function askWhetherToDeleteKeypair($params=null) {
        return $this->performPiranhaComputeDeleteKeypair($params);
    }


    protected function performPiranhaComputeCreateKeypair($params=null){
        if ($this->askForAddExecute() != true) { return false; }
        $this->initialisePiranha();
        $this->getKeypairName();
        $this->getKeypairKey();
        $this->getKeypairDescription();
        $unique= md5(uniqid(rand(), true));

        $result = false ;
        try {

            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Finding Keypair {$this->params["name"]}", $this->getModuleName());
//
            $keypairExists = $this->doesKeypairExistByKey() ;
            if ($keypairExists !== false) {

                $logging->log("Found Existing Keypair, named {$keypairExists["name"]} with fingerprint {$keypairExists["fingerprint"]}, creation confirmed ", $this->getModuleName());
                $result = $keypairExists ;

            } else {

                $logging->log("Keypair {$this->params["name"]} Not Found, creating...", $this->getModuleName());
                $p_api_vars['api_uri'] = '/api/sc1/keypair/create';
                $p_api_vars['region'] = 'dc' ;
                $p_api_vars['name'] = $this->params["name"] ;
                $p_api_vars['description'] = $this->params["description"] ;
                $p_api_vars['base64'] = true ;
                $p_api_vars['new_key_data'] = base64_encode($this->params["key"]) ;
                $result = $this->performRequest($p_api_vars);

                $logging->log("Creation Status is : {$result['status']}", $this->getModuleName());
                if ($result['status'] === 'OK') {
//                    var_dump($result);
                    $logging->log("Created Keypair is {$result['keypair']['name']} with id {$result['keypair']['id']}", $this->getModuleName());
                }
                $logging->log("Looking for created keypair {$this->params["name"]}", $this->getModuleName());
                $keypairExists = $this->doesKeypairExistByKey() ;
                if (is_array($keypairExists)) {
                    $logging->log("Found Keypair {$this->params["name"]}, id: {$keypairExists['id']} creation confirmed ", $this->getModuleName());
                } else {
                    $logging->log("Unable to find Keypair {$this->params["name"]}, creation failed ", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
                }

            }

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return $result ;

    }

    protected function doesKeypairExistByKey() {
        $p_api_vars['api_uri'] = '/api/sc1/keypair/all';
        $p_api_vars['page'] = 'all' ;
        $list = $this->performRequest($p_api_vars);
//        var_dump('doesKeypairExist list');
//        var_dump($list);
        $found = false ;
        foreach ($list['keypairs'] as $keypair) {
//        var_dump('doesKeypairExist compare');
//        var_dump($keypair['data']);
//        var_dump($this->params["key"]);
            if ($keypair['data'] === $this->params["key"]) {
                $found = [] ;
                $found['name'] = $keypair['name'] ;
                $found['id'] = $keypair['id'] ;
                $found['fingerprint'] = $keypair['fingerprint'] ;
                break ;
            }
        }
        return $found ;
    }

    protected function doesKeypairExistByID() {
        $p_api_vars['api_uri'] = '/api/sc1/keypair/all';
        $p_api_vars['page'] = 'all' ;
        $list = $this->performRequest($p_api_vars);
//        var_dump('doesKeypairExist list');
//        var_dump($list);
        $found = false ;
        foreach ($list['keypairs'] as $keypair) {
//        var_dump('doesKeypairExist compare');
//        var_dump($keypair['data']);
//        var_dump($this->params["key"]);
            if ($keypair['id'] === $this->params["id"]) {
                $found = [] ;
                $found['name'] = $keypair['name'] ;
                $found['id'] = $keypair['id'] ;
                $found['fingerprint'] = $keypair['fingerprint'] ;
                break ;
            }
        }
        return $found ;
    }



    protected function performPiranhaComputeDeleteKeypair($params=null){
        if ($this->askForDeleteExecute() != true) { return false; }
        $this->initialisePiranha();
        $this->getKeypairId();
        $unique= md5(uniqid(rand(), true));

        $result = false ;
        try {

            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);

            $keypairExists = $this->doesKeypairExistByID() ;
            if ($keypairExists === false) {
                $logging->log("Keypair with id {$this->params["id"]} Not Found", $this->getModuleName());
                $result = $keypairExists ;
            } else {

                $logging->log("Keypair with id {$this->params["id"]} Found, deleting...", $this->getModuleName());
                $p_api_vars['api_uri'] = '/api/sc1/keypair/delete';
                $p_api_vars['region'] = 'dc' ;
                $p_api_vars['id'] = $this->params["id"] ;
                $result = $this->performRequest($p_api_vars);

//                var_dump($result);

                $logging->log("Deletion Status is : {$result['status']}", $this->getModuleName());
                if ($result['status'] === 'OK') {
                    $logging->log("Deleted ID is : {$result['keypair']}", $this->getModuleName());
                }
                $logging->log("Looking for deleted keypair with id {$this->params["id"]}", $this->getModuleName());
                $keypairExists = $this->doesKeypairExistByID() ;
                if ($keypairExists === false) {
                    $logging->log("Keypair with id {$this->params["id"]} not found, deletion confirmed ", $this->getModuleName());
                } else {
                    $logging->log("Keypair with id {$this->params["id"]} exists, deletion failed ", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
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

    protected function  getKeypairName()
    {
        if (isset($this->params["name"])) { return ; }
        $question = 'Enter keypair name: ';
        $this->params["name"]= self::askForInput($question, true);
    }

    protected function  getKeypairKey()
    {
        if (isset($this->params["key"])) {
            if (file_exists($this->params["key"])) {
                $this->params["key"] = file_get_contents($this->params["key"]) ;
            }
            return ;
        }
        $question = 'Enter keypair key (or file path): ';
        $key = self::askForInput($question, true);
        if (file_exists($this->params["key"])) {
            $key = file_get_contents($this->params["key"]) ;
        }
        $this->params["key"]= $key ;
    }

    protected function  getKeypairID()
    {
        if (isset($this->params["id"])) { return ; }
        $question = 'Enter keypair id: ';
        $this->params["id"]= self::askForInput($question, true);
    }

    protected function getKeypairDescription() {
        if (isset($this->params["description"])) { return ; }
        if (isset($this->params["guess"])) {
            $this->params["description"] = "" ;
            return ;
        }
        $question = 'Enter an optional Keypair Description';
        $this->params["description"] = self::askForInput($question, true);
    }



}
