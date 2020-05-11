<?php

namespace App\Controllers;

use App\Models\User;

class UserController extends BaseController {

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function indexAction(){
        $users = User::all();
        return $this->renderHTML('user_index.twig',
            [
                'users' => $users->toArray(),
                'title' => 'Listado de Usuarios'
            ]
        );
    }

    /**
     * @param string $username
     */
    public function getAction(string $username){
        $user = User::query()->where('email', $username);

//        foreach ($users as $user){
//            echo "$user->firstname $user->lastname ($user->email)";
//            echo "<br>";
//        }
    }

    public function addAction(){
        echo getenv('UPLOAD_PRODUCT_PATH').'/user_profile.png';
    }

    public function editAction(){

    }

    // user/3/delete
    // deleteUser.php?id=3
    public function deleteAction(){

    }
}