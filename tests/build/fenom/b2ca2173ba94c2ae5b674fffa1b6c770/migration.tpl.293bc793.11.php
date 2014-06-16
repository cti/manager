<?php 
/** Fenom template 'migration.tpl' compiled at 2014-06-16 16:09:58 */
return new Fenom\Render($fenom, function ($var, $tpl) {
?>
<?php
/* migration.tpl:2: {foreach $difference['models'] as $name => $modelData} */
  if($var["difference"]['models']) {  foreach($var["difference"]['models'] as $var["name"] => $var["modelData"]) {  ?>

<?php
/* migration.tpl:4: {if $modelData['action'] == 'delete'} */
 if($var["modelData"]['action'] == 'delete') { ?>
        $schema->deleteModel('<?php
/* migration.tpl:5: {$name} */
 echo $var["name"]; ?>');
<?php
/* migration.tpl:6: {elseif $modelData['action'] == 'create'} */
 } elseif($var["modelData"]['action'] == 'create') { ?>
        $<?php
/* migration.tpl:7: {$name} */
 echo $var["name"]; ?> = $schema->createModel('<?php
/* migration.tpl:7: {$name} */
 echo $var["name"]; ?>', '', array(
<?php
/* migration.tpl:8: {foreach $modelData['properties'] as $propertyName => $property} */
  if($var["modelData"]['properties']) {  foreach($var["modelData"]['properties'] as $var["propertyName"] => $var["property"]) {  ?>
<?php
/* migration.tpl:9: {var $data = $property['data']} */
 $var["data"]=$var["property"]['data'] ?>
            '<?php
/* migration.tpl:10: {$propertyName} */
 echo $var["propertyName"]; ?>' => array(
                'comment' => '<?php
/* migration.tpl:11: {$data["comment"]} */
 echo $var["data"]["comment"]; ?>',
                'type' => '<?php
/* migration.tpl:12: {$data["type"]} */
 echo $var["data"]["type"]; ?>',
                'required' => <?php
/* migration.tpl:13: {$data['required'] ? "true" : "false"} */
 echo (empty($var["data"]['required']) ? "false" : "true"); ?>,
            ),
<?php
/* migration.tpl:15: {/foreach} */
   } } ?>
        ));
        $<?php
/* migration.tpl:17: {$name} */
 echo $var["name"]; ?>->setPrimaryKey(array(
<?php
/* migration.tpl:18: {foreach $modelData['pk'] as $field} */
  if($var["modelData"]['pk']) {  foreach($var["modelData"]['pk'] as $var["field"]) {  ?>
            '<?php
/* migration.tpl:19: {$field} */
 echo $var["field"]; ?>',
<?php
/* migration.tpl:20: {/foreach} */
   } } ?>
        ));
<?php
/* migration.tpl:22: {elseif is_null($modelData['action']) || $modelData['action'] == 'rename'} */
 } elseif(is_null($var["modelData"]['action']) || $var["modelData"]['action'] == 'rename') { ?>
        $<?php
/* migration.tpl:23: {$name} */
 echo $var["name"]; ?> = $schema->getModel('<?php
/* migration.tpl:23: {$name} */
 echo $var["name"]; ?>');
<?php
/* migration.tpl:24: {if $modelData['action'] == 'rename'} */
 if($var["modelData"]['action'] == 'rename') { ?>
        $schema->renameModel('<?php
/* migration.tpl:25: {$name} */
 echo $var["name"]; ?>', '<?php
/* migration.tpl:25: {$modelData["new_name"]} */
 echo $var["modelData"]["new_name"]; ?>');
<?php
/* migration.tpl:26: {/if} */
 } ?>
<?php
/* migration.tpl:27: {if $modelData['properties']?} */
 if(!empty($var["modelData"]['properties'])) { ?>
<?php
/* migration.tpl:28: {foreach $modelData['properties'] as $propertyName => $property} */
  if($var["modelData"]['properties']) {  foreach($var["modelData"]['properties'] as $var["propertyName"] => $var["property"]) {  ?>
<?php
/* migration.tpl:29: {if $property['action'] == 'add'} */
 if($var["property"]['action'] == 'add') { ?>
<?php
/* migration.tpl:30: {var $data = $property['data']} */
 $var["data"]=$var["property"]['data'] ?>
        $<?php
/* migration.tpl:31: {$name} */
 echo $var["name"]; ?>->addProperty('<?php
/* migration.tpl:31: {$propertyName} */
 echo $var["propertyName"]; ?>', array(
            'comment' => '<?php
/* migration.tpl:32: {$data["comment"]} */
 echo $var["data"]["comment"]; ?>',
            'type' => '<?php
/* migration.tpl:33: {$data["type"]} */
 echo $var["data"]["type"]; ?>',
            'required' => <?php
/* migration.tpl:34: {$data['required'] ? "true" : "false"} */
 echo (empty($var["data"]['required']) ? "false" : "true"); ?>,
        ));
<?php
/* migration.tpl:36: {elseif $property['action'] == 'remove'} */
 } elseif($var["property"]['action'] == 'remove') { ?>
        $<?php
/* migration.tpl:37: {$name} */
 echo $var["name"]; ?>->removeProperty('<?php
/* migration.tpl:37: {$propertyName} */
 echo $var["propertyName"]; ?>');
<?php
/* migration.tpl:38: {elseif $property['action'] == 'rename' || is_null($property['action'])} */
 } elseif($var["property"]['action'] == 'rename' || is_null($var["property"]['action'])) { ?>
<?php
/* migration.tpl:39: {if $property['action'] == 'rename'} */
 if($var["property"]['action'] == 'rename') { ?>
        $<?php
/* migration.tpl:40: {$name} */
 echo $var["name"]; ?>->renameProperty('<?php
/* migration.tpl:40: {$propertyName} */
 echo $var["propertyName"]; ?>', '<?php
/* migration.tpl:40: {$property["name"]} */
 echo $var["property"]["name"]; ?>');
<?php
/* migration.tpl:41: {/if} */
 } ?>
<?php
/* migration.tpl:42: {if $property['data']?} */
 if(!empty($var["property"]['data'])) { ?>
<?php
/* migration.tpl:43: {var $data = $property['data']} */
 $var["data"]=$var["property"]['data'] ?>
        $<?php
/* migration.tpl:44: {$name} */
 echo $var["name"]; ?>->alterProperty(array(
            'comment' => '<?php
/* migration.tpl:45: {$data["comment"]} */
 echo $var["data"]["comment"]; ?>',
            'type' => '<?php
/* migration.tpl:46: {$data["type"]} */
 echo $var["data"]["type"]; ?>',
            'required' => <?php
/* migration.tpl:47: {$data["required"] ? "true" : "false"} */
 echo (empty($var["data"]["required"]) ? "false" : "true"); ?>,
        ));
<?php
/* migration.tpl:49: {/if} */
 } ?>
<?php
/* migration.tpl:50: {/if} */
 } ?>
<?php
/* migration.tpl:51: {/foreach} */
   } } ?>
<?php
/* migration.tpl:52: {/if} */
 } ?>
<?php
/* migration.tpl:53: {if $modelData['pk']?} */
 if(!empty($var["modelData"]['pk'])) { ?>
        $<?php
/* migration.tpl:54: {$name} */
 echo $var["name"]; ?>->setPrimaryKey(array(
<?php
/* migration.tpl:55: {foreach $modelData['pk'] as $field} */
  if($var["modelData"]['pk']) {  foreach($var["modelData"]['pk'] as $var["field"]) {  ?>
            '<?php
/* migration.tpl:56: {$field} */
 echo $var["field"]; ?>',
<?php
/* migration.tpl:57: {/foreach} */
   } } ?>
        ));
<?php
/* migration.tpl:59: {/if} */
 } ?>
<?php
/* migration.tpl:60: {/if} */
 } ?>
<?php
/* migration.tpl:61: {/foreach} */
   } } ?><?php
}, array(
	'options' => 128,
	'provider' => false,
	'name' => 'migration.tpl',
	'base_name' => 'migration.tpl',
	'time' => 1402920597,
	'depends' => array (
  0 => 
  array (
    'migration.tpl' => 1402920597,
  ),
),
	'macros' => array(),

        ));
