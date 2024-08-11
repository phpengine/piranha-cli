<?php

Namespace Model;

class PiranhaObjectStorageBucket extends BasePiranhaObjectStorageAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Bucket");

    public function askWhetherToCreateBucket($params=null) {
        return $this->performPiranhaObjectStorageCreateBucket($params);
    }


    public function askWhetherToDeleteBucket($params=null) {

        return $this->performPiranhaObjectStorageDeleteBucket($params);
    }



    protected function performPiranhaObjectStorageCreateBucket($params=null){
        if ($this->askForAddExecute() != true) { return false; }
        $this->initialisePiranha();
        $this->getBucketName();
        $this->getBucketDescription();
        $unique= md5(uniqid(rand(), true));

        try {

            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Finding Bucket {$this->params["bucket-name"]}", $this->getModuleName());
//
            $bucketExists = $this->doesBucketExist() ;
            if ($bucketExists !== false) {

                $logging->log("Found Existing Bucket {$this->params["bucket-name"]}", $this->getModuleName());

            } else {

                $logging->log("Bucket {$this->params["bucket-name"]} Not Found, creating...", $this->getModuleName());
                $p_api_vars['api_uri'] = '/api/ss3/bucket/create';
                $p_api_vars['region'] = 'dc' ;
                $p_api_vars['bucket_name'] = $this->params["bucket-name"] ;
                $p_api_vars['bucket_description'] = $this->params["bucket-description"] ;
                $result = $this->performRequest($p_api_vars);

//                var_dump($result);

                $logging->log("Creation Status is : {$result['status']}", $this->getModuleName());
                if ($result['status'] === 'OK') {
                    $logging->log("Created Name is : {$result['bucket']['ss3_bucket_name']}", $this->getModuleName());
                }
                $logging->log("Looking for created bucket {$this->params["bucket-name"]}", $this->getModuleName());
                $bucketExists = $this->doesBucketExist() ;
                if ($bucketExists === true) {
                    $logging->log("Found Bucket {$this->params["bucket-name"]}, creation confirmed ", $this->getModuleName());
                } else {
                    $logging->log("Unable to find Bucket {$this->params["bucket-name"]}, creation failed ", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
                }

            }

        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return true ;

    }

    protected function doesBucketExist() {
        $p_api_vars['api_uri'] = '/api/ss3/bucket/all';
        $p_api_vars['page'] = 'all' ;
        $list = $this->performRequest($p_api_vars);
//        var_dump('doesBucketExist list');
//        var_dump($list);
        $found = false ;
        foreach ($list['buckets'] as $bucket) {
            if ($bucket['name'] === $this->params["bucket-name"]) {
                return true ;
            }
        }
        return false ;
    }



    protected function performPiranhaObjectStorageDeleteBucket($params=null){
        if ($this->askForDeleteExecute() != true) { return false; }
        $this->initialisePiranha();
        $this->getBucketName();
        $unique= md5(uniqid(rand(), true));
        $result = null ;
        try{

            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);

            $bucketExists = $this->doesBucketExist() ;
            if ($bucketExists === false) {

                $logging->log("Bucket {$this->params["bucket-name"]} Not Found", $this->getModuleName());

            } else {

                $logging->log("Bucket {$this->params["bucket-name"]} Found, deleting...", $this->getModuleName());
                $p_api_vars['api_uri'] = '/api/ss3/bucket/delete';
                $p_api_vars['region'] = 'dc' ;
                $p_api_vars['bucket_name'] = $this->params["bucket-name"] ;
                $result = $this->performRequest($p_api_vars);

//                var_dump($result);

                $logging->log("Deletion Status is : {$result['status']}", $this->getModuleName());
                if ($result['status'] === 'OK') {
                    $logging->log("Deleted Name is : {$result['bucket']}", $this->getModuleName());
                }
                $logging->log("Looking for deleted bucket {$this->params["bucket-name"]}", $this->getModuleName());
                $bucketExists = $this->doesBucketExist() ;
                if ($bucketExists === false) {
                    $logging->log("Bucket {$this->params["bucket-name"]} not found, deletion confirmed ", $this->getModuleName());
                } else {
                    $logging->log("Bucket {$this->params["bucket-name"]} exists, deletion failed ", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
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

    
    protected function  getBucketName()
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

    protected function getBucketDescription() {
        if (isset($this->params["bucket-description"])) { return ; }
        if (isset($this->params["description"])) {
            $this->params["bucket-description"] = $this->params["description"] ;
            return ;
        }
        if (isset($this->params["guess"])) {
            $this->params["bucket-description"] = '' ;
            return ; }
        $question = 'Enter an optional Bucket Description';
        $this->params["bucket-description"] = self::askForInput($question, true);
    }


}
