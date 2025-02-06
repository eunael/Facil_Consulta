<?php
namespace Tests\Feature;

use App\Models\User;

it('should return null when there is no autheticated user', function() {
    $this->get(
        route('api.user', absolute: true)
    )
        ->assertOk()
        ->assertJson(['user' => null]);
});

it('should return autheticated user', function() {
    $user = User::factory()->create([
        'email' => 'admin@admin.com',
        'password' => 'password'
    ]);

    $token = getToken();

    $this->withHeaders([
        'Authorization' => 'Bearer ' . $token
    ])
        ->get(route('api.user', absolute: true))
        ->assertOk()
        ->assertJson([
            'user' => $user->toArray()
        ]);
});
