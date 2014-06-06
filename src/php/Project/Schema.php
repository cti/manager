<?php
namespace Project;

class Schema 
{
    /**
     * @var \Project\Model[]
     */
    protected $models = array();

    /**
     * @var \Build\Application
     */
    protected $application;

    public function __construct($config, \Build\Application $application)
    {
        $this->application = $application;

        foreach($config['models'] as $name => $modelConfig) {
            $this->createModel($name, $modelConfig);
        }
    }

    public function getModel($nick)
    {
        return $this->models[$nick];
    }

    public function createModel($name, $config)
    {
        $this->models[$name] = $this->application->getManager()->create('\Project\Model', $config);
    }

    public function removeModel($name)
    {
        unset($this->models[$name]);
    }
} 