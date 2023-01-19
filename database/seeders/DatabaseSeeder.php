<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\usertype;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'firstname' => 'Brian',
            'lastname' => 'Rabin',
            'email' => 'brianrabin@elink.com.ph',
            'email_verified_at' => now(),
            'usertype'=> '1',
            'department'=>'OpEx',
            'password' => Hash::make('1234'),
        ]);
        User::create([
            'firstname' => 'Jake',
            'lastname' => 'Relampagos',
            'email' => 'jakerelampagos@elink.com.ph',
            'email_verified_at' => now(),
            'usertype'=> '1',
            'department'=>'OpEx',
            'password' => Hash::make('1234'),
        ]);
        User::create([
            'firstname' => 'Kemberlie',
            'lastname' => 'Sabellano',
            'email' => 'kemberliesabellano@elink.com.ph',
            'email_verified_at' => now(),
            'usertype'=> '2',
            'department'=>'OpEx',
            'password' => Hash::make('1234')
        ]);
        User::create([
            'firstname' => 'Rhoda Mae',
            'lastname' => 'Dou-ay',
            'email' => 'maedou-ay@readersmagnet.com',
            'email_verified_at' => now(),
            'usertype'=> '4',
            'department'=>'SALES',
            'password' => Hash::make('1234')
        ]);
        User::create([
            'firstname' => 'Grace',
            'lastname' => 'Fantonial',
            'email' => 'grace@readersmagnet.com',
            'email_verified_at' => now(),
            'usertype'=> '4',
            'department'=>'SALES',
            'password' => Hash::make('1234')
        ]);
        User::create([
            'firstname' => 'Arlin',
            'lastname' => 'Dela Cruz',
            'email' => 'arlindelacruz@elink.com.ph',
            'email_verified_at' => now(),
            'usertype'=> '3',
            'department'=>'SALES',
            'password' => Hash::make('1234')
        ]);
        User::create([
            'firstname' => 'Luningning',
            'lastname' => 'Vasquez',
            'email' => 'lu@elink.com.ph',
            'email_verified_at' => now(),
            'usertype'=> '3',
            'department'=>'ARO',
            'password' => Hash::make('1234')
        ]);
        User::create([
            'firstname' => 'Ryan',
            'lastname' => 'Vindo',
            'email' => 'hey3x@elink.com.ph',
            'email_verified_at' => now(),
            'usertype'=> '4',
            'department'=>'ARO',
            'password' => Hash::make('1234')
        ]);
        User::create([
            'firstname' => 'Willa Mae',
            'lastname' => 'Hiyoca',
            'email' => 'hiyoca@elink.com.ph',
            'email_verified_at' => now(),
            'usertype'=> '4',
            'department'=>'ARO',
            'password' => Hash::make('1234')
        ]);
        usertype::create([
            'usertype' => 'superadmin'       
        ]);
        usertype::create([
            'usertype' => 'admin'       
        ]);
        usertype::create([
            'usertype' => 'manager'       
        ]);
        usertype::create([
            'usertype' => 'reguser'       
        ]);
        Department::create([
            'deptcode' =>'SALES',
            'deptname' =>'SALES',
            
        ]);
        Department::create([
            'deptcode' =>'OpEx',
            'deptname' =>'Operations Excellence',
            
        ]);
        Department::create([
            'deptcode' =>'Publishing',
            'deptname' =>'Publishing Production',
            
        ]);
        Department::create([
            'deptcode' =>'LM',
            'deptname' =>'Lead Management',
            
        ]);
        Department::create([
            'deptcode' =>'ARO',
            'deptname' =>'Auhtor Relation Officer',
            
        ]);

    }
}
