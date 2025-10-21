<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'user',
            'terms' => true,
        ]);

        // Vérifier que l'utilisateur a été créé
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        // Vérifier que l'utilisateur est connecté
        $this->assertAuthenticated();

        // Vérifier la redirection
        $response->assertRedirect(route('verification.notice'));
    }

    public function test_registration_validation_rules(): void
    {
        // Test avec des données invalides
        $response = $this->post('/register', [
            'first_name' => '', // Vide
            'last_name' => 'A', // Trop court
            'email' => 'invalid-email', // Email invalide
            'password' => '123', // Mot de passe trop simple
            'password_confirmation' => '456', // Confirmation différente
            'role' => 'invalid_role', // Rôle invalide
            'terms' => false, // Conditions non acceptées
        ]);

        $response->assertSessionHasErrors([
            'first_name',
            'last_name',
            'email',
            'password',
            'role',
            'terms'
        ]);
    }

    public function test_password_validation_rules(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'simple', // Mot de passe trop simple
            'password_confirmation' => 'simple',
            'role' => 'user',
            'terms' => true,
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_email_uniqueness(): void
    {
        // Créer un utilisateur existant
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->post('/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'name' => 'John Doe',
            'email' => 'existing@example.com', // Email déjà utilisé
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'user',
            'terms' => true,
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_phone_validation(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => 'invalid-phone', // Format invalide
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'user',
            'terms' => true,
        ]);

        $response->assertSessionHasErrors(['phone']);
    }

    public function test_birth_date_validation(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'birth_date' => '2030-01-01', // Date future
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'user',
            'terms' => true,
        ]);

        $response->assertSessionHasErrors(['birth_date']);
    }

    public function test_age_minimum_validation(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'birth_date' => '2020-01-01', // Trop jeune (moins de 13 ans)
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'user',
            'terms' => true,
        ]);

        $response->assertSessionHasErrors(['birth_date']);
    }

    public function test_name_generation_validation(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'name' => 'Jane Smith', // Nom qui ne correspond pas
            'email' => 'john@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'user',
            'terms' => true,
        ]);

        $response->assertSessionHasErrors(['name']);
    }
}
