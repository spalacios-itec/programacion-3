<?php

namespace App\Controllers;

use App\Models\User;

class UserController {

    /**
     *
     */
    public function indexAction(){
        $users = User::all();

        foreach ($users as $user){
            echo "$user->firstname $user->lastname ($user->email)";
            echo "<br>";
        }
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