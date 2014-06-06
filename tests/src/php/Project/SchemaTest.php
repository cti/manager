<?php

class SchemaTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $schema = Container::getSchema();
        $this->assertNotNull($schema);
        $models = $schema->getModels();
        $this->assertCount(4, $models);
        $this->assertArrayHasKey("person", $models);
        $person = $schema->getModel("person");
        $this->assertInstanceOf("\\Project\\Model", $person);
    }

    public function testAddModel()
    {
        $schema = Container::getSchema();
        $newModelConfig = array(
            'pk' => array('id_test'),
            'has_log' => false,
            'is_link' => false,
            'properties' => array(
                'id_test' => array(
                    'comment' => 'test message',
                    'type' => 'integer',
                    'required' => 1
                ),
            ),
            'references' => array(),
        );
        $schema->createModel('test', $newModelConfig);
        $model = $schema->getModel('test');
        $this->assertNotNull($model);
        $this->assertInstanceOf('\\Project\\Model', $model);
    }

    public function testRemoveModel()
    {
        $schema = Container::getSchema();
        $moduleModel = $schema->getModel('module');
        $this->assertNotNull($moduleModel);
        $schema->removeModel('module');
        $models = $schema->getModels();
        $this->assertArrayNotHasKey('module', $models);
    }


} 