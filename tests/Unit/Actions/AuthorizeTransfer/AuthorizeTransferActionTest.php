<?php

namespace Tests\Unit\Actions\AuthorizeTransfer;

use App\Actions\Transfer\AuthorizeTransferAction;
use App\Exceptions\Transfer\TransferUnauthorizedException;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthorizeTransferActionTest extends TestCase
{
    public function test_does_not_throw_when_authorized(): void
    {
        Http::fake(['*' => Http::response([
            'data' => ['authorization' => true],
        ], Response::HTTP_OK)]);

        $action = app(AuthorizeTransferAction::class);
        $action();

        $this->assertTrue(true);
    }

    public function test_throws_exception_when_not_authorized(): void
    {
        $this->expectException(TransferUnauthorizedException::class);

        Http::fake(['*' => Http::response([
            'data' => ['authorization' => false],
        ], Response::HTTP_OK)]);

        $action = app(AuthorizeTransferAction::class);
        $action();
    }

    public function test_throws_exception_on_http_failure(): void
    {
        $this->expectException(TransferUnauthorizedException::class);

        Http::fake(['*' => Http::response(null, Response::HTTP_INTERNAL_SERVER_ERROR)]);

        $action = app(AuthorizeTransferAction::class);
        $action();
    }
}
