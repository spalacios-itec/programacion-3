<?php

namespace App\Controllers;

use App\Models\User;
use Zend\Diactoros\ServerRequest;

class UserController extends BaseController {

    /**
     * @return \Zend\Diactoros\Response\HtmlResponse
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function indexAction(){
        $users = User::all();
        return $this->renderHTML('Users/index.twig',
            [
                'users' => $users->toArray(),
                'title' => 'Listado de Usuarios'
            ]
        );
    }

    /**
     * @param ServerRequest $request
     * @return \Zend\Diactoros\Response\HtmlResponse
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function createAction(ServerRequest $request)
    {
        if(!empty($_POST)){
            $data = $request->getParsedBody();

            $user = new User();
            $user->firstname = $data['firstname'];
            $user->lastname = $data['lastname'];
            $user->email = $data['email'];
            $user->password = md5($data['password']);
            $user->save();
        }

        return isset($user) ? $this->indexAction() :
            $this->renderHTML('Users/new.twig',
            [
                'title' => 'Nuevo usuario'
            ]
        );
    }
}