<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
  public function add(Request $request)
  {
    $res = [
      "status" => false,
      "message" => [],
      "token" => '',
    ];
    $token = $request->header("Authorization");
    $token = str_replace('Bearer ', '', $token);
    if (!$token) {
      $res["message"][] = "Login failed";
      return response()->json($res)->setStatusCode(404);
    }

    $user = User::all()
      ->where('remember_token', $token)
      ->first();
    if (!$user) {
      $res["message"][] = "Login failed";
      return response()->json($res)->setStatusCode(404);
    }
    $fileName = $request->file("file")->getClientOriginalName();
    $fileName = str_replace("public/", "", $fileName);

    $path = $request->file('file')->storeAs(
      'public',
      $fileName
    );
    $file = new File();
    $file->user_id = $user->user_id;
    $file->file = $path;
    $file->save();

    $res = [
      "status" => true,
      "message" => "success",
      "token" => $user->remember_token,
    ];
    return response()->json($res)->setStatusCode(200);
  }
  public function change($file_id, Request $request)
  {
    $res = [
      "status" => false,
      "message" => [],
      "token" => '',
    ];
    $token = $request->header("Authorization");
    $token = str_replace('Bearer ', '', $token);
    if (!$token) {
      $res["message"][] = "Login failed";
      return response()->json($res)->setStatusCode(404);
    }

    $user = User::all()
      ->where('remember_token', $token)
      ->first();
    if (!$user) {
      $res["message"][] = "Login failed";
      return response()->json($res)->setStatusCode(404);
    }
    $file = File::all()
      ->where('user_id', $user->user_id)
      ->where('file_id', $file_id)
      ->first();
    if (!$user) {
      $res["message"][] = "Login failed";
      return response()->json($res)->setStatusCode(404);
    }
    $file = storage_path('app/'. $file->file);
    return response()->file($file)->setStatusCode(200);


    $res = [
      "status" => true,
      "message" => "success",
      "token" => $user->remember_token,
    ];
    return response()->json($res)->setStatusCode(200);
  }
}
