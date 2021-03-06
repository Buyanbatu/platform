<?php

declare(strict_types=1);

namespace Tests\Feature\Platform;

use Orchid\Platform\Models\User;
use Orchid\Tests\TestFeatureCase;

class AuthTest extends TestFeatureCase
{
    /**
     * debug: php vendor/bin/phpunit  --filter= AuthTest tests\\Feature\\Platform\\AuthTest --debug.
     * @var
     */
    private $user;

    public function test_route_DashboardLogin()
    {
        $response = $this->get(route('platform.login'));

        $response->assertStatus(200);
        $this->assertContains('type="email"', $response->getContent());
        $this->assertContains('type="password"', $response->getContent());
    }

    public function test_route_DashboardLogin_auth()
    {
        $response = $this->actingAs($this->getUser())
            ->get(route('platform.login'));

        $response->assertStatus(302);
        $response->assertRedirect('/home');
    }

    private function getUser()
    {
        if ($this->user) {
            return $this->user;
        }
        $this->user = factory(User::class)->create();

        return $this->user;
    }

    public function test_route_DashboardPasswordRequest()
    {
        $response = $this->get(route('platform.password.request'));

        $response->assertStatus(200);
        $this->assertContains('type="email"', $response->getContent());
        $this->assertNotContains('type="password"', $response->getContent());
    }

    public function test_route_DashboardPasswordReset()
    {
        $response = $this->get(route('platform.password.reset', '11111'));

        $response->assertStatus(200);
        $this->assertContains('type="email"', $response->getContent());
        $this->assertContains('type="password"', $response->getContent());
        $this->assertContains('"password_confirmation"', $response->getContent());
    }

    public function test_route_DashboardPasswordReset_auth()
    {
        $response = $this->actingAs($this->getUser())
            ->get(route('platform.password.reset', '11111'));

        $response->assertStatus(302);
        $response->assertRedirect('/home');
    }
}
