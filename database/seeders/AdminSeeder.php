<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Создаёт роль admin и пользователя-админа.
     * Email и пароль можно задать в .env: ADMIN_EMAIL, ADMIN_PASSWORD.
     */
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $email = config('app.env') === 'local'
            ? (env('ADMIN_EMAIL') ?: 'admin@example.com')
            : (env('ADMIN_EMAIL') ?: null);

        if (! $email) {
            $this->command->warn('ADMIN_EMAIL не задан в .env — админ не создан.');

            return;
        }

        $password = env('ADMIN_PASSWORD') ?: 'password';

        $admin = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin',
                'password' => Hash::make($password),
            ]
        );

        if (! $admin->hasRole('admin')) {
            $admin->assignRole($role);
        }

        $this->command->info('Админ создан/обновлён: ' . $email . (env('ADMIN_PASSWORD') ? '' : ' (пароль по умолчанию: password — смените после входа)'));
    }
}
