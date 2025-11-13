<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Mise a jour du mot de passe pour admin@speedo.com...\n\n";

$user = User::where('email', 'admin@speedo.com')->first();

if ($user) {
    echo "Utilisateur trouve: {$user->name}\n";
    echo "Email: {$user->email}\n\n";
    
    // Définir le mot de passe admin
    $user->password = Hash::make('directionspeedo');
    $user->save();
    
    echo "Mot de passe mis a jour avec succes!\n";
    echo "Nouveau mot de passe: directionspeedo\n\n";
    
    // Vérifier
    if (Hash::check('directionspeedo', $user->password)) {
        echo "VERIFICATION: Le mot de passe 'directionspeedo' est correct!\n";
    }
} else {
    echo "Utilisateur non trouve!\n";
}
