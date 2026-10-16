<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cdmit:admin {email? : Admin email address} {--name= : Admin display name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Securely create or promote the CDMIT Super Admin account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = strtolower((string) ($this->argument('email') ?: $this->ask('Email address')));
        $name = (string) ($this->option('name') ?: $this->ask('Display name', 'CDMIT Administrator'));
        $password = (string) $this->secret('Password (minimum 12 characters)');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($password) < 12) {
            $this->error('Provide a valid email and a password of at least 12 characters.');

            return self::FAILURE;
        }

        User::updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => Hash::make($password), 'is_super_admin' => true, 'email_verified_at' => now()],
        );

        $this->info('Super Admin account is ready.');

        return self::SUCCESS;
    }
}
