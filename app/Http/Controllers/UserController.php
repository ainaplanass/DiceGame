<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;

class UserController extends Controller
{
  public function index()
  {
      return view ('holaaaa');
  }
  public function createPlayer(Request $request)
 {
    $validator = Validator::make($request->all(), [
      'nickname' => 'nullable|string|max:255|unique:users',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:6',
  ]);


    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    $user = User::create([
      'nickname' => $request->has('nickname') ? $request->nickname : 'anonymus',
      'email' => $request->email,
      'password' => Hash::make($request->password),
  ]); 

    return response()->json(['message' => 'Usuario registrado con éxito'], 201);
  }

  public function login(Request $request)
  {
      $validator = Validator::make($request->all(), [
          'email' => 'required|string|email',
          'password' => 'required|string',
      ]);

      if ($validator->fails()) {
          return response()->json(['error' => $validator->errors()], 400);
      }

      $credentials = $request->only(['email', 'password']);

      if (auth()->attempt($credentials)) {
      $user = auth()->user();
        $token = $user->createToken('MyAppToken')->accessToken;
        return response()->json(['access_token' => $token], 200);
      }

      return response()->json(['error' => 'Credenciales inválidas'], 401);
  }
  public function editPlayer(Request $request, $id) {
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'Jugador no encontrado'], 404);
    }

    $user->nickname = $request->input('nickname');
    $user->save();

    return response()->json(['message' => 'Nombre de jugador editado con éxito'], 200);
  }

    public function playerWinrate()
    {

    }
    public function playersList()
    {

    }
    public function worstPlayers()
    {
  
    }
    public function bestPlayers()
    {
  
    }
  }
