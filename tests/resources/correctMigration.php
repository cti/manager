<?php

$schema->setNamespace('public');

$person = $schema->getModel('person');
$person->setPrimaryKey(array("id_person", "v_end", "login"));

$schema->renameModel('module', 'modules');
$modules = $schema->getModel('modules');
$modules->addProperty(array(
    'comment' => 'Comment',
    'type' => 'string',
    'required' => true,
));

$test = $schema->createModel('test', "Test", array(
    'id_test' => array(
        'comment' => 'Comment',
        'type' => 'integer',
        'required' => true,
    ),
));

$person_favorite_module_link = $schema->getModel('person_favorite_module_link');
$person_favorite_module_link->removeProperty('v_start');
$person_favorite_module_link->renameProperty('rating', 'ratings');

$schema->deleteModel('module_developer_link');

