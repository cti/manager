<?php

namespace Controller;

use Build\Application;
use Cti\Core\Module\Web;

class DefaultController
{
    public function get(Application $application)
    {
        $application->getCoffee()->build('application');
        
        $application->getFenom()->display('index', array(
            'script' => 'public/js/application.js',
            'direct' => $application->getDirect()->getUrl()
        ));
    }


    /**
     * if no method was found you can process request by yourself
     * chain is url pieces delimited by /
     * you can inject any parameter (thanks to di)
     * @param Web $web
     * @param array $chain
     */
    function processChain(Web $web, $chain)
    {
        $this->display('url', array(
            'base' => $web->getUrl(),
            'request' => $web->getUrl(implode('/', $chain)),
        ));
    }
}