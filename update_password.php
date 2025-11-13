<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Mise a jour du mot de passe pour commerciale@groupespeedo.com...\n\n";

$user = User::where('email', 'commerciale@groupespeedo.com')->first();

if ($user) {
    echo "Utilisateur trouve: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Ancien hash: " . substr($user->password, 0, 30) . "...\n\n";
    
    // Forcer le nouveau mot de passe
    $user->password = Hash::make('commercialespeedo');
    $user->save();
    
    echo "Nouveau hash: " . substr($user->password, 0, 30) . "...\n";
    echo "Mot de passe mis a jour avec succes!\n\n";
    
    // Vérifier que le mot de passe fonctionne
    if (Hash::check('commercialespeedo', $user->password)) {
        echo "VERIFICATION: Le mot de passe 'commercialespeedo' est correct!\n";
    } else {
        echo "ERREUR: La verification a echoue!\n";
    }
} else {
    echo "Utilisateur non trouve!\n";
}
