<?php
namespace Project;

class Project 
{
    /**
     * @var Array
     */
    protected $config;

    /**
     * @inject
     * @var \Build\Application
     */
    protected $application;

    /**
     * @var \Project\Schema
     */
    protected $schema;

    public function __construct($config)
    {
        $this->config = $config;
        $this->consolePath = implode(DIRECTORY_SEPARATOR, array(
            $this->config['path'],
            'resources',
            'php',
            'start-console.php',
        ));
    }

    protected function executeConsole($params)
    {
        $command = "php $this->consolePath $params";
        exec($command, $output);
        return implode(PHP_EOL, $output);
    }

    public function getSchema()
    {
        if (!$this->schema) {
            $jsonSchema = $this->executeConsole("show:schema");
            $data = json_decode($jsonSchema, true);
            $this->schema = $this->application->getManager()->create("\\Project\\Schema", $data);
        }
        return $this->schema;
    }

    public function getPath($string)
    {
        $chain = implode(DIRECTORY_SEPARATOR, explode(' ', $string));
        return implode(DIRECTORY_SEPARATOR, array($this->config['path'], $chain));
    }

    public function saveSchema($config)
    {
        $schema = $this->getSchema();
        $newSchema = $this->application->getManager()->create("\\Project\\Schema", $config);
        /**
         * @var \Migration\Diff $diff
         */
        $diff = $this->application->getManager()->create("\\Migration\\Diff", array(
            'from' => $schema,
            'to' => $newSchema
        ));
        $migrationCode = $diff->getMigrationCode();
        /**
         * Execute console generate migration
         * Inject migration code in method of created migrtion class
         * @todo make migration names
         */
        $output = $this->executeConsole('generate:migration migrationName');
        $regexp =
            "/resources\\"
            . DIRECTORY_SEPARATOR . "php\\"
            . DIRECTORY_SEPARATOR
            . "migrations\\"
            . DIRECTORY_SEPARATOR
            . "([0-9]+_[0-9]+_[A-Za-z]+.php)/";
        preg_match($regexp, $output, $matches);
        if (!$matches || count($matches) == 0) {
            throw new \Exception("Migration file not found");
        }
        $fileName = $matches[1];
        $fullMigrationPath = $this->getPath('resources php migrations ' . $fileName);
        $content = file_get_contents($fullMigrationPath);
        $modifiedContent = $this->modifyMigration($content, $migrationCode);
        file_put_contents($fullMigrationPath, $modifiedContent);
    }

    /**
     * Inject migration code into generated migration file in process method
     * @param $content
     * @param $migrationCode
     * @return string
     */
    public function modifyMigration($content, $migrationCode)
    {
        $position = strpos($content, "(Schema \$schema)") + 23;
        return
            substr($content, 0, $position)
            . $migrationCode
            . substr($content, $position + 2);
    }
}