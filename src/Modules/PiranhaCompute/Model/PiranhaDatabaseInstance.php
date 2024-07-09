<?php

Namespace Model;

class PiranhaDatabaseInstance extends BasePiranhaDatabaseAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Instance");

    public function askWhetherToCreateInstance($params=null) {
        return $this->performPiranhaComputeCreateInstance($params);
    }

    public function askWhetherToDeleteInstance($params=null) {
        return $this->performPiranhaComputeDeleteInstance($params);
    }

    protected function performPiranhaComputeCreateInstance($params=null){

        if ($this->askForInstanceAddDeleteExecute() != true) {
            return false;
        }

        $this->initialisePiranha();
        $this->getInstanceName() ;
        $this->getInstanceDescription() ;
        $this->getImageID() ;
        $this->getInternetEnabled() ;
        $this->getEgressEnabled() ;
        $this->getPrivateNetworks() ;
        $this->getKeypairID() ;
        $this->getComputeEngine() ;
        $this->getUserData() ;

        /*
         *             $vm_name = SharedValidationHelper::validate('instance_name', false, 'string', 'Instance Name') ;
         *
            $vm_description = SharedValidationHelper::validate('description', false, 'string', 'Instance Description') ;
            $size_slug = SharedValidationHelper::validate('size_slug', true, 'string', 'Size ID') ;
            $image_id = SharedValidationHelper::validate('image_id', true, 'string', 'Image ID') ;
            $internet_enabled = SharedValidationHelper::validate('internet_enabled', false, 'string', 'Enable Internet Network') ;
            $egress_enabled = SharedValidationHelper::validate('egress_enabled', false, 'string', 'Enable Egress Network') ;
            $private_networks_param = SharedValidationHelper::validate('private_networks', false, 'string', 'Private Network IDs') ;
            $keypair = SharedValidationHelper::validate('keypair', false, 'string', 'SSH Keypair ID') ;
            $compute_engine = SharedValidationHelper::validate('compute_engine', false, 'string', 'Compute Engine') ;
            $user_data = SharedValidationHelper::validate('userdata', false, 'string', 'User Data Script') ;

         */

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Performing Instance Ensure", $this->getModuleName());
        $unique= md5(uniqid(rand(), true));

        $p_api_vars['api_uri'] = '/api/sc1/instance/create';
        $p_api_vars['region'] = 'dc' ;

        $p_api_vars['instance_name'] = $this->params["instance-name"] ;
        $p_api_vars['instance_description'] = $this->params["instance-description"] ;
        $p_api_vars['size_slug'] = $this->params["size-slug"] ;
        $p_api_vars['image_id'] = $this->params["image-id"] ;
        $p_api_vars['internet_enabled'] = $this->params["internet-enabled"] ;
        $p_api_vars['egress_enabled'] = $this->params["egress-enabled"] ;
        $p_api_vars['private_networks'] = $this->params["private-networks"] ;
        $p_api_vars['keypair'] = $this->params["keypair"] ;
        $p_api_vars['compute_engine'] = $this->params["compute-engine"] ;
        $p_api_vars['userdata'] = $this->params["user-data"] ;

        echo "Requesting Instance...\n" ;

        $result = $this->performRequest($p_api_vars);

        if ($result['status'] === 'error') {
            return [] ;
        }


        echo "Request Complete...\n" ;

//        var_dump($result);

        $mod_id = $result['modification_id'] ;

        if ($mod_id === "") {
            echo "No Modification ID, command failed\n" ;
            return [] ;
        }

        echo "Modification ID is ".$mod_id."\n" ;



        $wait_period = 5 ;

        for ($checks=1; $checks<200; $checks++) {
            sleep ($wait_period) ;
            $p_api_vars['api_uri'] = '/api/sc1/instance/modify_status';
            $p_api_vars['mod_id'] = $mod_id ;
            $p_api_vars['vm_id'] = $result['vm_details']["vm_id"] ;
            $mod_result = $this->performRequest($p_api_vars);
            echo $checks*$wait_period . "s: " ;
            if ($mod_result['status'] == 'OK') {
                echo $mod_result['modification']['stage']."\n" ;
            }
            if ($mod_result['status'] == 'false') {
                echo "Modification Failed...\n" ;
                echo $mod_result['message']."\n" ;
                break ;
            }
            if (isset($mod_result['modification']['status'])) {
                if ($mod_result['modification']['status'] == 'Complete') {
                    echo "Instance Creation is Complete\n" ;
                    break ;
                }
            }

        }

        return $result;

    }


    protected function performPiranhaComputeDeleteInstance($params=null){
        if ($this->askForInstanceAddDeleteExecute() != true) {
            return false; }
        $this->initialisePiranha();
        $this->getInstanceID() ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Performing Instance Deletion", $this->getModuleName());

        $p_api_vars['api_uri'] = '/api/compute/instance/delete';
        $p_api_vars['id'] = $this->params["instance-id"] ;
        $result = $this->performRequest($p_api_vars);

        echo "Before Result\n\n" ;

        var_dump($result);

        echo "After Result\n\n" ;

        return $result;

    }




    protected function askForInstanceAddDeleteExecute() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Ensure Piranha SMP Instance?';
        return self::askYesOrNo($question);
    }

    protected function getInstanceID() {
        if (isset($this->params["instance-id"])) { return ; }
        $question = 'Enter Instance ID: ';
        $this->params["instance-id"]= self::askForInput($question, true);
    }

    protected function getInstanceName() {
        if (isset($this->params["instance-name"])) { return ; }
        $question = 'Enter Instance Name';
        $this->params["instance-name"] = self::askForInput($question, true);
    }

    protected function getInstanceDescription() {
        if (isset($this->params["instance-description"])) { return ; }
        $question = 'Instance Description';
        $this->params["instance-description"] = self::askForInput($question, true);
    }

    protected function getImageID() {
        if (isset($this->params["image-id"])) { return ; }
        $question = 'Image ID';
        $this->params["image-id"] = self::askForInput($question, true);
    }

    protected function getInternetEnabled() {
        if (isset($this->params["internet-enabled"])) { return ; }
        $question = 'Internet Enbaled?';
        $this->params["internet-enabled"] = self::askForInput($question, true);
    }

    protected function getEgressEnabled() {
        if (isset($this->params["egress-enabled"])) { return ; }
        $question = 'Egress Enabled?';
        $this->params["egress-enabled"] = self::askForInput($question, true);
    }

    protected function getPrivateNetworks() {
        if (isset($this->params["private-networks"])) { return ; }
        $question = 'Private Networks';
        $this->params["private-networks"] = self::askForInput($question, true);
    }

    protected function getKeypairID() {
        if (isset($this->params["keypair"])) { return ; }
        $question = 'Enter Keypair Name';
        $this->params["keypair"] = self::askForInput($question, true);
    }

    protected function getComputeEngine() {
        if (isset($this->params["compute-engine"])) { return ; }
        $question = 'Enter Compute Engine';
        $this->params["compute-engine"] = self::askForInput($question, true);
    }

    protected function getUserData() {
        if (isset($this->params["user-data"])) { return ; }
        $question = 'Enter User Data';
        $this->params["user-data"] = self::askForInput($question, true);
    }

}
