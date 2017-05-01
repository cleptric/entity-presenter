<?php
namespace TestApp\View\Presenter;

use EntityPresenter\Presenter;

class Article extends Presenter
{
    public function italicTitle()
    {
        return '<i>' . $this->title . '</i>';
    }
}
