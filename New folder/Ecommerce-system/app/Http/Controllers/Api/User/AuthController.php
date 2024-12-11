<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }
        $request->merge(['password' => bcrypt($request->password)]);
        $user = User::create($request->all());
        $token = $user->createToken('userToken');
        $user->save();
        return responseJson(1, 'successfully created', [
            'user' => $user, 'token' => $token->plainTextToken
        ]);
    }

    public function login(Request $request)
    {
//        $validator = validator()->make($request->all(), [
//            'email' => 'required|email',
//            'password' => 'required',
//        ]);
//        if ($validator->fails()) {
//            return responseJson(0, $validator->errors()->first(), $validator->errors());
//        }
//
//        $user = User::where('email', $request->email)->first();
//        if ($user) {
//            if (Hash::check($request->password, $user->password)) {
//                $token = $user->createToken('userToken');
//                return responseJson(1, 'successfully login', [
//                    'user' => $user, 'api_token' => $token->plainTextToken]);
//            } else {
//                return responseJson(0, 'wrong data');
//
//            }
//        }
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (!Auth::attempt($credentials)) {
            return responseJson(0, 'Invalid credentials');
        }
        $user = Auth::user();
        $token = $user->createToken('userToken');
        return responseJson(1, 'successfully login',
            ['user' => $user, 'api_token' => $token->plainTextToken]);

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return responseJson(1, 'تم تسجيل الخروج بنجاح');
    }


}
