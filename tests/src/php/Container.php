<?php

class Container 
{
    /**
     * @var \Project\Schema
     */
    static $schema;

    /**
     * @return \Project\Schema
     */
    public static function getSchema()
    {
        if (!self::$schema) {
            $application = getApplication();
            $inputDataPath = $application->getProject()->getPath('resources inputData.json');
            $data = json_decode(file_get_contents($inputDataPath), true);
            self::$schema = $application->getManager()->create("\\Project\\Schema", $data);
        }
        return self::$schema;
    }
} 