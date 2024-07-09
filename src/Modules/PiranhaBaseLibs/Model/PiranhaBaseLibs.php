<?php

Namespace Model;

class PiranhaBaseLibs extends Base {

    protected $accessKey ;
    protected $secretKey ;
    protected $region ;
    protected $endpointUrl ;
    protected $piranhaClient ;

    protected function initialisePiranha() {
        $this->accessKey = $this->askForPiranhaAccessKey();
        $this->secretKey = $this->askForPiranhaSecretKey();
        $this->region = $this->askForPiranhaRegion();
        $this->endpointUrl = $this->askForPiranhaEndpoint() ;
    }

    public function __construct($params) {
        parent::__construct($params);
    }

    protected function askForPiranhaAccessKey(){
        if (isset($this->params["piranha-access-key"])) {
            return $this->params["piranha-access-key"] ; }
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
        $papyrusVar = \Model\AppConfig::getProjectVariable("piranha-secret-key") ;
        if ($papyrusVar != null) {
            if ($this->params["guess"] == true) { return $papyrusVar ; }
            $question = 'Use Project saved Piranha EC2 Client ID?';
            if (self::askYesOrNo($question, true) == true) {
                return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getProjectVariable("piranha-secret-key") ;
        if ($appVar != null) {
            $question = 'Use Application saved Piranha EC2 Client ID?';
            if (self::askYesOrNo($question, true) == true) {
                return $appVar ; } }
        $question = 'Enter Piranha EC2 Secret Key';
        return self::askForInput($question, true);
    }

    protected function askForPiranhaRegion(){
        if (isset($this->params["piranha-region"])) { return $this->params["piranha-region"] ; }
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

    protected function getServerGroupRegionID() {
        if (isset($this->params["region-id"])) {
            return $this->params["region-id"] ; }
        if (isset($this->params["guess"])) {
            return $this->region ; }
        $question = 'Enter Region ID for this Server Group';
        return self::askForInput($question, true);
    }

    protected function setProjVars() {
        \Model\AppConfig::setProjectVariable("piranha-secret-key", $this->secretKey) ;
        \Model\AppConfig::setProjectVariable("piranha-access-key", $this->accessKey) ;
        \Model\AppConfig::setProjectVariable("piranha-region", $this->region) ;
        \Model\AppConfig::setProjectVariable("piranha-endpoint", $this->endpointUrl) ;
    }


    public function performRequest($request_vars) {
        $server_url = (isset($this->params["piranha-endpoint"])) ? $this->params["piranha-endpoint"] : 'https://api.piranha.sh' ;

        $post_data['user'] = $this->accessKey ;
        $post_data['pass'] = $this->secretKey ;
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
        curl_setopt_array($curl, [
            CURLOPT_URL => $server_url.'/'.$request_vars['api_uri'] ,
            CURLOPT_POSTFIELDS => http_build_query($post_data),
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true
        ]);
        $curl_result = curl_exec($curl);

//        var_dump('$curl_result');
//        var_dump($curl_result);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            $logging->log("Request Failed - $error_msg",
                $this->getModuleName(),
                LOG_FAILURE_EXIT_CODE);
        }
        $find = '<title>Redirecting to http://api.piranha.sh/api/user/login</title>' ;
        if (strpos($curl_result, $find) !== false) {
            $error_msg = 'Authenticaton Error';
            $logging->log("Request Failed - $error_msg",
                $this->getModuleName(),
                LOG_FAILURE_EXIT_CODE);
            return ['status' => 'error' , 'message' => $error_msg];
        }
        $curl_result_json = json_decode($curl_result, true) ;
//        var_dump($curl_result_json);
        return $curl_result_json ;
    }


}