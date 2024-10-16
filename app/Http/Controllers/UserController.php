<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(): JsonResponse
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    public function show($id): JsonResponse
    {
        $usuario = User::where('id', $id)->first();
        return response()->json($usuario, 200);
    }

    public function lastUser(): JsonResponse
    {
        $usuarios = User::latest()->take(5)->get();
        return response()->json($usuarios, 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'lastName' => 'required|string|max:50',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
            'rol' => 'required|string|in:admin,customer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validate(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'user' => $user
        ], 201);
    }

    public function update(Request $request)
    {
        try {
            $user = User::findOrFail($request->input('id'));

            $validator = Validator::make($request->all(), [
                'name' => 'nullable|string|max:50',
                'lastName' => 'nullable|string|max:50',
                'email' => "nullable|string|email|max:100|unique:users,email,{$user->id}",
                'password' => 'nullable|string|min:6|confirmed',
                'rol' => 'nullable|string|in:admin,customer',
            ]);

            if ($validator->fails()) return response()->json($validator->errors()->toJson(), 400);

            $user->name = $request->input('name', $user->name);
            $user->lastName = $request->input('lastName', $user->lastName);
            $user->email = $request->input('email', $user->email);

            if ($request->filled('password')) {
                $user->password = bcrypt($request->input('password'));
            }

            $user->rol = $request->input('rol', $user->rol);

            $user->save();

            return response()->json([
                'message' => 'Usuario actualizado exitosamente',
                'user' => $user
            ],);
        } catch (\Throwable $error) {
            return response()->json([
                'message' => 'No se pudo actualizar el usuario',
                'error' => $error->getMessage()
            ]);
        }
    }
}
