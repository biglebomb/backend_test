<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use  App\User;

class UserController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function currentUser()
    {
        $response['success'] = true;
        $response['user'] = Auth::user();
        return response()->json($response, 200);
    }

    public function all()
    {
        $response['success'] = true;
        $response['users'] = User::all();
        return response()->json($response, 200);
    }

    public function read($id)
    {
        try {
            $user = User::findOrFail($id);

            $response['success'] = true;
            $response['user'] = $user;
            return response()->json($response, 200);

        } catch (\Exception $e) {

            $response['success'] = false;
            $response['message'] = "User not found.";
            return response()->json($response, 404);
        }

    }

    public function update(Request $request, $id)
    {

        try {
            $user = User::findOrFail($id);

            $this->validate($request, [
                'name' => 'string',
                'email' => 'string',
                'password' => 'string',
            ]);

            $name = $request->input('name');
            $email = $request->input('email');
            $password = $request->input('password');

            $user->name = $name;
            $user->email = $email;
            $user->password = app('hash')->make($password);
            $user->save();

            $response['success'] = true;
            $response['message'] = "Success updating user.";
            return response()->json($response, 200);

        } catch (\Exception $e) {

            $response['success'] = false;
            $response['message'] = $e->getTraceAsString();
            return response()->json($e->getMessage(), 404);
        }
    }

    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);

            $user->delete();
            $response['success'] = true;
            $response['message'] = "Success deleting user.";
            return response()->json($response, 200);

        } catch (\Exception $e) {

            $response['success'] = false;
            $response['message'] = "User not found.";
            return response()->json($response, 404);
        }
    }

}
