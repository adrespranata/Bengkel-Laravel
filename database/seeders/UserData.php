<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
                'nama' => 'Administrator',
                'username' => 'admin',
                'password' => bcrypt('admin'),
                'level' => 1,
                'email' => 'admin@gmail.com'
            ],
            [
                'nama' => 'Kasir',
                'username' => 'user',
                'password' => bcrypt('user'),
                'level' => 2,
                'email' => 'kasir@gmail.com'
            ]
        ];

        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}
