<?php

namespace Migration;

class Diff
{
    /**
     * @var \Project\Schema
     */
    protected $from;

    /**
     * @var \Project\Schema
     */
    protected $to;

    /**
     * @var array
     */
    protected $differences = array();

    public function getDifference()
    {

    }
} 