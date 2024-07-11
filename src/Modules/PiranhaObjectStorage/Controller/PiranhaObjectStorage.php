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


        if (in_array($action, array("create-bucket", "bucket-create"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Bucket") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToCreateBucket();
            return array ("type"=>"view", "view"=>"PiranhaObjectStorageAPI", "pageVars"=>$this->content);
        }

        if (in_array($action, array("delete-bucket", "bucket-delete"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Bucket") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToDeleteBucket();
            return array ("type"=>"view", "view"=>"PiranhaObjectStorageAPI", "pageVars"=>$this->content);
        }


        if (in_array($action, array("create-object", "object-create", "upload"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Object") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToCreateObject();
            return array ("type"=>"view", "view"=>"PiranhaObjectStorageAPI", "pageVars"=>$this->content);
        }

        if (in_array($action, array("delete-object", "object-delete", "delete"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Object") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToDeleteObject();
            return array ("type"=>"view", "view"=>"PiranhaObjectStorageAPI", "pageVars"=>$this->content);
        }

        if (in_array($action, array("download-object", "object-download", "download"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Object") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToDownloadObject();
            return array ("type"=>"view", "view"=>"PiranhaObjectStorageAPI", "pageVars"=>$this->content);
        }


        if ($action=="list") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Listing") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["piranhaResult"] = $thisModel->askWhetherToListData();
            return array ("type"=>"view", "view"=>"PiranhaObjectStorageList", "pageVars"=>$this->content);
        }

        $this->content["messages"][] = "Invalid Piranha Web Services Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

}