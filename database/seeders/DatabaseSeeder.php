<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laravel\Passport\Client;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {


        $clientSecret = env('PASSPORT_CLIENT_SECRET', 'wsBa0mp4jwSTYssUGHX5xoqD9IC0X95Gfpg0w3uY');
        Client::updateOrCreate([
            'id' => 1
        ], [
            'name' => 'Tests',
            'secret' => $clientSecret,
            'redirect' => 'http://localhost',
            'provider' => 'users',
            'personal_access_client' => false,
            'password_client' => true,
            'revoked' => false
        ]);
    }
}
