<?php

class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testMigrationTest()
    {
        $application = getApplication();
        $difference = $this->getDifference();

        $application->getFenom()->addSource(
            dirname($application->getProject()->getPath())
            . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'fenom'
        );
        /**
         * @var \Migration\Generator $generator
         */
        $generator = $application->getManager()->get("\\Migration\\Generator");
        $migration = $generator->getMigrationCode($difference);

        $correctMigration = $this->getCorrectMigration();
        $this->assertEquals($correctMigration, $migration);
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