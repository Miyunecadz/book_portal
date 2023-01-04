<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\usertype;
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
            'password' => Hash::make('1234'),
        ]);

        User::create([
            'firstname' => 'Jake',
            'lastname' => 'Relampagos',
            'email' => 'jakerelampagos@elink.com.ph',
            'email_verified_at' => now(),
            'usertype'=> '1',
            'password' => Hash::make('1234')
        ]);

        User::create([
            'firstname' => 'Shielo',
            'lastname' => 'Arong',
            'email' => 'sheiloarong@elink.com.ph',
            'email_verified_at' => now(),
            'usertype'=> '1',
            'password' => Hash::make('1234')
        ]);

        User::create([
            'firstname' => 'Rey Manuel',
            'lastname' => 'Ferolino',
            'email' => 'reymanuelferolino@elink.com.ph',
            'email_verified_at' => now(),
            'usertype'=> '2',
            'password' => Hash::make('1234')
        ]);

        User::create([
            'firstname' => 'Kemberlie',
            'lastname' => 'Sabellano',
            'email' => 'kemberliesabellano@elink.com.ph',
            'email_verified_at' => now(),
            'usertype'=> '2',
            'password' => Hash::make('1234')
        ]);
        User::create([
            'firstname' => 'Franc',
            'lastname' => 'Sanders',
            'email' => 'qrtabares@elink.com.ph',
            'email_verified_at' => now(),
            'usertype'=> '2',
            'password' => Hash::make('1234')
        ]);
        User::create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@elink.com.ph',
            'email_verified_at' => now(),
            'usertype'=> '1',

            'password' => Hash::make('qwe123123')
        ]);
        User::create([
            'firstname' => 'Leah',
            'lastname' => 'Malinao',
            'email' => 'leahmalinao@elink.com.ph',
            'email_verified_at' => now(),
            'usertype'=> '1',
            'password' => Hash::make('qwe123123')
        ]);
        User::create([
            'firstname' => 'James Dodge',
            'lastname' => 'Perez',
            'email' => 'jamesdodgeperez@elink.com.ph',
            'email_verified_at' => now(),
            'password' => Hash::make('1234')
        ]);
        User::create([
            'firstname' => 'Arlin',
            'lastname' => 'Dela Cruz',
            'email' => 'arlindelacruz@elink.com.ph',
            'email_verified_at' => now(),
            'password' => Hash::make('1234')
        ]);
        User::create([
            'firstname' => 'Bobby',
            'lastname' => 'Malinao',
            'email' => 'bobmarquez@readersmagnet.com',
            'email_verified_at' => now(),
            'password' => Hash::make('qwe123123')
        ]);
        User::create([
            'firstname' => 'Christopher',
            'lastname' => 'To',
            'email' => 'christopherto@readersmagnet.com',
            'email_verified_at' => now(),
            'password' => Hash::make('qwe123123')
        ]);
        User::create([
            'firstname' => 'Florisse',
            'lastname' => 'Blanco',
            'email' => 'florinebelford@readersmagnet.com',
            'email_verified_at' => now(),
            'password' => Hash::make('qwe123123')
        ]);
        User::create([
            'firstname' => 'Kim Rod',
            'lastname' => 'Basit',
            'email' => 'kimbasit@readersmagnet.com',
            'email_verified_at' => now(),
            'password' => Hash::make('qwe123123')
        ]);
        User::create([
            'firstname' => 'Jamela May',
            'lastname' => 'Comoyong',
            'email' => 'jamcomoyong@readersmagnet.com',
            'email_verified_at' => now(),
            'password' => Hash::make('qwe123123')
        ]);
        User::create([
            'firstname' => 'Michael Frances',
            'lastname' => 'Yutan',
            'email' => 'michaelyutan@readersmagnet.com',
            'email_verified_at' => now(),
            'password' => Hash::make('qwe123123')
        ]);
        User::create([
            'firstname' => 'Klench',
            'lastname' => 'Ando',
            'email' => 'klenchando@readersmagnet.com',
            'email_verified_at' => now(),
            'password' => Hash::make('qwe123123')
        ]);
        User::create([
            'firstname' => 'Christian',
            'lastname' => 'Galinato',
            'email' => 'christiangalinato@elink.com.ph',
            'email_verified_at' => now(),
            'password' => Hash::make('qwe123123')
        ]);
        User::create([
            'firstname' => 'Ritely',
            'lastname' => 'Quimbo',
            'email' => 'ritehlyquimbo@elink.com.ph',
            'email_verified_at' => now(),
            'password' => Hash::make('qwe123123')
        ]);
        User::create([
            'firstname' => 'Maikee',
            'lastname' => 'Amoin',
            'email' => 'maikeeamoin@elink.com.ph',
            'email_verified_at' => now(),
            'password' => Hash::make('qwe123123')
        ]);
        User::create([
            'firstname' => 'Cat Steven',
            'lastname' => 'Betonio',
            'email' => 'catstevenbetonio@elink.com.ph',
            'email_verified_at' => now(),
            'password' => Hash::make('qwe123123')
        ]);
        User::create([
            'firstname' => 'Ariel',
            'lastname' => 'Dumalag',
            'email' => 'arieldumalag@elink.com.ph',
            'email_verified_at' => now(),
            'password' => Hash::make('qwe123123')
        ]);
        User::create([
            'firstname' => 'Ramffy',
            'lastname' => 'Rabadon',
            'email' => 'ramffyrabadon@elink.com.ph',
            'email_verified_at' => now(),
            'password' => Hash::make('qwe123123')
        ]);
       
        User::create([
            'firstname' => 'Juncel',
            'lastname' => 'Carreon',
            'email' => 'juncelcarreon@elink.com.ph',
            'email_verified_at' => now(),
            'usertype'=> '2',
            'password' => Hash::make('qwe123123')
        ]);
        User::create([
            'firstname' => 'Edwin Jr.',
            'lastname' => 'Rosales',
            'email' => 'edwinrosales@elink.com.ph',
            'email_verified_at' => now(),
            'password' => Hash::make('qwe123123')
        ]);
        User::create([
            'firstname' => 'Jeason.',
            'lastname' => 'Felipe',
            'email' => 'jeasonfelipe@elink.com.ph',
            'email_verified_at' => now(),
            'password' => Hash::make('qwe123123')
        ]);
        User::create([
            'firstname' => 'Luningning',
            'lastname' => 'Vasquez',
            'email' => 'luningningvasquez@elink.com.ph',
            'email_verified_at' => now(),
            'password' => Hash::make('qwe123123')
        ]);
        User::create([
            'firstname' => 'Angelic Nikki Louise',
            'lastname' => 'Boltron',
            'email' => 'nikkiboltron@readersmagnet.com',
            'email_verified_at' => now(),
            'password' => Hash::make('qwe123123')
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
    }
}
