<?php

Namespace Model;

class PiranhaBaseLibs extends Base {

    protected $profileName = null ;
    protected $accessKey ;
    protected $secretKey ;
    protected $region ;
    protected $endpointUrl ;
    protected $piranhaClient ;

    protected function initialisePiranha() {
        $profile_ask =  $this->askForPiranhaProfile() ;
        $this->profileName = ($profile_ask === null ) ? null : $profile_ask ;
        if (!is_null($this->profileName)) {
            $this->setKeysFromProfile() ;
        }
        $this->accessKey = $this->askForPiranhaAccessKey() ;
        $this->secretKey = $this->askForPiranhaSecretKey() ;
//        $this->region = $this->askForPiranhaRegion() ;
//        $this->endpointUrl = $this->askForPiranhaEndpoint() ;
    }

    public function __construct($params) {
        parent::__construct($params);
    }

    protected function askForPiranhaProfile(){
        if (isset($this->params["piranha-profile"])) {
            return $this->params["piranha-profile"] ;
        }
        if (isset($this->params["profile"])) {
            $this->params["piranha-profile"] = $this->params["profile"] ;
            return $this->params["piranha-profile"] ;
        }
        return null ;
    }


    protected function askForPiranhaAccessKey(){
        if (isset($this->params["piranha-access-key"])) {
            return $this->params["piranha-access-key"] ; }
        if (isset($this->params["access-key"])) {
            return $this->params["access-key"] ; }
        if (isset($this->params["accesskey"])) {
            return $this->params["accesskey"] ; }
        $papyrusVar = \Model\AppConfig::getProjectVariable("piranha-access-key") ;
        if ($papyrusVar != null) {
            if ($this->params["guess"] == true) { return $papyrusVar ; }
            $question = 'Use Project saved Piranha Access Key?';
            if (self::askYesOrNo($question, true) == true || $this->params["yes"] == true) {
                return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getProjectVariable("piranha-access-key") ;
        if ($appVar != null) {
            $question = 'Use Application saved Piranha Access Key?';
            if (self::askYesOrNo($question, true) == true || $this->params["yes"] == true) {
                return $appVar ; } }
        $question = 'Enter Piranha Access Key';
        $key = self::askForInput($question, true);
        return $key ;
    }

    protected function askForPiranhaSecretKey(){
        if (isset($this->params["piranha-secret-key"])) {
            return $this->params["piranha-secret-key"] ; }
        if (isset($this->params["secret-key"])) {
            return $this->params["secret-key"] ; }
        if (isset($this->params["secretkey"])) {
            return $this->params["secretkey"] ; }
        $papyrusVar = \Model\AppConfig::getProjectVariable("piranha-secret-key") ;
        if ($papyrusVar != null) {
            if ($this->params["guess"] == true) { return $papyrusVar ; }
            $question = 'Use Project saved Piranha Secret Key?';
            if (self::askYesOrNo($question, true) == true) {
                return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getProjectVariable("piranha-secret-key") ;
        if ($appVar != null) {
            $question = 'Use Application saved Piranha Secret Key?';
            if (self::askYesOrNo($question, true) == true) {
                return $appVar ; } }
        $question = 'Enter Piranha Secret Key';
        return self::askForInput($question, true);
    }

    protected function askForPiranhaRegion(){
        if (isset($this->params["piranha-region"])) { return $this->params["piranha-region"] ; }
        if (isset($this->params["region"])) {
            $this->params["piranha-region"] = $this->params["region"] ;
            return $this->params["piranha-region"] ;
        }
        $papyrusVar = \Model\AppConfig::getProjectVariable("piranha-region") ;
        if ($papyrusVar != null) {
            if ($this->params["guess"] == true) { return $papyrusVar ; }
            if ($this->params["use-project-region"] == true) { return $papyrusVar ; }
            $question = 'Use Project saved Piranha Region?';
            if (self::askYesOrNo($question, true) == true) {
                return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getProjectVariable("piranha-region") ;
        if ($appVar != null) {
            $question = 'Use Application saved Piranha Region?';
            if (self::askYesOrNo($question, true) == true) {
                return $appVar ; } }
        $question = 'Enter Piranha Region';
        return self::askForInput($question, true);
    }

    protected function askForPiranhaEndpoint(){
        if (isset($this->params["piranha-endpoint"])) { return $this->params["piranha-endpoint"] ; }
        $papyrusVar = \Model\AppConfig::getProjectVariable("piranha-endpoint") ;
        if ($papyrusVar != null) {
            if (isset($this->params["guess"]) && $this->params["guess"] == true) {
                return $papyrusVar ; }
            if (isset($this->params["guess"]) && $this->params["use-project-endpoint"] == true) {
                return $papyrusVar ; }
            $question = 'Use Project saved Piranha Endpoint?';
            if (self::askYesOrNo($question, true) == true) {
                return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getProjectVariable("piranha-endpoint") ;
        if ($appVar != null) {
            $question = 'Use Application saved Piranha Endpoint?';
            if (self::askYesOrNo($question, true) == true) {
                return $appVar ; } }
        if (isset($this->params["guess"]) &&
            $this->params["guess"] == true) {
            return '' ;
        }
        $question = 'Enter Piranha Endpoint (empty for default)';
        return self::askForInput($question, true);
    }

//    protected function getServerGroupRegionID() {
//        if (isset($this->params["region-id"])) {
//            return $this->params["region-id"] ; }
//        if (isset($this->params["guess"])) {
//            return $this->region ; }
//        $question = 'Enter Region ID for this Server Group';
//        return self::askForInput($question, true);
//    }

    protected function setProjVars() {
        \Model\AppConfig::setProjectVariable("piranha-secret-key", $this->secretKey) ;
        \Model\AppConfig::setProjectVariable("piranha-access-key", $this->accessKey) ;
        \Model\AppConfig::setProjectVariable("piranha-region", $this->region) ;
        \Model\AppConfig::setProjectVariable("piranha-endpoint", $this->endpointUrl) ;
    }

    protected function setKeysFromProfile() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $profile_paths = [
            getenv('HOME').'/.piranha/credentials',
            getenv('HOME').'/.aws/credentials'
        ] ;
        # var_dump(getenv('HOME') );
        $searched_files = [] ;
        foreach ($profile_paths as $profile_path) {
//            var_dump($this->params);
            if (file_exists($profile_path)) {
                if (array_key_exists("v", $this->params)) {
                    $logging->log("Searching for Profile {$this->profileName} in $profile_path", $this->getModuleName());
                }
                $searched_files[] = $profile_path ;
                $profiles = $this->getProfileArrayFromFile($profile_path) ;
//                var_dump($profiles);
                if (array_key_exists($this->profileName, $profiles ) ) {
                    if (array_key_exists("v", $this->params)) {
                        $logging->log("Profile $this->profileName found in $profile_path", $this->getModuleName());
                    }
                    $this->params["piranha-secret-key"] = $profiles[$this->profileName]['secret_key'] ;
                    $this->params["piranha-access-key"] = $profiles[$this->profileName]['access_key'] ;
                    return ;
                }
            } else {
                if (array_key_exists("v", $this->params)) {
                    $logging->log("No Profile file $profile_path found", $this->getModuleName());
                }
            }
        }
        $logging->log("Unable to find Profile {$this->profileName} in ".join(', ', $searched_files), $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
        exit(1) ;
    }


    protected function getProfileArrayFromFile($file_path) {
        $file_data = file_get_contents($file_path) ;
        $file_data_lines = explode("\n", $file_data);
        $profiles = [];
        for ($current_line=0; $current_line < count($file_data_lines); $current_line++) {
            if (substr($file_data_lines[$current_line], 0, 1) === '[') {
                $profile_name = substr($file_data_lines[$current_line], 1, strlen($file_data_lines[$current_line])-2); ;
                $profiles[$profile_name]['access_key'] = str_replace('aws_access_key_id=', '', $file_data_lines[$current_line+1] );
                $profiles[$profile_name]['secret_key'] = str_replace('aws_secret_access_key=', '', $file_data_lines[$current_line+2] );
            }
        }
        return $profiles ;
    }

    public function performRequest($request_vars, $save_output=false, $destination=null, $upload_file=null) {
        $server_url = (isset($this->params["piranha-endpoint"])) ? $this->params["piranha-endpoint"] : 'https://api.piranha.sh' ;
        $post_data['key_id'] = $this->accessKey ;
        $post_data['secret_key'] = $this->secretKey ;
        $post_data['page'] = 'all' ;
        $post_data = array_merge($request_vars, $post_data) ;

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        if (!function_exists('curl_init')) {
            $logging->log("No PHP Curl Available",
                $this->getModuleName(),
                LOG_FAILURE_EXIT_CODE);
            return false ;
        }

        $curl = curl_init();

        if ($save_output === true) {

            $fp = fopen ($destination, 'w+');
            curl_setopt_array($curl, [
                CURLOPT_URL => $server_url.'/'.$request_vars['api_uri'] ,
                CURLOPT_POSTFIELDS => http_build_query($post_data),
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FILE => $fp
            ]);

        } else if (is_null($upload_file)){

            curl_setopt($curl, CURLOPT_URL, $server_url.'/'.$request_vars['api_uri']);
            curl_setopt($curl, CURLOPT_POST, true );
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data)) ;


        } else if (!is_null($upload_file)) {

            $one_file['name'] = basename($upload_file) ;
            $one_file['path'] = $upload_file ;
            if (!isset($post_data['object_name'])) {
                $post_data['object_name'] = $one_file['name'] ;
            }
//            var_dump($post_data);
//            die() ;
            $noncurl_result_json = $this->do_post_request_without_curl($server_url.'/'.$request_vars['api_uri'], $post_data, $one_file) ;
            $noncurl_result_json = json_decode($noncurl_result_json, true) ;
//            var_dump($noncurl_result_json);
            return $noncurl_result_json ;
        }

        $curl_result = curl_exec($curl);

        if ($save_output === true) {
            fclose($fp) ;
        }
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            $logging->log("Request Failed - $error_msg",
                $this->getModuleName(),
                LOG_FAILURE_EXIT_CODE);
        }
        $find = '<title>Redirecting to http://api.piranha.sh/api/user/login</title>' ;
        if (strpos($curl_result, $find) !== false) {
            $error_msg = 'Authentication Error';
            $logging->log("Request Failed - $error_msg",
                $this->getModuleName(),
                LOG_FAILURE_EXIT_CODE);
            return ['status' => 'error' , 'message' => $error_msg];
        }
        $curl_result_json = json_decode($curl_result, true) ;
//        var_dump($curl_result_json);
        return $curl_result_json ;
    }


    function do_post_request_without_curl($url, $postdata, $file)
    {
        $data = "";
        $boundary = "---------------------".substr(md5(rand(0,32000)), 0, 10);

//        $postdata['key'] = 'test2/' ;
//        $postdata['path'] = 'test2/' ;
//        $postdata['object_name'] = 'wolverine.jpg' ;
//        unset($postdata['page']) ;
        //Collect Postdata
        foreach($postdata as $key => $val)
        {
            $data .= "--$boundary\n";
            $data .= "Content-Disposition: form-data; name=\"".$key."\"\n\n".$val."\n";
        }

        $data .= "--$boundary\n";

        //Collect Filedata
        $fileContents = file_get_contents($file['path']);
//        var_dump($file['name']);
//        var_dump($file['path']);
//        var_dump($fileContents);

        $data .= "Content-Disposition: form-data; name=\"file\"; filename=\"{$file['name']}\"\n";
//            $data .= "Content-Type: image/jpeg\n";
        $data .= "Content-Transfer-Encoding: binary\n\n";

        var_dump($data);

        $data .= $fileContents."\n";
        $data .= "--$boundary--\n";

        $params = array('http' => array(
            'method' => 'POST',
            'header' => 'Content-Type: multipart/form-data; boundary='.$boundary,
            'content' => $data
        ));

        $ctx = stream_context_create($params);
        $fp = fopen($url, 'rb', false, $ctx);

        if (!$fp) {
            throw new \Exception("Problem with $url, $php_errormsg");
        }

        $response = @stream_get_contents($fp);
        if ($response === false) {
            throw new \Exception("Problem reading data from $url, $php_errormsg");
        }
        return $response;
    }

    static function getListAsCLITable($list_entries) {
        $outvar = '' ;
        $max_lengths = [] ;
        // get max lengths
        foreach($list_entries as $list_entry) {
            foreach($list_entry as $key=>$value) {
                if (is_string($value)) {
                    if (isset($max_lengths[$key])) {
                        $max_lengths[$key] = (strlen($value) > $max_lengths[$key]) ? strlen($value) : $max_lengths[$key] ;
//                    $max_lengths[$key] = $max_lengths[$key] + 1;
//                    if ($key === 'name') {
//                        echo $max_lengths[$key]." - " ;
//                    }
                    } else {
                        $max_lengths[$key] = strlen($value) + 1;
                    }
                } else {
                    $max_lengths[$key] = strlen($key) + 1;
                }
            }
        }
//    var_dump($max_lengths);
        // use title length if longer
        foreach($max_lengths as $key=>$value) {
            if (strlen($key) > $value) {
                $max_lengths[$key] = strlen($key)+1 ;
            }
        }
//    var_dump($max_lengths);
        // get titles
        foreach($max_lengths as $key=>$value) {
            $outvar .= "$key".getSpaceString($value-strlen($key)) ;
        }
        $outvar .= "\n" ;
        // get
        foreach($list_entries as $list_entry) {
            foreach($list_entry as $key=>$value) {
                if (is_string($value)) {
                    $chars = $max_lengths[$key] - strlen($value) ;
                    $outvar .= "$value".getSpaceString($chars) ;
                } else {
                    if (isset($max_lengths[$key])) {
                        $chars = $max_lengths[$key] - strlen( "Array") ;
                        $outvar .= "Array".getSpaceString($chars) ;
                    } else {
                        $chars = $max_lengths[$key] - strlen( "NULL") ;
                        $outvar .= "NULL".getSpaceString($chars) ;
                    }
                }
            }
            $outvar .= "\n" ;
        }
        return $outvar ;
    }

}