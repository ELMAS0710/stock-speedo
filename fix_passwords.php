<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Mise a jour des mots de passe...\n\n";

// Récupérer tous les utilisateurs
$users = User::all();

foreach ($users as $user) {
    echo "Utilisateur: {$user->email}\n";
    
    // Vérifier si le mot de passe commence par $2y$ (Bcrypt hash)
    if (!str_starts_with($user->password, '$2y$')) {
        echo "  -> Mot de passe non hashe detecte!\n";
        
        // Définir le mot de passe selon l'email
        $plainPassword = ($user->email === 'commerciale@groupespeedo.com') 
            ? 'commercialespeedo' 
            : 'password';
        
        $newPassword = Hash::make($plainPassword);
        $user->password = $newPassword;
        $user->save();
        
        echo "  -> Mot de passe mis a jour (nouveau: '{$plainPassword}')\n";
    } else {
        echo "  -> Mot de passe deja hashe (OK)\n";
    }
    echo "\n";
}

echo "\nTermine!\n";
echo "\nVous pouvez maintenant vous connecter avec:\n";
echo "Email: commerciale@groupespeedo.com\n";
echo "Mot de passe: commercialespeedo\n";
