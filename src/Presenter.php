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
     * @param \Cake\DataSource\EntityInterface $entity The presented entity
     */
    public function __construct($entity)
    {
        $this->_entity = $entity;
    }

    /**
     * Magic getter to call presenter methods or
     * access properties that have been set in the entity
     *
     * @param string $property Name of the property to access
     * @return mixed
     */
    public function __get($property)
    {
        if (method_exists($this, $property)) {
            return $this->{$property}();
        }

        return $this->_entity->get($property);
    }

    /**
     * Overload entity functions
     *
     * @param string $method the method to call
     * @param array $arguments list of arguments for the method to call
     * @return mixed
     */
    public function __call($name, $args)
    {
        return call_user_func_array([$this->_entity, $name], $args);
    }
}
