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
     * @var \Project\Model[]
     */
    protected $originalNameLinks = array();

    /**
     * @var \Project\Model[]
     */
    protected $newModels = array();

    /**
     * @var array
     */
    protected $differences = array();

    public function __construct($config)
    {
        /**
         * @var \Project\Schema $to
         *
         */
        $to = $config['to'];

        foreach($to->getModels() as $model) {
            $originalName = $model->getOriginalName();
            if ($originalName) {
                $this->originalNameLinks[$originalName] = $model;
            } else {
                $this->newModels[$model->getName()] = $model;
            }
        }
    }

    public function getDiff()
    {
        foreach($this->from->getModels() as $name => $fromModel) {
            if (!isset($this->originalNameLinks[$fromModel->getName()])) {
                var_dump($fromModel->getName());
                $this->differences[$fromModel->getName()] = array(
                    'action' => 'delete'
                );
            } else {
                $toModel = $this->originalNameLinks[$fromModel->getName()];
                $difference = array(
                    'action' => null,
                );
                if ($toModel->getName() != $fromModel->getName()) {
                    $difference['action'] = 'rename';
                    $difference['new_name'] = $toModel->getName();
                }
                $this->getModelDifference($fromModel, $toModel, $difference);
                if (isset($difference['properties']) || isset($difference['pk']) || !is_null($difference['action'])) {
                    $this->differences[$fromModel->getName()] = $difference;
                }
            }
        }
        foreach($this->newModels as $name => $model) {
            $difference = array(
                'action' => 'create',
                'pk' => $model->getPrimaryKey(),
                'properties' => array(),
            );
            foreach ($model->getProperties() as $property) {
                $difference['properties'][$property->getName()] = array(
                    'action' => 'add',
                    'data' => array(
                        'type' => $property->getType(),
                        'comment' => $property->getComment(),
                        'required' => $property->isNotNull(),
                    )
                );
            }
            $this->differences[$model->getName()] = $difference;

        }
        return array('models' => $this->differences);
    }

    protected function getModelDifference(\Project\Model $from, \Project\Model $to, &$difference)
    {
        /**
         * @var \Project\Property[] $originalNamesLinks
         */
        $originalNamesLinks = array();
        $newProperties = array();
        foreach($to->getProperties() as $property) {
            if ($property->getOriginalName()) {
                $originalNamesLinks[$property->getOriginalName()] = $property;
            } else {
                $newProperties[$property->getName()] = $property;
            }
        }
        foreach($from->getProperties() as $fromProperty) {
            $toProperty = isset($originalNamesLinks[$fromProperty->getName()]) ? $originalNamesLinks[$fromProperty->getName()] : null;
            if ($toProperty) {
                if ($toProperty->getName() != $fromProperty->getName()) {
                    $difference['properties'][$fromProperty->getName()] = array(
                        'action' => 'rename',
                        'name' => $toProperty->getName(),
                    );
                }
                $this->getPropertyDifference($fromProperty, $toProperty, $difference);
            } else {
                // Property removed
                $difference['properties'][$fromProperty->getName()] = array(
                    'action' => 'remove'
                );
            }
        }
        foreach($newProperties as $property) {
            $difference['properties'][$property->getName()] = array(
                'action' => 'add',
                'data' => array(
                    'type' => $property->getType(),
                    'comment' => $property->getComment(),
                    'required' => $property->isNotNull(),
                ),
            );
        }
        // check PK
    }

    protected function getPropertyDifference(\Project\Property $from, \Project\Property $to, &$difference)
    {
        $fields = array('comment', 'type', 'notNull');
        foreach($fields as $field) {
            if ($from->get($field) != $to->get($field)) {
                if (!isset($difference['properties'][$from->getName()])) {
                    $difference['properties'][$from->getName()] = array(
                        'action' => null,
                        'data' => array()
                    );
                } else if (!isset($difference['properties'][$from->getName()]['data'])) {
                    $difference['properties'][$from->getName()]['data'] = array();
                }
                $difference['properties'][$from->getName()]['data'][$field] = $to->get($field);
            }
        }
    }
}