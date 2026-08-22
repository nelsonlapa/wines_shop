<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $adminRole = Role::updateOrCreate(
            ['name' => 'admin'],
            ['name' => 'admin']
        );

        $organizadorRole = Role::updateOrCreate(
            ['name' => 'organizador'],
            ['name' => 'organizador']
        );

        $participanteRole = Role::updateOrCreate(
            ['name' => 'participante'],
            ['name' => 'participante']
        );

        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin Teste',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'organizador@test.com'],
            [
                'name' => 'Organizador Teste',
                'password' => Hash::make('password'),
                'role_id' => $organizadorRole->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'participante@test.com'],
            [
                'name' => 'Participante Teste',
                'password' => Hash::make('password'),
                'role_id' => $participanteRole->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'ana.participante@test.com'],
            [
                'name' => 'Ana Participante',
                'password' => Hash::make('password'),
                'role_id' => $participanteRole->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'joao.organizador@test.com'],
            [
                'name' => 'João Organizador',
                'password' => Hash::make('password'),
                'role_id' => $organizadorRole->id,
            ]
        );
    }
    
}
