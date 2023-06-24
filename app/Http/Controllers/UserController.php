<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller
{
    public function register(Request $request)
    {
        // validate the request data

        $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'address' => 'nullable|string'
        ]);

        try {
            //code...

            // create the new user in the database
            $user = User::create([
                'status' => 'active',
                'name' => $request->name,
                'cpf' => $request->cpf,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'address' => $request->address ?? null
            ]);

            // log the user in
            Auth::login($user);
            $success = true;
            $message = 'Usuário registrado e logado com sucesso';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response, 200);
        } catch (QueryException $error) {
            $success = false;
            $message = $error;
        }
        $response = [
            'success' => $success,
            'message' => $message,
        ];
        return response()->json($response, 500);


        // redirect the user to the home page with a success message
        //return redirect()->route('home')->with('success', 'Registration successful!');
    }

    public function login(Request $request)
    {
        //validate the request data
        try {
            //code...
            $request->validate([
                'email' => 'required|string|email|max:255',
                'password' => 'required|string',
            ]);

            //attempt to authenticate the user
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $user = User::where('email', $credentials['email'])->first();
                $response = [
                    'user'      => Auth::user()->name,
                    'token'     => $user->createToken("API TOKEN")->plainTextToken,
                    'success'   => true,
                    'message'   => "Usuário logado com sucesso"
                ];
                return response()->json($response, 200);
            } else {
                //if the user's credentials are invalid, redirect back to the login page with an error message
                $response = [
                    'success' => false,
                    'message' => 'email ou senha inválidos'
                ];
                return response()->json($response, 401);
            }
        } catch (\Throwable $th) {
            $response = [
                'success' => false,
                'message' => $th->getMessage()
            ];
            return response()->json($response, 500);
        }

    }


    public function update(Request $request)
    {
        try {
            //code...
            $user = Auth::user();
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'cpf' => 'sometimes|string|max:14|unique:users,cpf,'.$user->id,
                'email' => 'sometimes|string|email|max:255|unique:users,email,'.$user->id,
                'password' => 'sometimes|string|min:8|confirmed',
                'address' => 'sometimes|string'
            ]);

            $user->fill($request->only([
                'cpf', 'email'
            ]));

            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }

            $user->save();
            Session::flush();

            $response = [
                'success' => true,
                'message' => "Usuário atualizado com sucesso",
            ];
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            //throw $th;
            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];
            return response()->json($response, 500);
        }


    }

    public function logout()
    {
        try {
            //code...

            Auth::user()->tokens()->delete();

            $response = [
                'success' => true,
                'message' => 'Usuário deslogado com sucesso',
            ];
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            //throw $th;
            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];
            return response()->json($response, 500);
        }


    }
}
