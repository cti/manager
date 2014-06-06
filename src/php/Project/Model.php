<?php
namespace Project;

use Build\Application;

class Model
{
    /**
     * @var \Project\Property[]
     */
    protected $properties = array();

    /**
     * @var Application
     */
    protected $application;

    public function __construct($config, Application $application)
    {
        $this->application = $application;
        foreach($config['properties'] as $name => $propertyConfig) {
            $this->addProperty($name, $propertyConfig);
        }
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function addProperty($name, $config)
    {
        $config['name'] = $name;
        $property = $this->application->getManager()->create('\Project\Property', $config);
        $this->properties[$name] = $property;
    }

    public function removeProperty($name)
    {
        unset($this->properties[$name]);
    }

    public function setPrimaryKey($pk)
    {
        foreach($this->getProperties() as $property) {
            $property->setPrimary(in_array($property->getName(), $pk));
        }
    }

    public function getPrimaryKey()
    {
        $pk = array();
        foreach($this->getProperties() as $property) {
            if ($property->isPrimary()) {
                $pk[] = $property->getName();
            }
        }
        return $pk;
    }
} 