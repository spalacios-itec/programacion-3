<?php

namespace App\Controllers\Api;

use App\Models\User;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;

class UserController {

    /**
     * @return JsonResponse
     */
    public function indexAction(){
        $users = User::select('id', 'firstname', 'lastname', 'email')->get();
        $response = ($users) ? $users->toArray() : null;
        return new JsonResponse($response, ($response) ? 200 : 404);
    }

    /**
     * @param ServerRequest $request
     * @return JsonResponse
     */
    public function createAction(ServerRequest $request)
    {
        if($request->getMethod() == 'POST'){
            $data = $request->getParsedBody();
            return new JsonResponse($data, 200);

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

                return new JsonResponse(true, 201);
            }catch (NestedValidationException $e){
                $errors = $e->getMessages();
                return new JsonResponse($errors, 400);
            }
        }
    }

    /**
     * @param ServerRequest $request
     * @return JsonResponse
     */
    public function viewAction(ServerRequest $request)
    {
        $id = (int) $request->getAttribute('id');
        $user = User::query()->find($id);

        return ($user) ? new JsonResponse($user->toArray(), 200) : new JsonResponse(null, 404);
    }
}