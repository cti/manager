<?php

return array(
    'models' => array(
        "person" => array(
            "action" => "pk_change",
            "pk" => array("id_person", "v_end", "login"),
        ),
        "module" => array(
            "action" => "rename",
            "new_name" => "modules",
            "properties" => array(
                "type" => array(
                    "action" => "add",
                    "data" => array(
                        "type" => "string",
                        'comment' => "Comment",
                        "required" => true,
                    )
                )
            ),
        ),
        "test" => array(
            "action" => "create",
            "pk" => array("id_test"),
            "properties" => array(
                "id_test" => array(
                    "action" => "add",
                    "data" => array(
                        "type" => "integer",
                        'comment' => "Comment",
                        "required" => true,
                    ),
                ),
            ),

        ),
        "person_favorite_module_link" => array(
            "action" => null,
            "properties" => array(
                "v_start" => array(
                    "action" => "remove",
                ),
                "rating" => array(

                    "action" => "rename",
                    "name" => "ratings",
                ),
            ),
        ),
        "module_developer_link" => array(
            "action" => "delete",
        ),
    ),
);