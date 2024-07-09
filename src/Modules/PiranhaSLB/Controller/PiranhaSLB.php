<?php

Namespace Controller ;

class PiranhaSLB extends Base {

    public function execute($pageVars) {
//
        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Base") ;
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];
        if(in_array($action, array("create-hosted-zone", 'ensure-domain-exists'))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Domain") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToCreateHostedZone();
            return array ("type"=>"view", "view"=>"PiranhaSLBAPI", "pageVars"=>$this->content);
        }
        if(in_array($action, array("delete-hosted-zone", 'ensure-domain-empty'))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Domain") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToDeleteHostedZone();
            return array ("type"=>"view", "view"=>"PiranhaSLBAPI", "pageVars"=>$this->content);
        }
        if(in_array($action, array("create-record", 'ensure-record-exists'))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Record") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToCreateRecord();
            return array ("type"=>"view", "view"=>"PiranhaSLBAPI", "pageVars"=>$this->content);
        }
        if(in_array($action, array("delete-record", 'ensure-record-empty'))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Record") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToDeleteRecord();
            return array ("type"=>"view", "view"=>"PiranhaSLBAPI", "pageVars"=>$this->content);
        }

        if($action=="create-health-check") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Domain") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToCreateHealthCheck();
            return array ("type"=>"view", "view"=>"PiranhaSLBAPI", "pageVars"=>$this->content);

        }

        if($action=="delete-health-check")
        {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Domain") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToDeleteHealthCheck();
            return array ("type"=>"view", "view"=>"PiranhaSLBAPI", "pageVars"=>$this->content);
        }
        if($action=="list")
        {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Listing") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToListData();
            return array ("type"=>"view", "view"=>"PiranhaSLBList", "pageVars"=>$this->content);
        }


        $this->content["messages"][] = "Invalid Piranha Web Services Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

}