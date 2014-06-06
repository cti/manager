<?php

class SchemaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Project\Schema
     */
    protected $schema;

    protected function getSchema()
    {
        if (!$this->schema) {
            $application = getApplication();
            $inputDataPath = $application->getProject()->getPath('resources inputData.json');
            $data = json_decode(file_get_contents($inputDataPath), true);
            $this->schema = $application->getManager()->create("\\Project\\Schema", $data);
        }
        return $this->schema;
    }


    public function testCreate()
    {
        $this->assertNotNull($this->getSchema());
        $models = $this->getSchema()->getModels();
        $this->assertCount(4, $models);
        $this->assertArrayHasKey("person", $models);
        $person = $this->getSchema()->getModel("person");
        $this->assertInstanceOf("\\Project\\Model", $person);
    }

    public function testAddModel()
    {
        $this->markTestSkipped();
    }

    public function testRemoveModel()
    {
        $this->markTestSkipped();
    }


} 