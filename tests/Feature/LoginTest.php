<?php
namespace Tests\Feature;

use App\Models\User;

test('invalid credentials should return Unauthorized response', function() {
    $this->post(
        route('api.login', absolute: true),
        [
            'email' => 'test@email.com',
            'password' => 'test123'
        ]
    )
        ->assertUnauthorized()
        ->assertJson(['error' => 'Unauthorized']);
});

test('valid credentails should return authetication token informations', function() {
    User::factory()->create([
        'email' => 'admin@admin.com',
        'password' => 'password'
    ]);

    $this->post(
        route('api.login', absolute: true),
        [
            'email' => 'admin@admin.com',
            'password' => 'password'
        ]
    )
        ->assertOk()
        ->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
});
