<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use Route;
use Hash;
use Auth;

class AuthController extends Controller
{
  public function __construct()
  {
    $this->client = DB::table('oauth_clients')->where('id', 2)->first();
  }

  public function login(Request $request) {
    
    return User::authenticate($request, $this->client);

  }

  public function register(Request $request) {

    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:6', 'confirmed'],
    ]);
 
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);
    if($user) {


      return User::authenticate($request, $this->client);     
    }
    return 'failed to register user';
  }

  public function logout() {
  	
  	auth()->user()->tokens->each(function($token, $key) {

  		$token->delete();
  	});

  	return response()->json('logged out successfully', 200 );
    
  }
}
