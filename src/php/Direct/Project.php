<?php

namespace Direct;

use Build\Application;

class Project
{
    /**
     * @inject
     * @var \Build\Application
     */
    protected $application;

    /**
     * @inject
     * @var \Project\Manager
     */
    protected $manager;

    function getList()
    {
        return $this->manager->getList();
	}

    public function add($data)
    {
        $path = dirname($this->application->getProject()->getPath(''))
            . DIRECTORY_SEPARATOR . $data->path;
        if (!$this->manager->validProject($path)) {
            throw new \Exception("No valid project in path $path");
        }
        return $this->manager->addProject($data->nick, $path);
    }

    public function getModels($projectNick)
    {
        $project = $this->manager->getProject($projectNick);
        return $project->getSchema()->getModels();
    }

    public function getSchema($projectNick)
    {
        $project = $this->manager->getProject($projectNick);
        return $project->getSchema()->asArray();
    }


}