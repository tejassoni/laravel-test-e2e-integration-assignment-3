<?php

namespace Tests\Browser;

use Throwable;
use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\LoginPage;

class LoginTest extends DuskTestCase
{
    // This trait will ensure a fresh database for each test run,
    // which is crucial for tests involving user creation/authentication.
    use DatabaseMigrations;

    /**
     * Test: A registered user can successfully log in. (Happy Path)
     * This is the test you already have, slightly refined.
     */
      /**
     * Test: A registered user can successfully log in. (Happy Path)
     */
    public function testUserCanLoginSuccessfully(): void
    {
        $functionName = __FUNCTION__;
        $password = 'password';

        // Arrange: Create a user
        $user = User::factory()->create([
            'email' => 'login_test_user_' . uniqid() . '@example.com',
            'password' => Hash::make($password),
        ]);

        // Act: Run browser automation
        $this->browse(function (Browser $browser) use ($user, $password, $functionName) {
            try {
                $browser->visit('/login')
                    ->screenshot("login/{$functionName}/01-before-login-page")

                    ->waitFor('input[name="email"]', 10)
                    ->typeSlowly('email', $user->email, 50)

                    ->waitFor('input[name="password"]', 10)
                    ->typeSlowly('password', $password)

                    ->screenshot("login/{$functionName}/02-after-filling-login-form")

                    ->waitFor('button[type="submit"]', 10)
                    ->press('button[type="submit"]')

                    ->screenshot("login/{$functionName}/03-after-pressing-login")

                    ->assertPathIs('/dashboard')
                    ->assertSee('Dashboard')

                    ->pause(1500)
                    ->screenshot("login/{$functionName}/04-after-successful-login");

            } catch (Throwable $e) {
                // Take failure screenshot
                $browser->screenshot("login/{$functionName}/error-exception");
                
                Log::error("Dusk login test failed: " . $e->getMessage());
                // Optionally log or handle the exception here
                throw $e; // Rethrow to ensure test fails
            }
        });
    }

     public function UserCanLoginSuccessfullyUsingPage(): void
    {
        $functionName = __FUNCTION__;
        $password = 'password';

        // Arrange: Create a user
        $user = User::factory()->create([
            'email' => 'login_test_user_' . uniqid() . '@example.com',
            'password' => Hash::make($password),
        ]);

        // Act: Run browser automation
        $this->browse(function (Browser $browser) use ($user, $password, $functionName) {
            try {
                $browser->visit(new LoginPage)
                    ->screenshot("login/{$functionName}/01-before-login-page")
                    ->value('@email', $user->email)
                    ->value('@password', $password)
                    ->screenshot("login/{$functionName}/02-after-filling-login-form")
                    ->waitFor('button[type="submit"]', 10)
                    ->press('button[type="submit"]')
                    ->screenshot("login/{$functionName}/03-after-pressing-login")
                    ->assertPathIs('/dashboard')
                    ->assertSee('Dashboard')
                    ->pause(1500)
                    ->screenshot("login/{$functionName}/04-after-successful-login");
            } catch (Throwable $e) {
                // Take failure screenshot
                $browser->screenshot("login/{$functionName}/error-exception");
                
                Log::error("Dusk login test failed: " . $e->getMessage());
                // Optionally log or handle the exception here
                throw $e; // Rethrow to ensure test fails
            }
        });
    }

}
