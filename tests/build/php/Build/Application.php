<?php

namespace Build;

use Cti\Core\Module\Manager;

class Application
{
    /**
     * @inject
     * @var Manager
     */
    protected $manager;

    /**
     * initialize application
     */
    public function init()
    {
        $this->getManager()->boot($this);
        $this->getDirect()->boot($this);
    }

    /**
     * warm application
     */
    public function warm()
    {
        $this->getManager()->warm($this);
        $this->getCore()->warm($this);
        $this->getFenom()->warm($this);
        $this->getProject()->warm($this);
    }

    /**
     * @return \Cti\Core\Module\Cache
     */
    public function getCache()
    {
        return $this->getManager()->get('Cti\Core\Module\Cache');
    }

    /**
     * @return \Cti\Core\Module\Coffee
     */
    public function getCoffee()
    {
        return $this->getManager()->get('Cti\Core\Module\Coffee');
    }

    /**
     * @return \Cti\Core\Module\Console
     */
    public function getConsole()
    {
        return $this->getManager()->get('Cti\Core\Module\Console');
    }

    /**
     * @return \Cti\Core\Module\Core
     */
    public function getCore()
    {
        return $this->getManager()->get('Cti\Core\Module\Core');
    }

    /**
     * @return \Cti\Direct\Module
     */
    public function getDirect()
    {
        return $this->getManager()->get('Cti\Direct\Module');
    }

    /**
     * @return \Cti\Core\Module\Fenom
     */
    public function getFenom()
    {
        return $this->getManager()->get('Cti\Core\Module\Fenom');
    }

    /**
     * @return Manager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @return \Cti\Core\Module\Project
     */
    public function getProject()
    {
        return $this->getManager()->get('Cti\Core\Module\Project');
    }

    /**
     * @return \Cti\Core\Module\Web
     */
    public function getWeb()
    {
        return $this->getManager()->get('Cti\Core\Module\Web');
    }
}