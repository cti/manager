
        $person = $schema->getModel('person');
        $person->setPrimaryKey(array(
            'id_person',
            'v_end',
            'login',
        ));

        $module = $schema->getModel('module');
        $schema->renameModel('module', 'modules');
        $module->addProperty('type', array(
            'comment' => 'Comment',
            'type' => 'string',
            'required' => true,
        ));

        $person_favorite_module_link = $schema->getModel('person_favorite_module_link');
        $person_favorite_module_link->renameProperty('rating', 'ratings');
        $person_favorite_module_link->removeProperty('v_start');

        $schema->deleteModel('module_developer_link');

        $test = $schema->createModel('test', '', array(
            'id_test' => array(
                'comment' => 'Comment',
                'type' => 'integer',
                'required' => true,
            ),
        ));
        $test->setPrimaryKey(array(
            'id_test',
        ));
