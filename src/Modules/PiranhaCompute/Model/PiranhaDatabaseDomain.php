<?php

Namespace Model;

class PiranhaDatabaseDomain extends BasePiranhaDatabaseAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Domain");

    public function askWhetherToCreateHostedZone($params=null) {
        return $this->performPiranhaComputeCreateHostedZone($params);
    }

    public function askWhetherToCreateHealthCheck($params=null) {

        return $this->performPiranhaComputeCreateHealthCheck($params);
    }
    public function askWhetherToDeleteHostedZone($params=null) {

        return $this->performPiranhaComputeDeleteHostedZone($params);
    }

    public function askWhetherToDeleteHealthCheck($params=null) {

        return $this->performPiranhaComputeDeleteHealthCheck($params);
    }
    protected function performPiranhaComputeCreateHealthCheck($params=null){
        if ($this->askForAddExecute() != true) { return false; }
        $this->initialisePiranha();

        $this->getProtocol();  //$this->params["check-type"]

        $this->getRequestInterval(); //$this->params["request-interval"]
        $this->getfailureThresHold(); //$this->params["failure-threshold"]
        $unique= md5(uniqid(rand(), true));

            $this->getIpAddress(); //$this->params["ip-address"]
            $this->getDomainName();

        try{
        $result = $this->piranhaClient->createHealthCheck(
                array(
                    'CallerReference'=>$unique,
                    'HealthCheckConfig'=>array(
                        'IPAddress'=>$this->params["ip-address"],
                        'Type'=>$this->params["check-type"],
                        'FullyQualifiedDomainName'=>$this->params["domain-name"],
                        'RequestInterval'=>(int)$this->params["request-interval"],
                        'FailureThreshold'=>(int)$this->params["failure-threshold"]
                    )
                )
            );
        }catch(\Aws\Route53\Exception\Route53Exception  $e)
        {
            echo $e->getMessage();
        }
        $bucketResult = new \StdClass() ;
        $bucketResult->status = "created" ;
        return $bucketResult;
    }

    protected function performPiranhaComputeCreateHostedZone($params=null){
        if ($this->askForAddExecute() != true) { return false; }
        $this->initialisePiranha();
        $this->getDomainName();
        $this->getDomainComment();
        $unique= md5(uniqid(rand(), true));

        try {

            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Finding Domain {$this->params["domain-name"]}", $this->getModuleName());
//
            $domainExists = $this->doesDomainExist() ;
            if ($domainExists !== false) {

                $logging->log("Found Domain {$this->params["domain-name"]}", $this->getModuleName());
                $logging->log("Found Zone {$this->params["domain-name"]}, creation confirmed ", $this->getModuleName());

            } else {

                $logging->log("Domain {$this->params["domain-name"]} Not Found, creating...", $this->getModuleName());
                $p_api_vars['api_uri'] = '/api/compute/domain/create';
                $p_api_vars['region'] = 'dc' ;
                $p_api_vars['domain_name'] = $this->params["domain-name"] ;
                $result = $this->performRequest($p_api_vars);

                $logging->log("Creation Status is : {$result['status']}", $this->getModuleName());
                if ($result['status'] === 'OK') {
                    $logging->log("Created ID is : {$result['id']}", $this->getModuleName());
                }
                $logging->log("Looking for created domain {$this->params["domain-name"]}", $this->getModuleName());
                $domainExists = $this->doesDomainExist() ;
                if (is_array($domainExists)) {
                    $logging->log("Found Zone {$this->params["domain-name"]}, id: {$domainExists['id']} creation confirmed ", $this->getModuleName());
                } else {
                    $logging->log("Unable to find Zone {$this->params["domain-name"]}, creation failed ", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
                }

            }

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        $route53Result = new \StdClass() ;
        $route53Result->status = "created" ;
        $route53Result->requested =  $this->params["domain-name"];
        $route53Result->domain =  $this->params["domain-name"] ;
        $route53Result->Id = $domainExists['id'] ;
        $route53Result->Name = $domainExists['domain_name'] ;
        $route53Result->CallerReference = $unique ;
        $route53Result->caller_reference = $unique ;
//        $route53Result->Config = $single_hosted_zone['Config'] ;
//        $route53Result->ResourceRecordSetCount = $single_hosted_zone['ResourceRecordSetCount'] ;
        return $route53Result;

    }

    protected function doesDomainExist() {
        $p_api_vars['api_uri'] = '/api/compute/domain/all';
        $p_api_vars['page'] = 'all' ;
        $list = $this->performRequest($p_api_vars);
//        var_dump('doesDomainExist list');
//        var_dump($list);
        $found = false ;
        foreach ($list['domains'] as $domain) {
            if ($domain['compute_domain_name'] === $this->params["domain-name"]) {
                $found['domain_name'] = $domain['compute_domain_name'] ;
                $found['id'] = $domain['compute_domain_id'] ;
            }
        }
        return $found ;
    }

    protected function performPiranhaComputeDeleteHealthCheck($params=null){
        if ($this->askForDeleteExecute() != true) { return false; }
        $this->initialisePiranha();
        $this->getHealthCheckId();

        try{
        $result = $this->piranhaClient->deleteHealthCheck(
            array(
                'HealthCheckId' =>$this->params["health-check-id"]

            )


        );
        }catch (\Aws\Route53\Exception\Route53Exception  $e)
        {
            echo $e->getMessage();
        }
        $bucketResult = new \StdClass() ;
        $bucketResult->status = "deleted" ;
        $bucketResult->requested =  $this->params["health-check-id"];
        return $bucketResult;
    }

    protected function performPiranhaComputeDeleteHostedZone($params=null){
        if ($this->askForDeleteExecute() != true) { return false; }
        $this->initialisePiranha();
        $this->getDomainName();
        $this->getHostedZoneId();
        $unique= md5(uniqid(rand(), true));
        try{

            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            if (isset($this->params["hosted-zone-id"]) && $this->params["hosted-zone-id"]  !== '') {
                $logging->log("Using Provided Zone ID of {$this->params["hosted-zone-id"]}", $this->getModuleName());
                $result = $this->piranhaClient->getHostedZone(
                    array(
                        'Id' =>$this->params["hosted-zone-id"]
                    )
                ) ;
            } elseif (isset($this->params["domain-name"]) && $this->params["domain-name"]  !== '') {
                $logging->log("Finding Zone ID's by {$this->params["domain-name"]}", $this->getModuleName());
                $result = $this->piranhaClient->listHostedZonesByName(
                    array(
                        'ComputeName' =>$this->params["domain-name"],
                        'CallerReference'=>$unique
                    )
                ) ;
            } else {
                $logging->log("Domain name or Zone ID required", $this->getModuleName());
                return false;
            }

            $hosted_zone_count = count($result['HostedZones']) ;
            $logging->log("Found Zone {$this->params["domain-name"]} with ".$hosted_zone_count.' entries', $this->getModuleName());
            if ($hosted_zone_count > 0) {
                $logging->log("Zone/s for {$this->params["domain-name"]} found, deleting", $this->getModuleName());
                foreach ($result['HostedZones'] as $hostedZone) {
                    $result = $this->piranhaClient->deleteHostedZone(
                        array(
                            'Id' => $hostedZone['Id']
                        )
                    );
                    $logging->log("Deleted Zone {$this->params["domain-name"]}, with ID {$hostedZone['Id']}", $this->getModuleName());
                }
            } else {
                $logging->log("Zone for {$this->params["domain-name"]} does not exist, nothing to delete.", $this->getModuleName());
            }
        } catch(\Aws\Route53\Exception\Route53Exception $e) {
            echo $e->getMessage();
        }
        $bucketResult = new \StdClass() ;
        $bucketResult->status = "deleted" ;
        $bucketResult->requested =  $this->params["hosted-zone-id"];
        return $bucketResult;
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
    protected function specifyEndPointByIp(){
        if (isset($this->params["yes-ip"]) && $this->params["yes-ip"]==true) { return true ; }
        $question = 'specify ip address by Ip?';
        return self::askYesOrNo($question);
    }

    protected function  getRequestInterval()
    {
        if (isset($this->params["request-interval"])) { return ; }
        $question = 'Enter Request Interval in seconds:';
        $this->params["request-interval"]= self::askForInput($question, true);
    }

    protected function  getfailureThresHold()
    {
        if (isset($this->params["failure-threshold"])) { return ; }
        $question = 'Enter Failure Threshold:';
        $this->params["failure-threshold"]= self::askForInput($question, true);
    }
    protected function  getProtocol()
    {
        if (isset($this->params["check-type"])) { return ; }
        $question = 'Enter type(HTTP/HTTPS/TCP):';
        $this->params["check-type"]= self::askForInput($question, true);
    }

    protected function  getIpAddress()
    {
        if (isset($this->params["ip-address"])) { return ; }
        $question = 'Enter Ip address: ';
        $this->params["ip-address"]= self::askForInput($question, true);
    }

    protected function  getDomainName()
    {
        if (isset($this->params["domain-name"])) { return ; }
        if (isset($this->params["zone"])) { return ; }
        if (isset($this->params["hosted-zone"])) { return ; }
        if (isset($this->params["hosted-zone-id"])) { return ; }
        $question = 'Enter domain name: ';
        $this->params["domain-name"]= self::askForInput($question, true);
    }

    protected function getDomainComment() {
        if (isset($this->params["domain-comment"])) { return ; }
        if (isset($this->params["guess"])) {
            $this->params["domain-comment"] = "" ;
            return ;
        }
        $question = 'Enter an optional Domain Comment';
        $this->params["domain-comment"] = self::askForInput($question, true);
    }

    protected function  getHealthCheckId()
    {
        if (isset($this->params["health-check-id"])) { return ; }
        $question = 'Enter Health Check Id: :';
        $this->params["health-check-id"]= self::askForInput($question, true);
    }

    protected function  getHostedZoneId()
    {
        if (isset($this->params["zone"])) {
            $this->params["hosted-zone-id"] = $this->params["zone"] ; }
        if (isset($this->params["hosted-zone"])) {
            $this->params["hosted-zone-id"] = $this->params["hosted-zone"] ; }
        if (isset($this->params["hosted-zone-id"])) { return ; }
        if (isset($this->params["domain-name"])) { return ; }
        $question = 'Enter Hosted Zone Id: :';
        $this->params["hosted-zone-id"] = self::askForInput($question, true);
    }


}
