<?php

namespace Direct;

use Build\Application;

class Project
{
	function getList() {
        return array(
            'data' => array(
                array(
                    'nick' => 'test',
                    'path' => __DIR__
                )
            )
        );
	}

	function create() {

	}
}