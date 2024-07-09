<?php

Namespace Controller ;

class PiranhaDatabase extends Base {

    public function execute($pageVars) {
//
        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Base") ;
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];


        if(in_array($action, array("create-instance", "instance-create", 'ensure-instance-exists'))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Instance") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToCreateInstance();
            return array ("type"=>"view", "view"=>"PiranhaDatabaseAPI", "pageVars"=>$this->content);
        }
        if(in_array($action, array("delete-record", 'ensure-record-empty'))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Instance") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToDeleteInstance();
            return array ("type"=>"view", "view"=>"PiranhaDatabaseAPI", "pageVars"=>$this->content);
        }

        if($action=="list")
        {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Listing") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToListData();
            return array ("type"=>"view", "view"=>"PiranhaDatabaseList", "pageVars"=>$this->content);
        }


        $this->content["messages"][] = "Invalid Piranha Web Services Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

}