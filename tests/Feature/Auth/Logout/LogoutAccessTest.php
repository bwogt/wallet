<?php

namespace Tests\Feature\Auth\Logout;

use App\Enum\FlashMessage\FlashMessageType;
use Database\Factories\UserFactory;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LogoutAccessTest extends TestCase
{
    public function test_user_can_logout_and_current_access_token_is_revoked(): void
    {
        $consumer = UserFactory::new()->consumer()->create();
        $token = $consumer->createToken('auth-token');

        Sanctum::actingAs($consumer);
        $consumer->withAccessToken($token->accessToken);

        $this->postJson(route('api.v1.auth.logout'))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::SUCCESS)
                ->where('message.text', trans('flash_messages.success.logout'))
            );

        $this->assertCount(0, $consumer->fresh()->tokens);
    }
}
