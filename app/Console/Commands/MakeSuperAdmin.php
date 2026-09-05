<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

/**
 * Promeut un compte existant en super-administrateur.
 *
 * Nécessaire depuis que la gestion des admins est réservée au super-admin :
 * il doit toujours exister au moins un compte is_super_admin = true.
 *
 *   php artisan aeej:super-admin alseny@example.com
 */
class MakeSuperAdmin extends Command
{
    protected $signature = 'aeej:super-admin {email : Adresse e-mail du compte à promouvoir}';

    protected $description = 'Promeut un compte existant en super-administrateur';

    public function handle(): int
    {
        $email = mb_strtolower(trim($this->argument('email')));

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("Aucun compte avec l'adresse « {$email} ».");

            return self::FAILURE;
        }

        $user->forceFill([
            'is_admin'          => true,
            'is_super_admin'    => true,
            'approved_at'       => $user->approved_at ?? now(),
            'email_verified_at' => $user->email_verified_at ?? now(),
        ])->save();

        $this->info("{$user->name} ({$email}) est maintenant super-administrateur.");

        return self::SUCCESS;
    }
}
