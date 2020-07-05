<?php

namespace application\controllers;

use application\core\Controller;
use application\core\View;
use application\models\Main;

class MainController extends Controller {

    public function index($pageNumber, $columnSort, $sort) {
        $mainModel = new Main;
        $page = new View('main');
        $vars['tasks'] = $mainModel->getTasks($pageNumber, $columnSort, $sort);
        $vars['countPages'] = ceil($mainModel->getCountTasks() / 3);
        $this->checkAuthorization($vars);

        $page->renderPage('Главная', $vars);
    }

    public function logIn($login, $password) {
        $mainModel = new Main;
        $users = $mainModel->getUsers();

        foreach ($users as $user) {
            if ($user['login'] == $login && $user['password'] == md5($password)) {
                $_SESSION['user']['role'] = 'admin';
                $_SESSION['isAuthorized'] = true;

                return true;
            }
        }
        return false;
    }

    public function logOut() {
        $_SESSION['user']['role'] = 'guest';
        $_SESSION['isAuthorized'] = false;
    }

    public function addTask($vars) {
        $mainModel = new Main;
        return $mainModel->insertTask($vars);
    }

    public function checkAuthorization(&$vars) {
        if (isset($_SESSION['user']) && isset($_SESSION['user']['role'])) {
            $vars['user']['role'] = ($_SESSION['user']['role'] == 'admin') ? 'admin' : 'guest';
        } else {
            $vars['user']['role'] = 'guest';
        }
        if (isset($_SESSION['isAuthorized']) && $_SESSION['isAuthorized'] === true) {
            $vars['isAuthorized'] = true;
        } else {
            $vars['isAuthorized'] = false;
        }
    }

    public function changeTask($vars, $isCompleted, $text) {
        if($_SESSION['user']['role'] != 'admin') {
            exit(json_encode(['status' => false]));
        }
        $mainModel = new Main;

        if ($text) {
            $vars['text'] = $text;
            $vars['isAdminEdited'] = true;

            return $mainModel->changeTextTask($vars);
        } else {
            $vars['isCompleted'] = $isCompleted;

            return $mainModel->changeStatusTask($vars);
        }
    }

}