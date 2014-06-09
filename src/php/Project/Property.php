<?php
namespace Project;

class Property 
{
    /**
     * @var String
     */
    protected $name;

    /**
     * @var String
     */
    protected $comment;

    /**
     * @var String
     */
    protected $type;

    /**
     * @var boolean
     */
    protected $primary;

    /**
     * @var boolean
     */
    protected $notNull;

    /**
     * @var boolean
     */
    protected $foreign;

    public function __construct($config)
    {
        $this->name = $config['name'];
        $this->comment = $config['comment'];
        $this->type = $config['type'];
        $this->primary = !empty($config['primary']);
        $this->notNull = !empty($config['notNull']);
        $this->foreign = !empty($config['foreign']);
    }

    /**
     * @return String
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param String $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param String $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return boolean
     */
    public function isForeign()
    {
        return $this->foreign;
    }

    /**
     * @param boolean $foreign
     */
    public function setForeign($foreign)
    {
        $this->foreign = $foreign;
    }

    /**
     * @return boolean
     */
    public function isNotNull()
    {
        return $this->notNull;
    }

    /**
     * @param boolean $notNull
     */
    public function setNotNull($notNull)
    {
        $this->notNull = $notNull;
    }

    /**
     * @return boolean
     */
    public function isPrimary()
    {
        return $this->primary;
    }

    /**
     * @param boolean $primary
     */
    public function setPrimary($primary)
    {
        $this->primary = $primary;
    }

    /**
     * @return String
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param String $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    public function asArray()
    {
        return array(
            'name' => $this->name,
            'comment' => $this->comment,
            'type' => $this->type,
            'primary' => $this->primary,
            'notNull' => $this->notNull,
            'foreign' => $this->foreign,
        );
    }
} 