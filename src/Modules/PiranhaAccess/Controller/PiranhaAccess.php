<?php

Namespace Controller ;

class PiranhaAccess extends Base {

    public function execute($pageVars) {
//
        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Base") ;
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];

        if (in_array($action, array("create-accesskey", "accesskey-create"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "AccessKey") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToCreateAccessKey();
            return array ("type"=>"view", "view"=>"PiranhaAccessAPI", "pageVars"=>$this->content);
        }

        if (in_array($action, array("delete-accesskey", "accesskey-delete"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "AccessKey") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToDeleteAccessKey();
            return array ("type"=>"view", "view"=>"PiranhaAccessAPI", "pageVars"=>$this->content);
        }

        if (in_array($action, array("create-user", "user-create"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "User") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToCreateUser();
            return array ("type"=>"view", "view"=>"PiranhaAccessAPI", "pageVars"=>$this->content);
        }

        if (in_array($action, array("delete-user", "user-delete"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "User") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToDeleteUser();
            return array ("type"=>"view", "view"=>"PiranhaAccessAPI", "pageVars"=>$this->content);
        }

        if (in_array($action, array("create-group", "group-create"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Group") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToCreateGroup();
            return array ("type"=>"view", "view"=>"PiranhaAccessAPI", "pageVars"=>$this->content);
        }

        if (in_array($action, array("delete-group", "group-delete"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Group") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToDeleteGroup();
            return array ("type"=>"view", "view"=>"PiranhaAccessAPI", "pageVars"=>$this->content);
        }

        if (in_array($action, array("create-role", "role-create"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Role") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToCreateRole();
            return array ("type"=>"view", "view"=>"PiranhaAccessAPI", "pageVars"=>$this->content);
        }

        if (in_array($action, array("delete-role", "role-delete"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Role") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToDeleteRole();
            return array ("type"=>"view", "view"=>"PiranhaAccessAPI", "pageVars"=>$this->content);
        }

        if (in_array($action, array("create-policy", "policy-create"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Policy") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToCreatePolicy();
            return array ("type"=>"view", "view"=>"PiranhaAccessAPI", "pageVars"=>$this->content);
        }

        if (in_array($action, array("delete-policy", "policy-delete"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Policy") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToDeletePolicy();
            return array ("type"=>"view", "view"=>"PiranhaAccessAPI", "pageVars"=>$this->content);
        }

        if ($action=="list") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Listing") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToListData();
            return array ("type"=>"view", "view"=>"PiranhaAccessList", "pageVars"=>$this->content);
        }

        $this->content["messages"][] = "Invalid Piranha Web Services Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

}