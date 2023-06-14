<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;

class RegisterController extends BaseController
{
    //
    public function register(Request $request)
    {
        // error message display for duplicated registration
        $messages = [
            'email.unique' => 'The email address is already taken.',
        ];
        // validate all user registration
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => ['required', 'email', 'string', Rule::unique('users')],
            'password' => 'required',
            'c_password' => 'required|same:password',
        ], $messages);
        // function that triggers response for duplicated email addresses
        $this->sendError('Validation Error.', $messages);
        // function that triggers response for registration errors
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        // encrypt password, get token and user name, save data to database
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;
        // function that triggers response for registration success
        return $this->sendResponse($success, 'User register successfully.');
    }

    public function login(Request $request)
    {
        // set received parameters
        $credentials = ['email' => $request->email, 'password' => $request->password];
        // authenticate user
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // create a new token and get user information
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['name'] =  $user->name;
            // return response
            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }
}
