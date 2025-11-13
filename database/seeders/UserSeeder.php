<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer l'utilisateur commerciale
        User::create([
            'name' => 'commerciale',
            'email' => 'commerciale@groupespeedo.com',
            'password' => Hash::make('commercialespeedo'),
        ]);

        // Créer l'utilisateur admin speedo
        User::create([
            'name' => 'admin speedo',
            'email' => 'admin@groupespeedo.com',
            'password' => Hash::make('directionspeedo'),
        ]);

        echo "2 utilisateurs créés avec succès!\n";
    }
}
