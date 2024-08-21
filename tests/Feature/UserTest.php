<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use http\Header;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testRegisterSuccess()
    {
        $this->post('/api/users', [
            'email' => 'susmiyantoferi@gmail.com',
            'password' => 'qwerty',
            'name' => 'susmiyanto feri sus'
        ])->assertStatus(201)
            ->assertJson([
                "data" => [
                    'email' => 'susmiyantoferi@gmail.com',
                    'name' => 'susmiyanto feri sus'
                ]
            ]);

    }

    public function testRegisterFailed()
    {
        $this->post('/api/users', [
            'email' => '',
            'password' => '',
            'name' => ''
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'email' => [
                        "The email field is required."
                    ],
                    'password' => [
                        "The password field is required."
                    ],
                    'name' => [
                        "The name field is required."
                    ]
                ]
            ]);

    }

    public function testEmailAlreadyExist()
    {
        $this->testRegisterSuccess();
        $this->post('/api/users', [
            'email' => 'susmiyantoferi@gmail.com',
            'password' => 'qwerty',
            'name' => 'susmiyanto feri sus'
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'email' => [
                        "email already registered"
                    ],

                ]
            ]);

    }

    public function testLoginSuccess()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'email' => 'test@gmail.com',
            'password' => 'test',
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    'email' => 'test@gmail.com',
                    'name' => 'testlogin'
                ]
            ]);
        $user = User::where('email', 'test@gmail.com')->first();
        self::assertNotNull($user->token);
    }

    public function testLoginFailedEmailNotFound()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'email' => 'salah@gmail.com',
            'password' => 'test',
        ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    'message' => [
                        'email or password wrong'
                    ]
                ]
            ]);

    }

    public function testLoginFailedPasswordWrong()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'email' => 'test@gmail.com',
            'password' => 'salahpassword',
        ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    'message' => [
                        'email or password wrong'
                    ]
                ]
            ]);

    }

    public function testGetUserSuccess()
    {
        $this->seed([UserSeeder::class]);
        $this->get('/api/users/current', [
            'Authorization' => 'test'

        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    'email' => 'test@gmail.com',
                    'name' => 'testlogin'
                ]
            ]);

    }

    public function testGetUnauthorized()
    {
        $this->seed([UserSeeder::class]);
        $this->get('/api/users/current', [
            'Authorization' => ''

        ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    'message' => [
                        'unauthorized'
                    ]
                ]
            ]);

    }

    public function testGetUserTokenWrong()
    {
        $this->seed([UserSeeder::class]);
        $this->get('/api/users/current', [
            'Authorization' => 'salah'

        ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    'message' => [
                        'unauthorized'
                    ]
                ]
            ]);

    }

    public function testUpdateNameSuccess()
    {
        $this->seed([UserSeeder::class]);
        $oldEmail = User::where('email', 'test@gmail.com')->first();

        $this->patch('/api/users/current', [
            'name' => 'updatename'
        ],
            [
                'Authorization' => 'test'
            ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    'email' => 'test@gmail.com',
                    'name' => 'updatename'
                ]
            ]);

        $email = User::where('email', 'test@gmail.com')->first();
        self::assertNotEquals($oldEmail->name, $email->name);

    }

    public function testUpdatePasswordSuccess()
    {
        $this->seed([UserSeeder::class]);
        $oldEmail = User::where('email', 'test@gmail.com')->first();

        $this->patch('/api/users/current', [
            'password' => 'updatepassw'
        ],
            [
                'Authorization' => 'test'
            ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    'email' => 'test@gmail.com',
                    'name' => 'testlogin'
                ]
            ]);

        $email = User::where('email', 'test@gmail.com')->first();
        self::assertNotEquals($oldEmail->password, $email->password);
    }

    public function testUpdateFailled()
    {
        $this->seed([UserSeeder::class]);
        $this->patch('/api/users/current', [
            'name' => 'feriferiferiferiferiferiferiferiferiferiferiferiferferiferiferiferiferiferiferiferiferiferiferiferiferferiferiferiferiferiferiferiferiferiferiferiferifer'
        ],
            [
                'Authorization' => 'test'
            ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'name' => [
                        'The name field must not be greater than 100 characters.'
                    ]
                ]
            ]);
    }

    public function testLogoutSuccess()
    {
        $this->seed([UserSeeder::class]);
        $this->delete('api/users/logout', headers: [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data'=> true
            ]);

        $user = User::where('email','test@gmail.com')->first();
        self::assertNull($user->token);
    }

    public function testLogoutFailled()
    {
        $this->seed([UserSeeder::class]);
        $this->delete('api/users/logout', headers:[
            'Authorization' => 'salah'
        ])->assertStatus(401)
            ->assertJson([
                'errors'=> [
                    'message' => [
                        'unauthorized'
                    ]
                ]
            ]);
    }


}
