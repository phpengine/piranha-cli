<?php

Namespace Model;

class BasePiranhaDatabaseAllOS extends PiranhaBaseLibs {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Base") ;

    protected function getClient() {
        $this->setProjVars() ;
        $c = array(
            'version' => 'latest',
            'region' => $this->region,
            'credentials' => [
                'key'    => $this->accessKey,
                'secret' => $this->secretKey,
            ]
        ) ;
        if (isset($this->endpointUrl) &&
            is_string($this->endpointUrl) &&
            ( strlen($this->endpointUrl) > 0 ) ) {
            $c['endpoint'] = $this->endpointUrl ;
        }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        try {
            $this->piranhaClient = \Aws\Route53\Route53Client::factory($c);
//            $logging->log("Client Started ".var_export($c, true), $this->getModuleName());
//            $logging->log("Client Started", $this->getModuleName());
        } catch (\Exception $e) {
            debug_print_backtrace() ;
        }
    }

}