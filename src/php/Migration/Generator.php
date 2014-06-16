<?php
namespace Migration;

class Generator
{
    /**
     * @inject
     * @var \Build\Application
     */
    protected $application;

    /**
     * @param array $difference
     * @return string
     */
    public function getMigrationCode($difference)
    {
        return $this->application->getFenom()->render('migration', array(
            'difference' => $difference
        ));
    }
} 