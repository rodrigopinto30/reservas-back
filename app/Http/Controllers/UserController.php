<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller {
    public function index():JsonResponse{
        $usuarios = User::all();
        return response()->json($usuarios, 200);
    }

    public function show($id): JsonResponse {
        $usuario = User::where('id', $id)->first();
        return response()->json($usuario, 200);
    }
}
