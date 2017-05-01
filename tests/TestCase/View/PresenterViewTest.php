<?php
namespace EntityPresenter\Test\TestCase\View;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\ORM\Entity;
use Cake\TestSuite\TestCase;
use EntityPresenter\Presentertrait;
use TestApp\Model\Entity\Article;
use TestApp\Model\Entity\User;

/**
 * PresenterViewTest
 */
class PresenterViewTest extends TestCase
{

    use Presentertrait;

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * testPresent
     *
     * @return void
     */
    public function testPresent()
    {
        $request = new ServerRequest();
        $response = new Response();
        $controller = new Controller($request, $response);
        $user = new User();
        $user->setSource('Users');

        $controller->set([
            'user' => $user,
            '_present' => 'user'
        ]);

        $view = $controller->createView();
        $view->render(false);

        $this->assertInstanceOf('\TestApp\View\Presenter\User', $view->get('user'));
    }

    /**
     * testPresentAll
     *
     * @return void
     */
    public function testPresentAll()
    {
        $request = new ServerRequest();
        $response = new Response();
        $controller = new Controller($request, $response);
        $user = new User();
        $article = new Article();
        $user->setSource('Users');
        $article->setSource('Articles');

        $controller->set([
            'user' => $user,
            'article' => $article,
            '_present' => true
        ]);

        $view = $controller->createView();
        $view->render(false);

        $this->assertInstanceOf('\TestApp\View\Presenter\User', $view->get('user'));
        $this->assertInstanceOf('\TestApp\View\Presenter\Article', $view->get('article'));

        $request = new ServerRequest();
        $response = new Response();
        $controller = new Controller($request, $response);
        $user = new User();
        $article = new Article();
        $user->setSource('Users');
        $article->setSource('Articles');

        $controller->set([
            'user' => $user,
            'article' => $article,
            '_present' => [
                'user',
                'article'
            ]
        ]);

        $view = $controller->createView();
        $view->render(false);

        $this->assertInstanceOf('\TestApp\View\Presenter\User', $view->get('user'));
        $this->assertInstanceOf('\TestApp\View\Presenter\Article', $view->get('article'));
    }

    /**
     * testPresentAssoc
     *
     * @return void
     */
    public function testPresentAssoc()
    {
        $request = new ServerRequest();
        $response = new Response();
        $controller = new Controller($request, $response);
        $user = new User();
        $article = new Article();
        $user->setSource('Users');
        $article->setSource('Articles');
        $user->articles = $article;

        $controller->set([
            'user' => $user,
            '_present' => true
        ]);

        $view = $controller->createView();
        $view->render(false);

        $user = $view->get('user');
        $articles = $user->articles;

        $this->assertInstanceOf('\TestApp\View\Presenter\User', $view->get('user'));
        $this->assertInstanceOf('\TestApp\View\Presenter\Article', $articles);
    }

    /**
     * testPresentMultipleAssoc
     *
     * @return void
     */
    public function testPresentMultipleAssoc()
    {
        $request = new ServerRequest();
        $response = new Response();
        $controller = new Controller($request, $response);
        $user = new User();
        $article = new Article();
        $user->setSource('Users');
        $article->setSource('Articles');

        $user->articles = [
            $article
        ];

        $controller->set([
            'user' => $user,
            '_present' => true
        ]);

        $view = $controller->createView();
        $view->render(false);

        $user = $view->get('user');
        $articles = $user->articles;

        $this->assertInstanceOf('\TestApp\View\Presenter\User', $view->get('user'));
        $this->assertInstanceOf('\TestApp\View\Presenter\Article', $articles[0]);
    }

    /**
     * testPresentSubset
     *
     * @return void
     */
    public function testPresentSubset()
    {
        $request = new ServerRequest();
        $response = new Response();
        $controller = new Controller($request, $response);
        $user = new User();
        $article = new Article();
        $user->setSource('Users');
        $article->setSource('Articles');

        $controller->set([
            'user' => $user,
            'article' => $article,
            '_present' => 'user'
        ]);

        $view = $controller->createView();
        $view->render(false);

        $this->assertInstanceOf('\TestApp\View\Presenter\User', $view->get('user'));
        $this->assertInstanceOf('\TestApp\Model\Entity\Article', $view->get('article'));
    }

    /**
     * testPresentDifferent
     *
     * @return void
     */
    public function testPresentDifferent()
    {
        $user = new User();
        $presentedUser = $this->present($user, 'Article');
        $this->assertInstanceOf('\TestApp\View\Presenter\Article', $presentedUser);
    }

    /**
     * testPresenterMethods
     *
     * @return void
     */
    public function testPresenterMethods()
    {
        $user = new User();
        $user->setSource('Users');
        $user->set('firstname', 'Foo');
        $user->set('lastname', 'Bar');
        $presentedUser = $this->present($user);
        $this->assertSame('<b>Foo Bar</b>', $presentedUser->fullName());

        $article = new Article();
        $article->setSource('Articles');
        $article->set('title', 'Foo Bar');
        $presentedArticle = $this->present($article);
        $this->assertSame('<i>Foo Bar</i>', $presentedArticle->italicTitle());
    }

    /**
     * testMissingPresenterException
     *
     * @expectedException EntityPresenter\MissingPresenterException
     * @expectedExceptionMessage Entity presenter "Foo" is missing.
     * @return void
     */
    public function testMissingPresenterException()
    {
        $request = new ServerRequest();
        $response = new Response();
        $controller = new Controller($request, $response);
        $entity = new Entity();
        $entity->setSource('Foo');

        $controller->set([
            'entity' => $entity,
            '_present' => true
        ]);

        $view = $controller->createView();
        $view->render(false);
    }
}
