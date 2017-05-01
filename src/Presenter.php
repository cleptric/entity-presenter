<?php
namespace EntityPresenter;

use Cake\DataSource\EntityInterface;
use EntityPresenter\PresenterTrait;

abstract class Presenter
{

    use PresenterTrait;

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
     * access properties that have been set in the entity.
     *
     * If the accessed property is an entity, it's tried to present it as well.
     *
     * @param string $property Name of the property to access
     * @return mixed
     */
    public function __get($property)
    {
        if (method_exists($this, $property)) {
            return $this->{$property}();
        }
        $value = $this->_entity->get($property);

        if (is_array($value)) {
            foreach ($value as &$element) {
                if ($element instanceof EntityInterface) {
                    try {
                        $element = $this->present($element);
                    } catch (MissingPresenterException $e) {
                        // fail silently
                    }
                }
            }
        } elseif ($value instanceof EntityInterface) {
            try {
                $value = $this->present($value);
            } catch (MissingPresenterException $e) {
                // fail silently
            }
        }

        return $value;
    }
}
