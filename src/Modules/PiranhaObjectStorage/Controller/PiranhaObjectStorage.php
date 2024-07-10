<?php

Namespace Controller ;

class PiranhaObjectStorage extends Base {

    public function execute($pageVars) {
//
        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Base") ;
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];


        if(in_array($action, array("create-repository", "create-repo"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Repository") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToCreateRepository();
            return array ("type"=>"view", "view"=>"PiranhaSCMAPI", "pageVars"=>$this->content);
        }

        if(in_array($action, array("delete-repository", "delete-repo"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Repository") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToDeleteRepository();
            return array ("type"=>"view", "view"=>"PiranhaSCMAPI", "pageVars"=>$this->content);
        }

//        if($action=="create-health-check") {
//            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Domain") ;
//            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
//            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
//            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
//            $this->content["piranhaResult"] = $thisModel->askWhetherToCreateHealthCheck();
//            return array ("type"=>"view", "view"=>"PiranhaObjectStorageAPI", "pageVars"=>$this->content);
//
//        }
//
//        if($action=="delete-health-check")
//        {
//            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Domain") ;
//            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
//            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
//            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
//            $this->content["piranhaResult"] = $thisModel->askWhetherToDeleteHealthCheck();
//            return array ("type"=>"view", "view"=>"PiranhaObjectStorageAPI", "pageVars"=>$this->content);
//        }

        if($action=="list")
        {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Listing") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToListData();
            return array ("type"=>"view", "view"=>"PiranhaSCMList", "pageVars"=>$this->content);
        }

        $this->content["messages"][] = "Invalid Piranha Web Services Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

}