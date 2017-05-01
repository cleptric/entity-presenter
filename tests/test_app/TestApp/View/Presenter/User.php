<?php
namespace TestApp\View\Presenter;

use EntityPresenter\Presenter;

class User extends Presenter
{
    public function fullName()
    {
        return '<b>' . $this->firstname . ' ' . $this->lastname . '</b>';
    }
}
