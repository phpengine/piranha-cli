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

    protected $fileCountTotal = 0;
    protected $fileCountComplete = 0;

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
                $p_api_vars['path'] = '' ;
                if (isset($this->params["path"])) {
                    $p_api_vars['path'] = $this->params["path"] ;
                }
                $p_api_vars['bucket_name'] = $this->params["bucket-name"] ;
                $p_api_vars['object_name'] = basename($this->params["file-name"]) ;

                if ($p_api_vars['object_name'] === '*') {
                    $original_dir = dirname($this->params["file-name"]) ;
                    $all_files = scandir($original_dir) ;
                    $all_files = array_diff($all_files, array('.', '..')) ;
                    $key_dir = '/' ;
                    foreach ($all_files as $file) {
                        $this->loopUpload($original_dir.DS.$file, $p_api_vars, $original_dir, $key_dir);
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

    protected function loopUpload($file, $p_api_vars, $original_dir='', $key_dir='') {
        if (is_dir($file)) {
//            var_dump("dir $file...");
            $source_dir = $file ;
            $all_files = scandir($source_dir) ;
            $all_files = array_diff($all_files, array('.', '..')) ;
            foreach ($all_files as $file) {
                $this->loopUpload($source_dir.DS.$file, $p_api_vars, $original_dir, $key_dir);
            }
        } else {
//            var_dump("file lup $original_dir - $key_dir - $file...") ;
            $this->singleObjectUpload($file, $p_api_vars, $original_dir, $key_dir) ;
        }
    }

    protected function singleObjectUpload($single_file, $p_api_vars, $original_dir='', $key_dir='') {

        try {

            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
//            $logging->log("Finding Bucket {$this->params["bucket-name"]}", $this->getModuleName());

            $object_name_without_original_dir =  str_replace($original_dir.DS, '', $single_file) ;
            $p_api_vars['object_name'] = $object_name_without_original_dir ;
//            var_dump("sup 1 -- " . $single_file);
//            var_dump("sup 2 -- " . $p_api_vars['object_name']);
//            var_dump($this->params["file-name"]);
//            die() ;
            $result = $this->performRequest($p_api_vars, false, null, $single_file);

            $logging->log("Creation Status is : {$result['status']}", $this->getModuleName());
            if ($result['status'] === 'OK') {
                $logging->log("Created File name is : {$result['name']} in bucket : {$result['bucket']}", $this->getModuleName());
            } else if (isset($result['error'])) {
                $logging->log("Error is : {$result['error']}", $this->getModuleName());
            }

            if (isset($this->params['verify'])) {
                $logging->log("Looking for uploaded file ".$object_name_without_original_dir, $this->getModuleName());
                $remoteFileExists = $this->doesRemoteFileExist($this->params["bucket-name"], $object_name_without_original_dir) ;
                if ($remoteFileExists === false) {
                    $logging->log("File ".$object_name_without_original_dir." in Bucket {$this->params["bucket-name"]} not found, upload failed ", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
                } else {
                    $logging->log("File ".$object_name_without_original_dir." in Bucket {$this->params["bucket-name"]} exists, upload confirmed", $this->getModuleName());
                }
            }
            
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return true ;

    }




    protected function performPiranhaObjectStorageDownloadObject($params=null){
        if ($this->askForAddExecute() != true) { return false; }
        $this->initialisePiranha() ;
        $this->getRemotePath() ;
        $this->getBucketName() ;
        $this->getFileName() ;
        $this->getFileDestination() ;

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
                $p_api_vars['object_key'] = '' ;
                if (isset($this->params['key'])) {
                    $p_api_vars['object_key'] = $this->params["key"] ;
                }
                $p_api_vars['destination'] = $this->params["destination"] ;
                $p_api_vars['object_name'] = basename($this->params["file-name"]) ;
//                $p_api_vars['object_id'] = $this->params["bucket-name"].'/'.$p_api_vars['object_key'].$this->params["file-name"] ;
                $p_api_vars['object_id'] = $p_api_vars['object_key'].$this->params["file-name"] ;

                if ($p_api_vars['object_name'] === '*') {
                    $all_files = $this->getDirectoryListByKey($p_api_vars['bucket_name'], $p_api_vars['object_key']) ;
                    $this->fileCountTotal = count($all_files) ;
                    foreach ($all_files as $file) {
                        if (strlen($p_api_vars['object_key']) > 0 &&
                            strpos($file, $p_api_vars['object_key']) === 0) {
                            $file = substr($file, strlen($p_api_vars['object_key'])) ;
                        }
//                        var_dump('$file');
//                        var_dump($file);
                        $this->loopDownload($file, $p_api_vars, $p_api_vars['destination']);
                        $this->fileCountComplete++ ;
                    }
                } else {
                    $p_api_vars['object_key'] = $p_api_vars['object_key'].$p_api_vars['object_name'] ;
                    $this->singleObjectDownload($p_api_vars['object_name'], $p_api_vars, $p_api_vars['destination'].$p_api_vars['object_name']);
                }

            }

        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return true ;

    }

    protected function loopDownload($file, $p_api_vars, $destination_dir='') {
        $p_api_vars['object_key'] = $p_api_vars['object_key'].$file ;
        $this->singleObjectDownload(basename($file), $p_api_vars, $destination_dir.$file) ;
    }

    protected function singleObjectDownload($single_file, $p_api_vars, $destination_file) {

        try {

            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
//            var_dump('$p_api_vars');
//            var_dump($p_api_vars);
//            var_dump('$destination_file');
//            var_dump($destination_file);

            $result = $this->performRequest($p_api_vars, true, $destination_file);

            $fileCountString = "" ;
            if ($this->fileCountTotal > 0) {
                $fileCountString = "$($this->fileCountComplete/$this->fileCountTotal)" ;
            }
            $logging->log("Download Status is : {$result['status']} $fileCountString", $this->getModuleName());
            if ($result['status'] === 'OK') {
                $logging->log("Downloaded File name is : {$result['name']}", $this->getModuleName());
            } else if (isset($result['error'])) {
                $logging->log("Error is : {$result['error']}", $this->getModuleName());
            }

            if (isset($this->params['verify'])) {
                $logging->log("Looking for downloaded file ".$destination_file, $this->getModuleName());
                $localFileExists = file_exists($destination_file) ;
                if ($localFileExists === false) {
                    $logging->log("File ".$destination_file." not found, download failed ", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
                } else {
                    $logging->log("File ".$destination_file." exists, download confirmed", $this->getModuleName());
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
        foreach ($list['buckets'] as $bucket) {
            if ($bucket['name'] === $this->params["bucket-name"]) {
                return true ;
            }
        }
        return false ;
    }

    protected function doesRemoteFileExist($bucket, $path) {

        $file = basename($path) ;
        $key = dirname($path) ;
//        var_dump($key);
        if ($key !== '.') {
            $p_api_vars['key'] = $key ;
        }

        $list = $this->getRecursiveBucketListing($bucket);

        if (in_array($path, $list)) {
            return true ;
        }
        return false ;
    }

    protected function getRecursiveBucketListing($bucket) {
        $p_api_vars['api_uri'] = '/api/ss3/object/all';
        $p_api_vars['page'] = 'all' ;
        $p_api_vars['bucket_name'] = $bucket ;
        $p_api_vars['key'] = '' ;

        $list = $this->performRequest($p_api_vars);

        $lines = [] ;
        foreach ($list['objects'] as $list_object) {
            if ($list_object['type'] === 'directory') {
                $lines = array_merge($lines, $this->getDirectoryListByKey($bucket, $list_object['name']) );
            } else {
                $lines[] = $list_object['name'] ;
            }
        }
//        var_dump('doesRemoteFileExist last list');
//        var_dump($lines);
        return $lines ;
    }

    protected function getDirectoryListByKey($bucket, $key) {
//        echo "getDirectoryListByKey $key\n" ;
        $p_api_vars['api_uri'] = '/api/ss3/object/all';
        $p_api_vars['page'] = 'all' ;
        $p_api_vars['bucket_name'] = $bucket ;
        $p_api_vars['key'] = $key ;

        $list = $this->performRequest($p_api_vars);

//        var_dump('getDirectoryListByKey list: '.$key);
//        var_dump($list);

        $lines = [] ;
        foreach ($list['objects'] as $list_object) {
//            echo $list_object['name']."\n" ;
            if ($list_object['type'] === 'directory') {
                $new_lines = $this->getDirectoryListByKey($bucket, $key.$list_object['name']) ;
//                foreach ($new_lines as $line) {
//                    echo $line."\n" ;
//                }
                $lines = array_merge($lines, $new_lines);
            } else {
                $lines[] = $key.$list_object['name'] ;
            }
        }

        return $lines ;
    }




    protected function performPiranhaObjectStorageDeleteObject($params=null){
        if ($this->askForDeleteExecute() != true) { return false; }
        $this->initialisePiranha();
        $this->getBucketName();
        $this->getFileName();
        $result = null ;
        try{

            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);

            $last_char_filename = substr($this->params["file-name"], strlen($this->params["file-name"])-1, 1) ;
            if ($last_char_filename !== '*') {
                $remoteFileExists = $this->doesRemoteFileExist($this->params["bucket-name"], $this->params["file-name"]) ;
                if ($remoteFileExists === false) {
                    $logging->log("File {$this->params["file-name"]} in Bucket {$this->params["bucket-name"]} Not Found", $this->getModuleName());
                    return true ;
                }
            }

            $logging->log("Deleting File/s {$this->params["file-name"]} in Bucket {$this->params["bucket-name"]} ...", $this->getModuleName());
            $p_api_vars['api_uri'] = '/api/ss3/object/delete';
            $p_api_vars['region'] = 'dc' ;
            $p_api_vars['bucket_name'] = $this->params["bucket-name"] ;
            $p_api_vars['object_id'] = $this->params["file-name"] ;

            if ($p_api_vars['object_id'] === '*') {

                $listingModel = \Model\SystemDetectionFactory::getCompatibleModel("PiranhaObjectStorage", "Listing", $this->params);

//                $base_ctl = new \Controller\Base() ;
//                $listingModel = $base_ctl->getModelAndCheckDependencies("PiranhaObjectStorage", [], "Listing") ;
                $listingModel->initialisePiranha() ;
                $all_files_array = $listingModel->getDataListFromPiranhaObjectStorage('file') ;

                $all_files = array_column($all_files_array['data']['objects'], 'key') ;

//                var_dump($all_files);
//                die() ;
//                $source_dir = dirname($this->params["file-name"]) ;
//                $all_files = scandir($source_dir) ;
//                $all_files = array_diff($all_files, array('.', '..')) ;

                foreach ($all_files as $file) {
                    $this->loopDelete($file, $p_api_vars);
                }
            } else {
                $this->singleObjectDelete($p_api_vars['object_id'], $p_api_vars) ;
            }

        } catch(\Exception $e) {
            echo $e->getMessage();
        }

        return $result;
    }



    protected function loopDelete($file, $p_api_vars) {
        if (is_dir($file)) {
            $this->loopDelete($file, $p_api_vars);
        } else {
            $this->singleObjectDelete($file, $p_api_vars) ;
        }
    }

    protected function singleObjectDelete($single_file, $p_api_vars) {

        try {

            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
//            $logging->log("Finding Bucket {$this->params["bucket-name"]}", $this->getModuleName());

            $p_api_vars['object_name'] = $single_file ;
//            $remoteFileExists = $this->doesRemoteFileExist($this->params["bucket-name"], basename($single_file)) ;
//            if ($remoteFileExists === false) {
//                $logging->log("File $single_file not found}", $this->getModuleName());
//                return false ;
//            }
//            var_dump($p_api_vars['object_name']);
//            var_dump($this->params["file-name"]);
//            die() ;
            $result = $this->performRequest($p_api_vars, false, null, null);

            $logging->log("Deletion Status is : {$result['status']}", $this->getModuleName());
            if ($result['status'] === 'OK') {
                $logging->log("Deleted File name is : {$result['name']} in bucket : {$result['bucket']}", $this->getModuleName());
            } else if (isset($result['error'])) {
                $logging->log("Error is : {$result['error']}", $this->getModuleName());
            }

            $logging->log("Looking for deleted file ".basename($single_file), $this->getModuleName());
            $remoteFileExists = $this->doesRemoteFileExist($this->params["bucket-name"], basename($single_file)) ;
            if ($remoteFileExists === false) {
                $logging->log("File ".basename($single_file)." in Bucket {$this->params["bucket-name"]} not found, deletion confirmed.", $this->getModuleName());
            } else {
                $logging->log("File ".basename($single_file)." in Bucket {$this->params["bucket-name"]} exists, deletion failed.", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
            }

        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return true ;

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
        if (isset($this->params["guess"])) {
            $this->params["destination"] = getcwd().'/' ;
            return ;
        }
        $question = 'Enter file destination: ';
        $this->params["destination"]= self::askForInput($question, true);
    }

    protected function getRemotePath()
    {
        if (isset($this->params["key"])) { return ; }
        if (isset($this->params["guess"])) {
            $this->params["key"] = '' ;
            return ;
        }
        $question = 'Enter remote path: ';
        $this->params["key"]= self::askForInput($question, true);
    }

}
