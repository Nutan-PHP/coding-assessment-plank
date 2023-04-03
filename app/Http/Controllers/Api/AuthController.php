<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use Validator, Auth, Hash, DB, Exception;

class AuthController extends BaseController{

    public function registerUser(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'confirmPassword' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(),422);       
        }

        try {
            DB::beginTransaction();
            $response = [];
            $statusCode = 200;
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            // insert data into users table
            $userObj = User::create($input);
            if($userObj && count(array($userObj))){
                $response['token'] =  $userObj->createToken('My Token')->plainTextToken;
                $response['user'] =  [
                    'id' => $userObj->id,
                    'name' => $userObj->name,
                    'email' => $userObj->email
                ];
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollback();
            $statusCode = 500;
            $errorMessages = $e->getMessage();
            $error = 'There is some error while processing your request. Please try after some time.'; 
        }finally{
            if($statusCode != 200)
                return $this->sendError($error, $errorMessages, $statusCode);
            else
                return $this->sendResponse($response, 'User register successfully.');
        }
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(),422);       
        }
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $userObj = Auth::user();
            $response['token'] =  $userObj->createToken('My Token')->plainTextToken;
            $response['user'] =  [
                'id' => $userObj->id,
                'name' => $userObj->name,
                'email' => $userObj->email
            ];
            return $this->sendResponse($response, 'User login successfully.');
        } else { 
            return $this->sendError('Unauthenticated.', ['error'=>'Unauthenticated user login']);
        }
    }
}