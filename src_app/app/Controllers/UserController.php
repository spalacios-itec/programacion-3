<?php

namespace App\Controllers;

use App\Models\User;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
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
        if($request->getMethod() == 'POST'){
            $data = $request->getParsedBody();

            $userValidator = Validator::key('firstname',Validator::stringType()->notEmpty())
                ->key('lastname',Validator::stringType()->notEmpty())
                ->key('email', Validator::email()->notEmpty())
                ->key('password', Validator::stringType()->notEmpty());

            try{
                $userValidator->assert($data);

                $user = new User();
                $user->firstname = $data['firstname'];
                $user->lastname = $data['lastname'];
                $user->email = $data['email'];
                $user->password = md5($data['password']);
                $user->save();

            }catch (NestedValidationException $e){
                $errors = $e->getMessages();
            }
        }

        return isset($user) ? $this->indexAction() :
            $this->renderHTML('Users/new.twig',
            [
                'title'  => 'Nuevo usuario',
                'user'   => ($data) ? $data : null,
                'errors' => ($errors) ? $errors : null
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
    public function viewAction(ServerRequest $request)
    {
        $id = (int) $request->getAttribute('id');

        $user = User::query()->find($id);
        $userData = $user->toArray();

        return $this->renderHTML('Users/show.twig',
                [
                    'title'  => 'Ver usuario',
                    'user'   => $userData
                ]
            );
    }
}