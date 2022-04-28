<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

  public function signup(Request $request) {

		$rules = [
				'first_name' => 'required|string',
				'last_name' => 'required|string',
				'email' => 'required|email|unique:users,email',
				'password' => 'required|string|confirmed',
				'accept' => 'accepted',
		];

		$messages = [
				'first_name.required' => 'The "First name" field is required.',
				'last_name.required' => 'The "Last name" field is required.',
				'email.required' => 'A valid email is required.',
				'email.email' => 'The email address you provided is not valid.',
				'password.required' => 'A password is required.',
				'password.confirmed' => 'The passwords do NOT match.',
				'accept.accepted' => 'You must accept the terms and conditions.'
		];


		$fields = $request->validate($rules, $messages);

		$user = User::create([
				'first_name' => $fields['first_name'],
				'last_name' => $fields['first_name'],
				'email' => $fields['email'],
				'password' => Hash::make($fields['password'])
		]);

		$token = $user->createToken('secret-token')->plainTextToken;

			$response = [
				'user' => $user,
				'token' => $token
			];

			return response($response, 201);
  }

	public function signin(Request $request) {

		$fields = $request->validate([
			'email' => 'required|string',
			'password' => 'required|string'
		]);

		// Check email
		$user = User::where('email', $fields['email'])->first();

		// Check password
		if(!$user || !Hash::check($fields['password'], $user->password)) {
			return response(['message' => 'Incorrect email and/or password'], 401);
		}

		$token = $user->createToken('secret-token')->plainTextToken;

		$response = [
			'user' => $user,
			'token' => $token
		];

		return response($response, 201);
	}

	public function signout() {
    auth()->user()->tokens->each(function($token) {
        $token->delete();
    });

    return [
        'message' => 'You have been signed out'
    ];
	}
    
}
