<?php
namespace Project;

class Project 
{
    /**
     * @var Array
     */
    protected $config;

    /**
     * @inject
     * @var \Build\Application
     */
    protected $application;

    /**
     * @var \Project\Schema
     */
    protected $schema;

    public function __construct($config)
    {
        $this->config = $config;
        $this->consolePath = implode(DIRECTORY_SEPARATOR, array(
            $this->config['path'],
            'resources',
            'php',
            'start-console.php',
        ));
    }

    protected function executeConsole($params)
    {
        $command = "php $this->consolePath $params";
        exec($command, $output);
        return implode(PHP_EOL, $output);
    }

    public function getSchema()
    {
        if (!$this->schema) {
            $jsonSchema = $this->executeConsole("show:schema");
            $data = json_decode($jsonSchema, true);
            $this->schema = $this->application->getManager()->create("\\Project\\Schema", $data);
        }
        return $this->schema;
    }

    public function getModels()
    {
        $schema = $this->getSchema();
        return $schema['models'];
    }

    public function getModel($name)
    {
        $schema = $this->getSchema();
        return $schema['models'][$name];
    }

    public function getModelInstance($name)
    {

    }

} 