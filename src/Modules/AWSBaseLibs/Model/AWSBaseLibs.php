<?php

Namespace Model;

class AWSBaseLibs extends Base {

    protected $accessKey ;
    protected $secretKey ;
    protected $region ;
    protected $endpointUrl ;
    protected $awsClient ;

    protected function initialiseAWS() {
        $this->loadLibs();
        $this->accessKey = $this->askForAWSAccessKey();
        $this->secretKey = $this->askForAWSSecretKey();
        $this->region = $this->askForAWSRegion();
        $this->endpointUrl = $this->askForAWSEndpoint() ;
        $this->getClient();
    }

    public function __construct($params) {
        parent::__construct($params);
    }

    private function loadLibs() {
        $srcFolder =  dirname(dirname(__DIR__) ) .DS.'AWSBaseLibs' ;
//        $pharFile = $srcFolder.DS."Libraries".DS."aws.phar" ;
        $pharFile = $srcFolder.DS."Libraries".DS."vendor".DS."autoload.php" ;
        require_once($pharFile) ;
    }

    protected function askForAWSAccessKey(){
        if (isset($this->params["aws-access-key"])) {
            return $this->params["aws-access-key"] ; }
        $papyrusVar = \Model\AppConfig::getProjectVariable("aws-access-key") ;
        if ($papyrusVar != null) {
            if ($this->params["guess"] == true) { return $papyrusVar ; }
            $question = 'Use Project saved AWS Access Key?';
            if (self::askYesOrNo($question, true) == true || $this->params["yes"] == true) {
                return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getProjectVariable("aws-access-key") ;
        if ($appVar != null) {
            $question = 'Use Application saved AWS Access Key?';
            if (self::askYesOrNo($question, true) == true || $this->params["yes"] == true) {
                return $appVar ; } }
        $question = 'Enter AWS Access Key';
        $key = self::askForInput($question, true);
        return $key ;
    }

    protected function askForAWSSecretKey(){
        if (isset($this->params["aws-secret-key"])) {
            return $this->params["aws-secret-key"] ; }
        $papyrusVar = \Model\AppConfig::getProjectVariable("aws-secret-key") ;
        if ($papyrusVar != null) {
            if ($this->params["guess"] == true) { return $papyrusVar ; }
            $question = 'Use Project saved AWS EC2 Client ID?';
            if (self::askYesOrNo($question, true) == true) {
                return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getProjectVariable("aws-secret-key") ;
        if ($appVar != null) {
            $question = 'Use Application saved AWS EC2 Client ID?';
            if (self::askYesOrNo($question, true) == true) {
                return $appVar ; } }
        $question = 'Enter AWS EC2 Secret Key';
        return self::askForInput($question, true);
    }

    protected function askForAWSRegion(){
        if (isset($this->params["aws-region"])) { return $this->params["aws-region"] ; }
        $papyrusVar = \Model\AppConfig::getProjectVariable("aws-region") ;
        if ($papyrusVar != null) {
            if ($this->params["guess"] == true) { return $papyrusVar ; }
            if ($this->params["use-project-region"] == true) { return $papyrusVar ; }
            $question = 'Use Project saved AWS Region?';
            if (self::askYesOrNo($question, true) == true) {
                return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getProjectVariable("aws-region") ;
        if ($appVar != null) {
            $question = 'Use Application saved AWS Region?';
            if (self::askYesOrNo($question, true) == true) {
                return $appVar ; } }
        $question = 'Enter AWS Region';
        return self::askForInput($question, true);
    }

    protected function askForAWSEndpoint(){
        if (isset($this->params["aws-endpoint"])) { return $this->params["aws-endpoint"] ; }
        $papyrusVar = \Model\AppConfig::getProjectVariable("aws-endpoint") ;
        if ($papyrusVar != null) {
            if (isset($this->params["guess"]) && $this->params["guess"] == true) {
                return $papyrusVar ; }
            if (isset($this->params["guess"]) && $this->params["use-project-endpoint"] == true) {
                return $papyrusVar ; }
            $question = 'Use Project saved AWS Endpoint?';
            if (self::askYesOrNo($question, true) == true) {
                return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getProjectVariable("aws-endpoint") ;
        if ($appVar != null) {
            $question = 'Use Application saved AWS Endpoint?';
            if (self::askYesOrNo($question, true) == true) {
                return $appVar ; } }
        if (isset($this->params["guess"]) &&
            $this->params["guess"] == true) {
            return '' ;
        }
        $question = 'Enter AWS Endpoint (empty for default)';
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
        \Model\AppConfig::setProjectVariable("aws-secret-key", $this->secretKey) ;
        \Model\AppConfig::setProjectVariable("aws-access-key", $this->accessKey) ;
        \Model\AppConfig::setProjectVariable("aws-region", $this->region) ;
    }


}