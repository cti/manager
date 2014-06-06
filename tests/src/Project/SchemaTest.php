<?php

class SchemaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Project\Project
     */
    protected $schema;

    protected function getProject()
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
        $this->assertNotNull($this->getProject());

    }
} 