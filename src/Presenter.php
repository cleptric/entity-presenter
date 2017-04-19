<?php
namespace EntityPresenter;

abstract class Presenter
{

    /**
     * Holds the instance of the entity
     *
     * @var \Cake\DataSource\EntityInterface
     */
    protected $_entity;

    /**
     * Constructor
     *
     * @param \Cake\DataSource\EntityInterface $entity
     */
    public function  __construct($entity)
    {
        $this->_entity = $entity;
    }

    /**
     * Magic getter to access properties that have been set in this entity
     *
     * @param string $property Name of the property to access
     * @return mixed
     */
    public function __get($property)
    {
        return $this->_entity->get($property);
    }

    /**
     * Overload entity functions
     *
     * @param string $name
     * @param string $args
     * @return mixed
     */
    public function __call($name, $args)
    {
        return call_user_func_array([$this->_entity, $name], $args);
    }
}
