<?php
namespace EntityPresenter\View;

use Cake\DataSource\EntityInterface;
use Cake\Event\EventManager;
use Cake\View\View;
use EntityPresenter\PresenterTrait;

class PresenterView extends View
{

    use PresenterTrait;

    /**
     * List of special view vars.
     *
     * @var array
     */
    protected $_specialVars = ['_present'];

    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->_setupListener();
    }

    /**
     * Setup listener for View.beforeRender
     *
     * @return void
     */
    protected function _setupListener()
    {
        EventManager::instance()->on(
            'View.beforeLayout',
            function () {
                $this->_instantiatePresenters();
            }
        );
    }

    /**
     * Decorate view vars
     *
     * @return void
     */
    protected function _instantiatePresenters()
    {
        $present = false;
        if (isset($this->viewVars['_present'])) {
            $present = $this->viewVars['_present'];
        }

        if ($present !== false) {
            $toBePresented = $this->_dataToPresent($present);

            foreach ($toBePresented as $name => $presentable) {
                if ($presentable instanceof EntityInterface) {
                    $this->viewVars[$name] = $this->present($this->viewVars[$name]);
                }
            }
        }
    }

    /**
     * Returns data to be presented.
     *
     * @param array|string|bool $present The name(s) of the view variable(s) that
     *   need(s) to be presented. If true all available view variables will be used.
     * @return array The data to present.
     */
    protected function _dataToPresent($present = true)
    {
        if ($present === true) {
            $data = array_diff_key(
                $this->viewVars,
                array_flip($this->_specialVars)
            );

            if (empty($data)) {
                return null;
            }

            return $data;
        }

        if (is_array($present)) {
            $data = [];
            foreach ($present as $alias => $key) {
                if (is_numeric($alias)) {
                    $alias = $key;
                }
                if (array_key_exists($key, $this->viewVars)) {
                    $data[$alias] = $this->viewVars[$key];
                }
            }

            return !empty($data) ? $data : null;
        }

        return isset($this->viewVars[$present]) ? [$present => $this->viewVars[$present]] : null;
    }
}
