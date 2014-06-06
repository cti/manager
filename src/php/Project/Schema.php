<?php
namespace Project;

class Schema 
{
    /**
     * @var \Project\Model[]
     */
    protected $models = array();

    /**
     * @inject
     * @var \Build\Application
     */
    protected $application;

    public function init()
    {
        $modelsConfig = $this->models;
        $this->models = array();
        foreach($modelsConfig as $name => $modelConfig) {
            $this->createModel($name, $modelConfig);
        }
    }

    public function getModel($nick)
    {
        return $this->models[$nick];
    }

    public function getModels()
    {
        return $this->models;
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