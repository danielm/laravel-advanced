<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

use Illuminate\Support\Facades\Hash;

class UserTokenControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_ok()
    {
        $user = User::factory()->create([
            'password' => Hash::make($password = '123')
        ]);

        $data = [
            'email' => $user->email,
            'password' => $password,
            'source' => "feature test",
        ];
        $response = $this->postJson(route('auth.login'), $data);

        $response->assertSuccessful()
            ->assertJsonStructure(['token']);

    }

    public function test_login_validation()
    {
        $data = [
            'email' => "",
            'password' =>"" ,
            'source' => "",
        ];
        $response = $this->postJson(route('auth.login'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password', 'source']);
    }

    public function test_login_wrong_credentials()
    {
        $data = [
            'email' => "dude@dudez.com",
            'password' => "123" ,
            'source' => "unit test",
        ];
        $response = $this->postJson(route('auth.login'), $data);

        $response->assertUnauthorized();
    }
}
