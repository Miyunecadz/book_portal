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
    //for phase 2 updates 01.05.2023
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
    public function update(Request $request , User $users){
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required',
            'useraccess' => 'required',
        ]);
        $users->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'usertype' => $request->useraccess
        ]);
       // return redirect(route('user.profile'))->with('success','Profile successfully updated to the database');
        return redirect()->route('usrinfo.edit', ['users' => $users])->with('success', 'Author successfully updated to the database');

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
    public function delete(User $users)
    {
        /**
         * You can directly delete the author
         * To achieve that, use the authorVariable->delete()
         */

        $users->delete();

        /**
         * Redirect to author.index
         * Also add session with the value of { Author has been successfully deleted from the database }
         */

        return redirect()->route('userinfo.index')->with('success', 'Author has been successfully deleted from the database');
    }
}
