<?php
namespace Project;

class Project 
{
    /**
     * @var Array
     */
    protected $config;

    public function __construct($config)
    {
        $this->config = $config['configuration'];
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
        $jsonSchema = $this->executeConsole("show:schema");
        $schema = json_decode($jsonSchema, true);
        return $schema;
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

} 