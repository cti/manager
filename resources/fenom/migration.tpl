
{foreach $difference['models'] as $name => $modelData}

{if $modelData['action'] == 'delete'}
        $schema->deleteModel('{$name}');
{elseif $modelData['action'] == 'create'}
        ${$name} = $schema->createModel('{$name}', '', array(
{foreach $modelData['properties'] as $propertyName => $property}
{var $data = $property['data']}
            '{$propertyName}' => array(
                'comment' => '{$data["comment"]}',
                'type' => '{$data["type"]}',
                'required' => {$data['required'] ? "true" : "false"},
            ),
{/foreach}
        ));
        ${$name}->setPrimaryKey(array(
{foreach $modelData['pk'] as $field}
            '{$field}',
{/foreach}
        ));
{elseif is_null($modelData['action']) || $modelData['action'] == 'rename'}
        ${$name} = $schema->getModel('{$name}');
{if $modelData['action'] == 'rename'}
        $schema->renameModel('{$name}', '{$modelData["new_name"]}');
{/if}
{if $modelData['properties']?}
{foreach $modelData['properties'] as $propertyName => $property}
{if $property['action'] == 'add'}
{var $data = $property['data']}
        ${$name}->addProperty('{$propertyName}', array(
            'comment' => '{$data["comment"]}',
            'type' => '{$data["type"]}',
            'required' => {$data['required'] ? "true" : "false"},
        ));
{elseif $property['action'] == 'remove'}
        ${$name}->removeProperty('{$propertyName}');
{elseif $property['action'] == 'rename' || is_null($property['action'])}
{if $property['action'] == 'rename'}
        ${$name}->renameProperty('{$propertyName}', '{$property["name"]}');
{/if}
{if $property['data']?}
{var $data = $property['data']}
        ${$name}->alterProperty(array(
            'comment' => '{$data["comment"]}',
            'type' => '{$data["type"]}',
            'required' => {$data["required"] ? "true" : "false"},
        ));
{/if}
{/if}
{/foreach}
{/if}
{if $modelData['pk']?}
        ${$name}->setPrimaryKey(array(
{foreach $modelData['pk'] as $field}
            '{$field}',
{/foreach}
        ));
{/if}
{/if}
{/foreach}