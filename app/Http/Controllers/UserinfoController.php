<?php

namespace App\Http\Controllers;
use App\Helpers\HumanNameFormatterHelper;
use App\Helpers\UsertypeHelper;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\usertype;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UserinfoController extends Controller
{
    public function index(){
        $getuser = User::paginate(10);
        return view('users.index',[
           'users' => $getuser,
       ]);
    }

     
    public function import(){
        return view('users.import');
    }
    public function edit(User $users)
    {
        $usertype = usertype::all();
     return view('users.edit', compact('users', 'usertype'));
       
    }
    public function create()
    {
        $usertype = usertype::all();
        $randpass = "!@#$%&()1234567890abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
       // $print = substr(str_shuffle($randpass),1,8);
         $print  = "qwe123123";
        return view('users.create', compact('usertype' ,'print'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'middle_initial' => 'required',
            'email' => 'required',
            'password' => 'required',
            'useraccess' => 'required',
        ]);
        User::create([
            'firstname' => $request->firstname,
            'lastname'=> $request->lastname,
            'middlename'=>$request->middle_initial,
            'email'=> $request->email,
            'password' =>Hash::make($request->password),
            'usertype' => $request->useraccess


        ]);
        return redirect(route('userinfo.register'))->with('success', 'Author successfully added to database');
    }

}
