<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Notifications\ForgotPasswordNotification;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends Controller
{
    protected AuthRepositoryInterface $repository;

    public function __construct(AuthRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',

        ]);

        // Create user
        $user = $this->repository->create([
            'name'     => $request->name,
            'email'    => $request->email,

            'password' => Hash::make($request->password),
        ]);



        $user->assignRole('customer');

        return response()->json([
            'message' => 'User registered successfully',

            'user'    => $user,
        ], 201);
    }

    public function login(Request $request, ServerRequestInterface $serverRequest)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = $this->repository->findByEmail($request->email);

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }


        $tokenRequest = $serverRequest->withParsedBody([
            'grant_type'    => 'password',
            'client_id'     => config('services.passport.client_id'),
            'client_secret' => config('services.passport.client_secret'),
            'username'      => $request->email,
            'password'      => $request->password,
            'scope'         => '*',
        ]);

        $response = app(AccessTokenController::class)
            ->issueToken($tokenRequest, new Response());

        $token = json_decode($response->getContent(), true);

        return response()->json([
            'message' => 'Login successful',
            'token'   => $token,
            'roles'   => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = $this->repository->findByEmail($request->email);

        if (! $user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
        $token = Password::createToken($user);

        // Send reset password email
        $user->notify(new ForgotPasswordNotification($token));

        return response()->json([
            'message' => 'Password reset email sent successfully.',
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'token'    => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $resetToken = $this->repository->getResetToken(
            $request->email
        );

        if (
            ! $resetToken ||
            ! Hash::check($request->token, $resetToken->token)
        ) {
            return response()->json([
                'message' => 'Invalid token',
            ], 400);
        }

        $user = $this->repository->findByEmail(
            $request->email
        );

        $user->update([
            'password' => Hash::make($request->password), // auto hashed by model
        ]);

        $this->repository->deleteResetToken(
            $request->email
        );

        event(new PasswordReset($user));

        return response()->json([
            'message' => 'Password reset successful',
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Logout successful',
        ]);
    }
}
