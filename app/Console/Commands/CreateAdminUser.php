<?php

namespace App\Console\Commands;

use App\Models\UserAccount;
use App\Models\UserRole;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create';

    protected $description = 'Create the first Somos Constructivos administrator';

    public function handle(): int
    {
        $role = UserRole::query()
            ->where('code', 'Administrator')
            ->where('isActive', true)
            ->first();

        if ($role === null) {
            $this->error(
                'No existe un rol Administrator activo. Ejecuta los seeders primero.'
            );

            return self::FAILURE;
        }

        $firstName = trim((string) $this->ask('Nombre'));
        $lastName = trim((string) $this->ask('Apellidos'));
        $email = strtolower(trim((string) $this->ask('Correo electrónico')));

        if ($firstName === '') {
            $this->error('El nombre es obligatorio.');

            return self::FAILURE;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('El correo electrónico no es válido.');

            return self::FAILURE;
        }

        if (
            UserAccount::query()
                ->where('email', $email)
                ->exists()
        ) {
            $this->error(
                'Ya existe una cuenta con ese correo electrónico.'
            );

            return self::FAILURE;
        }

        $password = (string) $this->secret(
            'Contraseña (mínimo 12 caracteres)'
        );

        $passwordConfirmation = (string) $this->secret(
            'Confirma la contraseña'
        );

        if (mb_strlen($password) < 12) {
            $this->error(
                'La contraseña debe tener al menos 12 caracteres.'
            );

            return self::FAILURE;
        }

        if ($password !== $passwordConfirmation) {
            $this->error('Las contraseñas no coinciden.');

            return self::FAILURE;
        }

        UserAccount::create([
            'userRoleId' => $role->userRoleId,
            'firstName' => $firstName,
            'lastName' => $lastName !== '' ? $lastName : null,
            'email' => $email,
            'passwordHash' => Hash::make($password),
            'isActive' => true,
        ]);

        $this->info('Administrador creado correctamente.');

        return self::SUCCESS;
    }
}
