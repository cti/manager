<?php
namespace Project;

use Build\Application;

class Manager
{
    /**
     * @var Array
     */
    protected $configuration;

    /**
     * @var String
     */
    protected $configurationPath;

    /**
     * @var \Build\Application
     */
    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
        $this->configurationPath = $application->getProject()->getPath('resources php projects.php');
        if (file_exists($this->configurationPath)) {
            $this->configuration = include($this->configurationPath);
        } else {
            $this->configuration = array();
        }
    }

    public function getList()
    {
        return array_values($this->configuration);
    }

    public function validProject($path)
    {
        return file_exists($path . DIRECTORY_SEPARATOR . 'composer.json') &&
        file_exists(implode(DIRECTORY_SEPARATOR, array($path, 'resources', 'php', 'config.php')));
    }

    public function addProject($nick, $path)
    {
        $this->configuration[$nick] = array(
            'nick' => $nick,
            'path' => $path,
        );
        $code = "<?php return " . var_export($this->configuration, true) . ";";
        return !!file_put_contents($this->configurationPath, $code);
    }

    public function getProject($nick)
    {
        if (!isset($this->configuration[$nick])) {
            throw new \Exception("No project $nick found");
        }
        $project = $this->application->getManager()->create('\Project\Project',
            $this->configuration[$nick]
        );
        return $project;
    }
}