<?php

Namespace Model;

class PiranhaSCMRepository extends BasePiranhaSCMAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Repository");

    public function askWhetherToCreateRepository($params=null) {
        return $this->performPiranhaSCMCreateRepository($params);
    }


    public function askWhetherToDeleteRepository($params=null) {

        return $this->performPiranhaSCMDeleteRepository($params);
    }



    protected function performPiranhaSCMCreateRepository($params=null){
        if ($this->askForAddExecute() != true) { return false; }
        $this->initialisePiranha();
        $this->getRepositoryName();
        $this->getRepositoryDescription();
        $unique= md5(uniqid(rand(), true));

        try {

            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Finding Repository {$this->params["repository-name"]}", $this->getModuleName());
//
            $repositoryExists = $this->doesRepositoryExist() ;
            if ($repositoryExists !== false) {

                $logging->log("Found Existing Repository {$this->params["repository-name"]}", $this->getModuleName());

            } else {

                $logging->log("Repository {$this->params["repository-name"]} Not Found, creating...", $this->getModuleName());
                $p_api_vars['api_uri'] = '/api/scm/repository/create';
                $p_api_vars['region'] = 'dc' ;
                $p_api_vars['repository_name'] = $this->params["repository-name"] ;
                $p_api_vars['repository_description'] = $this->params["repository-description"] ;
                $result = $this->performRequest($p_api_vars);

//                var_dump($result);

                $logging->log("Creation Status is : {$result['status']}", $this->getModuleName());
                if ($result['status'] === 'OK') {
                    $logging->log("Created Name is : {$result['repo']['name']}", $this->getModuleName());
                }
                $logging->log("Looking for created repository {$this->params["repository-name"]}", $this->getModuleName());
                $repositoryExists = $this->doesRepositoryExist() ;
                if ($repositoryExists === true) {
                    $logging->log("Found Repository {$this->params["repository-name"]}, creation confirmed ", $this->getModuleName());
                } else {
                    $logging->log("Unable to find Repository {$this->params["repository-name"]}, creation failed ", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
                }

            }

        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return $result ;

    }

    protected function doesRepositoryExist() {
        $p_api_vars['api_uri'] = '/api/scm/repository/all';
        $p_api_vars['page'] = 'all' ;
        $list = $this->performRequest($p_api_vars);
//        var_dump('doesRepositoryExist list');
//        var_dump($list);
        $found = false ;
        foreach ($list['repositories'] as $repository) {
            if ($repository['name'] === $this->params["repository-name"]) {
                return true ;
            }
        }
        return false ;
    }



    protected function performPiranhaSCMDeleteRepository($params=null){
        if ($this->askForDeleteExecute() != true) { return false; }
        $this->initialisePiranha();
        $this->getRepositoryName();
        $unique= md5(uniqid(rand(), true));
        $result = null ;
        try{

            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);

            $repositoryExists = $this->doesRepositoryExist() ;
            if ($repositoryExists === false) {

                $logging->log("Repository {$this->params["repository-name"]} Not Found", $this->getModuleName());

            } else {

                $logging->log("Repository {$this->params["repository-name"]} Found, deleting...", $this->getModuleName());
                $p_api_vars['api_uri'] = '/api/scm/repository/delete';
                $p_api_vars['region'] = 'dc' ;
                $p_api_vars['repository_id'] = $this->params["repository-name"] ;
                $result = $this->performRequest($p_api_vars);

//                var_dump($result);

                $logging->log("Deletion Status is : {$result['status']}", $this->getModuleName());
                if ($result['status'] === 'OK') {
                    $logging->log("Deleted Name is : {$result['repository']}", $this->getModuleName());
                }
                $logging->log("Looking for deleted repository {$this->params["repository-name"]}", $this->getModuleName());
                $repositoryExists = $this->doesRepositoryExist() ;
                if ($repositoryExists === false) {
                    $logging->log("Repository {$this->params["repository-name"]} not found, deletion confirmed ", $this->getModuleName());
                } else {
                    $logging->log("Repository {$this->params["repository-name"]} exists, deletion failed ", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
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

    
    protected function  getRepositoryName()
    {
        if (isset($this->params["repository-name"])) { return ; }
        if (isset($this->params["name"])) {
            $this->params["repository-name"] = $this->params["name"] ;
            return ;
        }
        $question = 'Enter repository name: ';
        $this->params["repository-name"]= self::askForInput($question, true);
    }

    protected function getRepositoryDescription() {
        if (isset($this->params["repository-description"])) { return ; }
        if (isset($this->params["description"])) {
            $this->params["repository-description"] = $this->params["description"] ;
            return ;
        }
        if (isset($this->params["guess"])) {
            $this->params["repository-description"] = '' ;
            return ; }
        $question = 'Enter an optional Repository Description';
        $this->params["repository-description"] = self::askForInput($question, true);
    }


}
