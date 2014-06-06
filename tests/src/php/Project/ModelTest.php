<?php

class ModelTest extends \PHPUnit_Framework_TestCase
{
    public function testAddField()
    {
        $schema = Container::getSchema();
        $person = $schema->getModel('person');

        $fieldConfig = array(
            'comment' => 'phone number',
            'type' => 'string',
            'required' => false
        );
        $person->addProperty('phone', $fieldConfig);
        $field = $person->getProperty('phone');
        $this->assertNotNull($field);
        $this->assertEquals('phone', $field->getName());
    }

    public function testRemoveField()
    {
        $schema = Container::getSchema();
        $person = $schema->getModel('person');
        $person->removeProperty('hash');
        $properties = $person->getProperties();
        $this->assertArrayNotHasKey('hash', $properties);
    }

    public function testGetSetPK()
    {
        $schema = Container::getSchema();
        $person = $schema->getModel('person');
        $idealPk = array('id_person', 'v_end');
        $this->assertEquals($idealPk, $person->getPrimaryKey());

        // Delete from property method
        $vEnd = $person->getProperty('v_end');
        $vEnd->setPrimary(false);
        $this->assertArrayNotHasKey('v_end', $person->getPrimaryKey());

        // Add from model method
        $person->setPrimaryKey(array('id_person', 'login'));
        $idealPk = array('id_person', 'login');
        $this->assertEquals($idealPk, $person->getPrimaryKey());
    }

} 