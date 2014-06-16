<?php

class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testMigrationTest()
    {
        $application = getApplication();
        $fenom = $application->getFenom();
        $fenom->addSource(
            dirname($application->getProject()->getPath())
            . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'fenom'
        );
        $difference = $this->getDifference();
        $template = $fenom->render('migration', array(
            'difference' => $difference,
        ));
        $correctMigration = $this->getCorrectMigration();
        $this->assertEquals($correctMigration, $template);

    }

    protected function getDifference()
    {
        $from = Container::getSchema();
        $to = Container::getModifiedSchema();
        $diffTool = getApplication()->getManager()->create("\\Migration\\Diff", array(
            'from' => $from,
            'to' => $to
        ));
        return $diffTool->getDiff();
    }

    protected function getCorrectMigration()
    {
        $correctMigrationPath = getApplication()->getProject()->getPath('resources correctMigration.php');
        return file_get_contents($correctMigrationPath);
    }


} 