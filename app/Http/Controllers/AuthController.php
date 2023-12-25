<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\ResetEmail;
use Carbon\Traits\Timestamp;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Exceptions\GeneralJsonException;
use Nette\Utils\Random;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'email|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw new GeneralJsonException('email or password are wrong!', 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'token revoked'
        ], 200);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw new GeneralJsonException('Something went wrong, try again later', 500);
        }

        //remove reset token if exist and create new one
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => Random::generate(60, '0-9a-zA-Z'),
            'created_at' => Carbon::now()
        ]);

        //send reset token with email as link
        $tokenData = DB::table('password_reset_tokens')
        ->where('email', $user->email)->first();

        $email = $tokenData->email;
        $token = $tokenData->token;

        $data = ['link' => request()->getSchemeAndHttpHost() . '/resetPassword/' . $token . '?email=' . $email];

        try {
            Mail::to($user)->send(new ResetEmail($data));
        } catch (\Throwable $th) {
            throw new GeneralJsonException('Something went wrong, try again later', 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'check your email box to reset password',
        ], 200);

    }

    public function resetPassword(Request $request, string $resetToken)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);

        //check if token with email exist

        $tokenData = DB::table('password_reset_tokens')
        ->where('email', $request->email)->first();

        //check if token is valid

        if (!$tokenData || $tokenData->token !== $resetToken || Carbon::now() > Carbon::parse($tokenData->created_at)->addHours(8)) {
            throw new GeneralJsonException('this reset token is not valid', 401);
        }

        // remove reset token from db

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        //update password user delete all login tokens and provide new login token

        $user = User::where('email', $request->email)->first();

        $user->update(['password' => bcrypt($request->password)]);

        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token
        ], 200);

    }
}
