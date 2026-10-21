<?php

use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('account.status', absolute: false));

    $user = User::where('email', 'test@example.com')->firstOrFail();

    expect($user->role)->toBe(User::ROLE_USER)
        ->and($user->status)->toBe(User::STATUS_PENDING)
        ->and($user->is_admin)->toBeFalse();
});

test('registration cannot inject a role or approved status', function () {
    $this->post('/register', [
        'name' => 'Manipulasi Role',
        'email' => 'manipulasi@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => User::ROLE_SUPER_ADMIN,
        'status' => User::STATUS_ACTIVE,
        'is_admin' => true,
    ]);

    $user = User::where('email', 'manipulasi@example.com')->firstOrFail();

    expect($user->role)->toBe(User::ROLE_USER)
        ->and($user->status)->toBe(User::STATUS_PENDING)
        ->and($user->is_admin)->toBeFalse();
});
