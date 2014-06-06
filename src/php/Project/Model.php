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
     * @inject
     * @var Application
     */
    protected $application;

    /**
     * @var array
     */
    protected $pk;

    public function init()
    {
        $config = $this->properties;
        $this->properties = array();
        foreach($config as $name => $propertyConfig) {
            $this->addProperty($name, $propertyConfig);
        }
        foreach($this->pk as $name) {
            $this->getProperty($name)->setPrimary(true);
        }
    }

    /**
     * @return Property[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param $name
     * @return Property
     */
    public function getProperty($name)
    {
        return $this->properties[$name];
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

    /**
     * @return array
     */
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