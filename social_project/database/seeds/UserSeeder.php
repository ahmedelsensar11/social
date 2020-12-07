<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for($i=1 ; $i<8 ;$i++)
        {
            $user = new User ;
            $user->name = "user number $i";
            $user->email  = "$i.23@gmail.com";
            $user->password = "123$i.12";
            $user->work = "work$i";
            $user->location = "location$i";
            $user->image = "image$i.png";
            $user->save();
        }
    }
}
