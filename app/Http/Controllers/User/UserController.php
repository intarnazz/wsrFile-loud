<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  public function reg(Request $request)
  {
    $res = [
      "status" => false,
      "message" => [],
      "token" => '',
    ];
    $user = new User();
    $validator = Validator::make($request->all(), [
      "first_name" => ["required", "string", ""],
    ]);
    if ($validator->fails()) {
      $res["message"][] = "Неверное имя";
      return response()->json($res)->setStatusCode(401);
    }
    $validator = Validator::make($request->all(), [
      "last_name" => ["required", "string", ""],
    ]);
    if ($validator->fails()) {
      $res["message"][] = "Неверная фамилия";
      return response()->json($res)->setStatusCode(401);
    }
    $validator = Validator::make($request->all(), [
      "password" => ["required", "string", ""],
    ]);
    if ($validator->fails()) {
      $res["message"][] = "Неверный пароль";
      return response()->json($res)->setStatusCode(401);
    }
    $validator = Validator::make($request->all(), [
      "email" => ["required", "email", ""],
    ]);
    if ($validator->fails()) {
      $res["message"][] = "Неверный email";
      return response()->json($res)->setStatusCode(401);
    }
    if (!$res["message"]) {
      $user->first_name = $request->first_name;
      $user->last_name = $request->last_name;
      $user->password = $request->password;
      $user->email = $request->email;
      $user->save();
      $user->remember_token = $user->createToken("remember_token")->plainTextToken;
      $user->save();
      $res = [
        "status" => true,
        "message" => "success",
        "token" => $user->remember_token,
      ];
      return response()->json($res)->setStatusCode(200);
    }
    return response()->json($res)->setStatusCode(400);
  }
  public function login(Request $request)
  {
    $res = [
      "status" => false,
      "message" => [],
      "token" => '',
    ];
    // $token = $request->header('Authorization');
    // if (!$token) {
    //   $res["message"][] = "Неверный token";
    //   return response()->json($res)->setStatusCode(400);
    // }
    // $token = str_replace('Bearer ', '', $token);
    $user = User::all()
      ->where('email', $request->email)
      ->where('password', $request->password)
      ->first();
    if (!$user) {
      $res["message"][] = "User не найден";
      return response()->json($res)->setStatusCode(404);
    }

    if (!$res["message"]) {
      $user->remember_token = $user->createToken("remember_token")->plainTextToken;
      $user->save();

      $res = [
        "status" => true,
        "message" => "success",
        "token" => $user->remember_token,
      ];
      return response()->json($res)->setStatusCode(200);
    }
    return response()->json($res)->setStatusCode(400);
  }
}
