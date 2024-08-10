<?php

Namespace Model;

class PiranhaObjectStorageObject extends BasePiranhaObjectStorageAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Object");

    public function askWhetherToUploadObject($params=null) {
        return $this->performPiranhaObjectStorageUploadObject($params);
    }

    public function askWhetherToDownloadObject($params=null) {
        return $this->performPiranhaObjectStorageDownloadObject($params);
    }

    public function askWhetherToDeleteObject($params=null) {
        return $this->performPiranhaObjectStorageDeleteObject($params);
    }


    protected function performPiranhaObjectStorageUploadObject($params=null){
        if ($this->askForAddExecute() != true) { return false; }
        $this->initialisePiranha();
        $this->getBucketName();
        $this->getFileName();
//        $this->getFileDestination();

        try {

            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Finding Bucket {$this->params["bucket-name"]}", $this->getModuleName());
//
            $bucketExists = $this->doesBucketExist() ;

            if ($bucketExists == false) {

                $logging->log("Bucket {$this->params["bucket-name"]} does not exist", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);

            } else {

                $logging->log("Bucket {$this->params["bucket-name"]} Found, uploading {$this->params["file-name"]} to it...", $this->getModuleName());
                $p_api_vars['api_uri'] = '/api/ss3/object/create';
                $p_api_vars['region'] = 'dc' ;
                $p_api_vars['bucket_name'] = $this->params["bucket-name"] ;
                $p_api_vars['object_name'] = basename($this->params["file-name"]) ;

                if ($p_api_vars['object_name'] === '*') {
                    $source_dir = dirname($this->params["file-name"]) ;
                    $all_files = scandir($source_dir) ;
                    $all_files = array_diff($all_files, array('.', '..')) ;
                    foreach ($all_files as $file) {
                        $this->loopUpload($source_dir.DS.$file, $p_api_vars);
                    }
                } else {
                    $this->singleObjectUpload($p_api_vars['object_name'], $p_api_vars) ;
                }

            }

        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return true ;

    }

    protected function loopUpload($file, $p_api_vars) {

        if (is_dir($file)) {
            $this->loopUpload($file, $p_api_vars);
        } else {
            $this->singleObjectUpload($file, $p_api_vars) ;
        }
    }

    protected function singleObjectUpload($single_file, $p_api_vars) {

        try {

            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
//            $logging->log("Finding Bucket {$this->params["bucket-name"]}", $this->getModuleName());

            $p_api_vars['object_name'] = $single_file ;
//            var_dump($p_api_vars['object_name']);
//            var_dump($this->params["file-name"]);
//            die() ;
            $result = $this->performRequest($p_api_vars, false, null, $single_file);

            $logging->log("Creation Status is : {$result['status']}", $this->getModuleName());
            if ($result['status'] === 'OK') {
                $logging->log("Created File name is : {$result['name']} in bucket : {$result['bucket']}", $this->getModuleName());
            } else if (isset($result['error'])) {
                $logging->log("Error is : {$result['error']}", $this->getModuleName());
            }

            $logging->log("Looking for uploaded file ".basename($single_file), $this->getModuleName());
            $remoteFileExists = $this->doesRemoteFileExist($this->params["bucket-name"], basename($single_file)) ;
            if ($remoteFileExists === false) {
                $logging->log("File ".basename($single_file)." in Bucket {$this->params["bucket-name"]} not found, upload failed ", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
            } else {
                $logging->log("File ".basename($single_file)." in Bucket {$this->params["bucket-name"]} exists, upload confirmed", $this->getModuleName());
            }

        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return true ;

    }




    protected function performPiranhaObjectStorageDownloadObject($params=null){
        if ($this->askForAddExecute() != true) { return false; }
        $this->initialisePiranha();
        $this->getBucketName();
        $this->getFileName();
        $this->getFileDestination();
        $unique= md5(uniqid(rand(), true));

        try {

            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Finding Bucket {$this->params["bucket-name"]}", $this->getModuleName());
//
            $bucketExists = $this->doesBucketExist() ;
            if ($bucketExists === false) {

                $logging->log("Bucket {$this->params["bucket-name"]} does not exist", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);

            } else {

                $p_api_vars['api_uri'] = '/api/ss3/object/download';
                $p_api_vars['region'] = 'dc' ;
                $p_api_vars['bucket_name'] = $this->params["bucket-name"] ;
                $p_api_vars['object_key'] = $this->params["file-name"] ;
                $p_api_vars['object_id'] = $this->params["bucket-name"].'/'.$this->params["file-name"] ;
                $logging->log("Downloading {$p_api_vars['object_id']}...", $this->getModuleName());
                $this->performRequest($p_api_vars, true, $this->params["destination"]);

//                var_dump($result);

                $logging->log("Looking for downloaded file {$this->params["destination"]}", $this->getModuleName());
                $fileExists = file_exists($this->params["destination"]) ;
                if ($fileExists === true) {
                    $logging->log("Found File {$this->params["bucket-name"]}, creation confirmed ", $this->getModuleName());
                } else {
                    $logging->log("Unable to find File {$this->params["bucket-name"]}, creation failed ", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
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

    protected function doesRemoteFileExist($bucket, $path) {
        $p_api_vars['api_uri'] = '/api/ss3/object/all';
        $p_api_vars['page'] = 'all' ;
        $p_api_vars['bucket_name'] = $bucket ;
        $p_api_vars['key'] = '' ;
        $p_api_vars['page'] = 'all' ;

        $file = basename($path) ;
        $key = dirname($path) ;
//        var_dump($key);
        if ($key !== '.') {
            $p_api_vars['key'] = $key ;
        }
        $list = $this->performRequest($p_api_vars);
//        var_dump('doesBucketExist list');
//        var_dump($list);
        $found = false ;
        foreach ($list['objects'] as $object) {
            if ($object['name'] === $file) {
                return true ;
            }
        }
        return false ;
    }




    protected function performPiranhaObjectStorageDeleteObject($params=null){
        if ($this->askForDeleteExecute() != true) { return false; }
        $this->initialisePiranha();
        $this->getBucketName();
        $this->getFileName();
        $unique= md5(uniqid(rand(), true));
        $result = null ;
        try{

            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);

            $remoteFileExists = $this->doesRemoteFileExist($this->params["bucket-name"], $this->params["file-name"]) ;
            if ($remoteFileExists === false) {

                $logging->log("File {$this->params["file-name"]} in Bucket {$this->params["bucket-name"]} Not Found", $this->getModuleName());

            } else {

                $logging->log("File {$this->params["file-name"]} in Bucket {$this->params["bucket-name"]} Found, deleting ...", $this->getModuleName());
                $p_api_vars['api_uri'] = '/api/ss3/object/delete';
                $p_api_vars['region'] = 'dc' ;
                $p_api_vars['bucket_name'] = $this->params["bucket-name"] ;
                $p_api_vars['object_id'] = $this->params["file-name"] ;
                $result = $this->performRequest($p_api_vars);

//                var_dump($result);

                $logging->log("Deletion Status is : {$result['status']}", $this->getModuleName());
                if ($result['status'] === 'OK') {
                    $logging->log("Deleted Name is : {$result['object']}", $this->getModuleName());
                }
                $logging->log("Looking for deleted file {$this->params["file-name"]}", $this->getModuleName());
                $remoteFileExists = $this->doesRemoteFileExist($this->params["bucket-name"], $this->params["file-name"]) ;
                if ($remoteFileExists === false) {
                    $logging->log("File {$this->params["file-name"]} in Bucket {$this->params["bucket-name"]}not found, deletion confirmed ", $this->getModuleName());
                } else {
                    $logging->log("File {$this->params["file-name"]} in Bucket {$this->params["bucket-name"]} exists, deletion failed ", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
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

    
    protected function getBucketName()
    {
        if (isset($this->params["bucket-name"])) { return ; }
        if (isset($this->params["bucket"])) {
            $this->params["bucket-name"] = $this->params["bucket"] ;
            return ;
        }
        $question = 'Enter bucket name: ';
        $this->params["bucket-name"] = self::askForInput($question, true);
    }

    protected function getFileName()
    {
        if (isset($this->params["file-name"])) { return ; }
        if (isset($this->params["file"])) {
            $this->params["file-name"] = $this->params["file"] ;
            return ;
        }
        $question = 'Enter file name: ';
        $this->params["file-name"]= self::askForInput($question, true);
    }

    protected function  getFileDestination()
    {
        if (isset($this->params["destination"])) { return ; }
        if (isset($this->params["dest"])) {
            $this->params["destination"] = $this->params["dest"] ;
            return ;
        }
        if (isset($this->params["path"])) {
            $this->params["destination"] = $this->params["path"] ;
            return ;
        }
        $question = 'Enter file destination: ';
        $this->params["destination"]= self::askForInput($question, true);
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
