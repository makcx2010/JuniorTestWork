<?php

namespace application\core;

use application\controllers\MainController;
use application\core\View;

class Router
{

    public function __construct()
    {

    }

    public function run()
    {
        if ($_SERVER['REQUEST_URI'] == '/') {
            $controller = new MainController;
            $page = (isset($_POST['page']) && $_POST['page']) ? $_POST['page'] : 1;
            $column = (isset($_POST['column']) && $_POST['column']) ? $_POST['column'] : 'id';
            $typeSort = (isset($_POST['typeSort']) && $_POST['typeSort']) ? $_POST['typeSort'] : 'ASC';

            $controller->index($page, $column, $typeSort);
        } elseif ($_SERVER['REQUEST_URI'] == '/add/task' && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $controller = new MainController;
            $vars['name'] = $_POST['name'];
            $vars['email'] = $_POST['email'];
            $vars['text'] = $_POST['text'];

            $isSuccess = $controller->addTask($vars);

            exit(json_encode(['isSuccess' => $isSuccess]));
        } elseif ($_SERVER['REQUEST_URI'] == '/login' && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $controller = new MainController;
            $isAuthorized = $controller->logIn($_POST['login'], $_POST['password']);

            exit(json_encode(['isAuthorized' => $isAuthorized]));
        } elseif ($_SERVER['REQUEST_URI'] == '/logout' && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $controller = new MainController;
            $controller->logOut();

            exit(json_encode(['logout' => true]));
        } elseif ($_SERVER['REQUEST_URI'] == '/task/change' && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $vars['id'] = $_POST['id'];
            $isCompleted = (isset($_POST['isCompleted']) && $_POST['isCompleted'] == 'true') ? 1 : 0;
            $text = (isset($_POST['text']) && $_POST['text']) ? $_POST['text'] : 0;
            $controller = new MainController;

            $status = $controller->changeTask($vars, $isCompleted, $text);

            exit(json_encode(['status' => $status]));
        } elseif ($_SERVER['REQUEST_URI'] != '/404' && $_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->redirect('/404');
        } elseif ($_SERVER['REQUEST_URI'] == '/404') {
            $page = new View('404');
            $page->renderPage('Страница не найдена');
        }
    }

    public function redirect($url)
    {
        header('location: ' . $url);
        exit;
    }

}