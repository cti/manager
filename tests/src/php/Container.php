<?php

class Container 
{
    /**
     * @var \Project\Schema
     */
    static $schema;

    /**
     * @var \Project\Schema
     */
    static $modifiedSchema;

    /**
     * @return \Project\Schema
     */
    public static function getSchema()
    {
        if (!self::$schema) {
            self::$schema = self::makeSchema('inputData.json');
        }
        return self::$schema;
    }

    public static function getModifiedSchema()
    {
        if (!self::$modifiedSchema) {
            self::$modifiedSchema = self::makeSchema('modifiedSchema.json');
        }
        return self::$modifiedSchema;
    }

    protected static function makeSchema($name)
    {
        $application = getApplication();
        $inputDataPath = $application->getProject()->getPath('resources ' . $name);
        $data = json_decode(file_get_contents($inputDataPath), true);
        return $application->getManager()->create("\\Project\\Schema", $data);
    }

    public static function getCorrectDifference()
    {
        $path = getApplication()->getProject()->getPath('resources correctDifference.php');
        return include($path);
    }
} 