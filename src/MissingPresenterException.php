<?php
namespace EntityPresenter;

use Cake\Core\Exception\Exception;

/**
 * Used when a presenter was not found.
 */
class MissingPresenterException extends Exception
{

    protected $_messageTemplate = 'Entity presenter "%s" is missing.';
}
