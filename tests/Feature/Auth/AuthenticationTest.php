<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen and verifying OTP', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertGuest();
    $response->assertRedirect(route('otp.verify'));

    // Recuperar código OTP generado
    $otp = $user->fresh()->otp_code;

    // Simular el ingreso del OTP
    $verifyResponse = $this->withSession([
        'otp_user_id' => $user->id,
        'otp_remember' => false
    ])->post('/otp-verify', [
        'code' => $otp,
    ]);

    $this->assertAuthenticatedAs($user);
    $verifyResponse->assertRedirect(route('dashboard', absolute: false));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});

test('users can authenticate using username', function () {
    $user = User::factory()->create([
        'username' => 'testuser',
    ]);

    $response = $this->post('/login', [
        'email' => 'testuser',
        'password' => 'password',
    ]);

    $this->assertGuest();
    $response->assertRedirect(route('otp.verify'));

    // Recuperar código OTP generado
    $otp = $user->fresh()->otp_code;

    // Simular el ingreso del OTP
    $verifyResponse = $this->withSession([
        'otp_user_id' => $user->id,
        'otp_remember' => false
    ])->post('/otp-verify', [
        'code' => $otp,
    ]);

    $this->assertAuthenticatedAs($user);
    $verifyResponse->assertRedirect(route('dashboard', absolute: false));
});
