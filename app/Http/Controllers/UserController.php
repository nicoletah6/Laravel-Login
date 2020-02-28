<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Validator;
use Storage;
use Socialite;

use App\Models\Account;

use App\Mail\SendRecoverCode;

class UserController extends Controller
{
  public function login (Request $request) {
    $form_data = $request->only(['email','password']);
    $validationRules = [
        'email'    => ['required','email'],
        'password' => ['required','min:6'],
    ];

    $validator = Validator::make($form_data, $validationRules);
    if ($validator->fails())
        return ['success' => false, 'msg' => $validator->errors()->all()];  
    else{
      $user = Account::where(['email' => $request->input('email')])->first();
      if($user && Hash::check($request->input('password'), $user->password)) {
        Auth::login($user);
        return ['success' => true, 'msg' => "Login succes!"];
      }
      else{
        return ['success' => false, 'msg' => "A aparut o eroare. Incercati mai tarziu!"];
      }
    }   
  }
  
  public function logout (Request $request) {
    Auth::logout();
    return ['success' => true, 'msg' => "Logout!"];
  }
  
  public function register (Request $request) {
    $form_data = $request->only(['name', 'email','phone', 'password', 'acord']);
    $validationRules = [
        'name'     => ['required', 'min:2'],
        'email'    => ['required', 'email', 'unique:accounts'],
        'password' => ['required','min:6'],
        'phone'    => ['required', 'min:10'],
        'acord'    => ['required'],
    ];
    $validator = Validator::make($form_data, $validationRules);
    if ($validator->fails())
        return ['success' => false, 'msg' => $validator->errors()->all()];  
    if(!filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)){
       return ['success' => false, 'msg' => "Emailul nu respecta formatul corect! Ex: email@email.com"];
    }
    // register new user
    $user = new Account;
    $user->email    = $form_data['email'];
    $user->name    = $form_data['name'];
    $user->phone    = $form_data['phone'];
    $user->password = Hash::make($form_data['password']);
    
    if (!$user->save()) {
      return ['success' => false, 'msg' => "A aparut o eroare. Incercati mai tarziu!"];
    }

    return [
        'success' => true,
        'msg' => "Felicitari! V-ati inregistrat cu succes!",
    ];
  }
  
  public function forgotPassword(Request $request) {
    $form_data = $request->only(['email']);
    $validationRules = [
        'email'    => ['required','email']
    ];
    $validator = Validator::make($form_data, $validationRules);
    if ($validator->fails())
        return ['success' => false, 'msg' => $validator->errors()->all()];
    if(!filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)){
       return ['success' => false, 'msg' => "Emailul nu respecta formatul corect! Ex: email@email.com"];
    }
    
    $user = Account::where('email', $request->email)->first();
    if (!$user)
        return ['success' => false, 'msg' => "Nu s-a gasit niciun user cu emailul introdus!"];
    $codDeTrimis = $this->generateRandomString(5);
    try {
        $user->recovery_code = $codDeTrimis;
        $user->save();
        return [
            'success' => true,
            'msg' => "Veti primi un email cu codul de verificare. Cod: ".$codDeTrimis
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'msg' => "A aparut o eroare. Incercati mai tarziu!",
        ];
    }
  }
  
  public function forgotPasswordVerify(Request $request) {
    $form_data = $request->only(['email', 'code', 'password', 'password1']);
    $validationRules = [
        'email'     => ['required', 'email'],
        'code'      => ['required'],
        'password'  => ['required', 'min:6'],
        'password1' => ['required'],
    ];
    $validator = Validator::make($form_data, $validationRules);
    if ($validator->fails())
        return ['success' => false, 'msg' => $validator->errors()->all()];  
    if(!filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)){
       return ['success' => false, 'msg' => "Emailul nu respecta formatul corect! Ex: email@email.com"];
    }
    if ($request->password != $request->password1)
        return ['success' => false, 'msg' => "Parolele nu corespund"];
    $user = Account::where('email', $request->email)->first();
    if (!$user)
        return ['success' => false, 'msg' => "Nu s-a gasit niciun user cu emailul introdus!"];
    if ($user->recovery_code !== $request->code)
        return ['success' => false, 'msg' => "Codul introdus nu este corect!"];
    try {
        $user->recovery_code = null;
        $user->password = Hash::make($request->password);
        $user->save();
        return [
            'success' => true,
            'msg' => "Parola a fost schimbata!",
        ];
    } catch (\Exception $e) {
        return ['success' => false, 'msg' => "A aparut o eroare. Incercati mai tarziu!"];
    }
  }

  public function do_edit_account (Request $request) {
    $form_data = $request->only(['name', 'last_name', 'phone','email', 'acord']);
    $validationRules = [
        'name'      => ['required','min:2'],
        'email'     => ['required','email'],
        'phone'     => ['required','min:10'],
        'acord'     => ['required']
    ];
    $validator = Validator::make($form_data, $validationRules);
    if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
       return ['success' => false, 'msg' => "Emailul nu respecta formatul corect! Ex: email@email.com"];
    }
    if ($validator->fails())
        return ['success' => false, 'msg' => $validator->errors()->all()];
    else{
      try{
        if (Account::where('id', '!=', Auth::id())->where('email', $request->email)->count() > 0)
            return ['success' => false, 'msg' => "Se pare ca mai exista un utilizator cu aceasta adresa de email"];
            
        $data_to_save = [
          'name' => $form_data['name'],
          'last_name' => $form_data['last_name'],
          'email' => $form_data['email'],
          'phone' => $form_data['phone'],
        ];
        Account::where('id', Auth::id())->update($data_to_save);
        return ['success' => true, 'msg' => "Datele au fost salvate cu succes!"];

      }
      catch (\Illuminate\Database\QueryException $e) {
           return ['success' => false, 'msg' => "A aparut o eroare. Incercati mai tarziu!"]; 
      }
    }
  }

  public function do_edit_password (Request $request) {
    $form_data = $request->only(['password', 'acord']);
    $validationRules = [
        'password'  => ['required','min:6'],
        'acord'     => ['required']
    ];
    $validator = Validator::make($form_data, $validationRules);
    if ($validator->fails())
        return ['success' => false, 'msg' => $validator->errors()->all()];
    else{
      try{
        Account::where('id', Auth::id())->update(['password' => Hash::make($form_data['password'])]);
        return ['success' => true, 'msg' => "Parola modificata cu succes!"];

      }
      catch (\Illuminate\Database\QueryException $e) {
         return ['success' => false, 'msg' => "A aparut o eroare. Incercati mai tarziu!"]; 
      }
    }
  }
  
  public static function generateRandomString($length = 90) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
  
}
