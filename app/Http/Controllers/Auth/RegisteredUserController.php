<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Prof;
use App\Models\Student;
use App\Models\User;
use App\Traits\GetUserTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    use GetUserTrait;
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

        $request->validate([
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);


        $user = User::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'typeUser' => $request->typeUser,


        ]);
        if($user->typeUser==0){
            Student::create([
                'idUser' => $user->id,
                'haveBinom' => $request->haveBinom,
                'specialite' => $request->specialite,
                'level' => $request->level,
                'section' => $request->section,
                'uniqueCode' => Str::random(10),
            ]);
        }else{
            if($user->typeUser == 1){
                $prof = Prof::create([
                    'idUser' => $user->id,
                    'isValidator' => $request->isValidator
                ]);
                foreach($request->categories as $category){
                     DB::insert('insert into prof_categories (idProf, idCategory) values (?, ?)', [$prof->id, $category]);
                }
            }else{
                Admin::create([
                    'idUser' => $user->id,
                    'typeAdmin' => $request->typeAdmin
                ]);
            }

        }

        event(new Registered($user));

        Auth::login($user);
        $token = $user->createToken('token')->plainTextToken;

        $cookie = cookie('jwt',$token,60*12);
        $user = $this->user();
        return response([
            'status' => 'good',
            'token' => $token,
            'user' => $user
        ])->withCookie($cookie);

    }
}
