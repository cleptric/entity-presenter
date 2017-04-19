<?php
namespace EntityPresenter;

use Cake\Core\App;
use Cake\DataSource\EntityInterface;
use Cake\Utility\Inflector;

trait PresenterTrait
{
    /**
     * Create a new presenter instance
     *
     * @param \Cake\DataSource\EntityInterface $entity An entity which should be decorated
     * @param string|null $presenter Optional presenter name
     * @return Presenter
     */
    public function present(EntityInterface $entity, $presenter = null)
    {
        if ($presenter === null) {
            $presenterName = Inflector::singularize($entity->getSource());
        } else {
            $presenterName = $presenter;
        }
        $presenter = App::className($presenterName, 'View\Presenter', 'Presenter');

        return new $presenter($entity);
    }
}
