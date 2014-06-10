<?php
namespace Project;

use Build\Application;

class Model
{
    /**
     * @var \Project\Property[]
     */
    protected $properties;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var String
     */
    protected $name;

    /**
     * @inject
     * @var Application
     */
    protected $application;

    /**
     * @var array
     */
    protected $pk;

    /**
     * @var array
     */
    protected $references = array();

    public function init()
    {
        $this->pk = $this->config['pk'];
        $config = $this->config['properties'];
        $this->properties = array();
        foreach($config as $name => $propertyConfig) {
            $this->addProperty($name, $propertyConfig);
        }
        foreach($this->pk as $name) {
            $this->getProperty($name)->setPrimary(true);
        }
        foreach($this->config['references'] as $reference) {
            $this->addReference($reference);
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

    public function addReference($config)
    {
        $this->references[$config['destination'].':'.$config['destination_alias']] = $config;
        foreach($config['properties'] as $name) {
            $property = $this->getProperty($name);
            $property->setForeign(true);
        }
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

    public function asArray()
    {
        $array = array(
            'name' => $this->name,
            'properties' => array(),
            'pk' => $this->getPrimaryKey(),
            'references' => array_values($this->references),
        );
        foreach($this->getProperties() as $property) {
            $array['properties'][] = $property->asArray();
        }
        return $array;

    }
} 