<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            // return $this->sendError('Validation Error.', $validator->errors());
            return response()->json([
                'Msg'=> $validator->errors()->first(),
                ],Response::HTTP_BAD_REQUEST);
        }

        $input = $request->all();
        // $input['password'] = bcrypt($input['password']);
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        // return $this->sendResponse($success, 'User register successfully.');
        return response()->json([
            'Msg'=> 'User  Register Successfully',
            'data'=>$success,
        ],Response::HTTP_OK);
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')-> accessToken;
            $success['name'] =  $user->name;

            // return $this->sendResponse($success, 'User login successfully.');
            return response()->json([
                'Msg'=>'User Login Successsfully',
                'data'=>$success,
                ],Response::HTTP_OK);
        }
        else{
            // return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
            return response()->json([
                'Msg'=>'Login Failed..Try Again!!',
                ],Response::HTTP_BAD_REQUEST);
        }
    }
}
