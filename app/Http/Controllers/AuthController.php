<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\OTPMail;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        try {
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $password = $request->input('password');
            $user->password = app('hash')->make($password);

            $user->save();

            $response['success'] = true;
            $response['user'] = $user;
            $response['message'] = "Success registering user.";

            return response()->json($response, 200);

        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = "Failed registering user";
            $response['message'] = $e->getTraceAsString();
            return response()->json($response, 405);
        }
    }

    public function loginByPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            $response['success'] = false;
            $response['message'] = "Wrong email and password combination";
            return response()->json($response, 401);
        }

        return $this->respondWithToken($token);
    }

    public function loginByOTP(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'otp' => 'required|numeric',
        ]);

        $email = $request->get('email');
        $otp = $request->get('otp');

        $user = User::where('email', $email)->where('otp', $otp)->first();

        if ($user) {
            if (Carbon::parse($user->otp_expired_at)->lessThanOrEqualTo(Carbon::now())) {
                $response['success'] = false;
                $response['message'] = "OTP expired";
                return response()->json($response, 401);
            }
            $token = Auth::login($user);
            return $this->respondWithToken($token);
        }
        $response['success'] = false;
        $response['message'] = "Wrong email and OTP combination";
        return response()->json($response, 401);
    }

    public function requestOTP(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
        ]);

        $email = $request->get('email');

        $user = User::where('email', $email)->first();
        if ($user) {
            $user->generateOTP();

            Mail::to($user)->send(new OTPMail($user));

            $response['success'] = true;
            $response['message'] = "OTP sent to $email";
        } else {
            $response['success'] = false;
            $response['message'] = "Email not registered";
        }

        return $response;
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }
}
