<?php

use App\Models\User;
use Database\Seeders\OlaraDatabaseSeeder;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->seed(OlaraDatabaseSeeder::class);
    $this->user = User::where('email', 'zidan@olara.id')->first();
});

test('guest cannot access profile page and is redirected to login', function () {
    $response = $this->get(route('profile.edit'));

    $response->assertRedirect(route('login'));
});

test('authenticated user can view profile edit page', function () {
    $response = $this->actingAs($this->user)->get(route('profile.edit'));

    $response->assertStatus(200);
    $response->assertSee('Edit Profil Saya');
    $response->assertSee('Informasi Pribadi');
    $response->assertSee('Keamanan');
    $response->assertSee($this->user->name);
    $response->assertSee($this->user->email);
});

test('user dropdown menu in layout contains Edit Profil link', function () {
    $response = $this->actingAs($this->user)->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee(route('profile.edit'));
    $response->assertSee('Edit Profil');
});

test('authenticated user can update profile information', function () {
    $response = $this->actingAs($this->user)->put(route('profile.update'), [
        'first_name' => 'Dandi',
        'last_name' => 'Azaidane',
        'email' => 'dandi.az@olara.id',
        'phone' => '081234567890',
        'origin' => 'Jakarta Selatan',
        'address' => 'Jl. Hijau Lestari No. 12, Jakarta',
    ]);

    $response->assertRedirect(route('profile.edit'));
    $response->assertSessionHas('success', 'Profil Anda berhasil diperbarui.');

    $this->user->refresh();
    expect($this->user->name)->toBe('Dandi Azaidane')
        ->and($this->user->first_name)->toBe('Dandi')
        ->and($this->user->last_name)->toBe('Azaidane')
        ->and($this->user->email)->toBe('dandi.az@olara.id')
        ->and($this->user->phone)->toBe('081234567890')
        ->and($this->user->origin)->toBe('Jakarta Selatan')
        ->and($this->user->address)->toBe('Jl. Hijau Lestari No. 12, Jakarta');
});

test('authenticated user cannot update to an email taken by another user', function () {
    User::factory()->create([
        'email' => 'taken@olara.id',
    ]);

    $response = $this->actingAs($this->user)->put(route('profile.update'), [
        'first_name' => 'Dandi',
        'email' => 'taken@olara.id',
    ]);

    $response->assertSessionHasErrors('email');
});

test('authenticated user can update password with valid current password', function () {
    $response = $this->actingAs($this->user)->put(route('profile.password'), [
        'current_password' => 'password123',
        'password' => 'newSecretPassword123',
        'password_confirmation' => 'newSecretPassword123',
    ]);

    $response->assertRedirect(route('profile.edit'));
    $response->assertSessionHas('success', 'Kata sandi berhasil diperbarui dengan aman.');

    $this->user->refresh();
    expect(Hash::check('newSecretPassword123', $this->user->password))->toBeTrue();
});

test('authenticated user cannot update password with incorrect current password', function () {
    $response = $this->actingAs($this->user)->put(route('profile.password'), [
        'current_password' => 'wrongpassword',
        'password' => 'newSecretPassword123',
        'password_confirmation' => 'newSecretPassword123',
    ]);

    $response->assertSessionHasErrors('current_password');
});
