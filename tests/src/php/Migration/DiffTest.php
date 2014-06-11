<?php

/**
 * Class DiffTest
 *
 * Possible schema changes:
 *  Create model
 *  Delete model
 *  Rename model
 *
 *  Create field in model
 *  Rename field
 *  Change field params -> change model's PK
 *  Remove field
 *
 *  Changes in modifiedSchema:
 *      Add login field into PK of person model
 *      Rename model "Module" to modules
 *      Add field "type" to "Module" model
 *      Add model "test" with 1 field
 *      Delete "v_start" from person_favorite_module_link
 *      Rename rating to ratings in person_favorite_module_link
 *      Delete model "module_developer_link"
 *
 */
class DiffTest extends \PHPUnit_Framework_TestCase
{
    public function testDifference()
    {
        $from = Container::getSchema();
        $to = Container::getModifiedSchema();
        $diffTool = getApplication()->getManager()->create("\\Migration\\Diff", array(
            'from' => $from,
            'to' => $to
        ));
        $difference = $diffTool->getDiff();
        $this->assertEquals(Container::getCorrectDifference(), $difference);
    }
}