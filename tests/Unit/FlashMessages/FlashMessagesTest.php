<?php

namespace Tests\Unit\FlashMessages;

use App\Enum\FlashMessage\FlashMessageType;
use App\Http\Messages\FlashMessage;
use Tests\TestCase;

class FlashMessagesTest extends TestCase
{
    public function test_should_creates_a_success_flash_message_with_correct_structure(): void
    {
        $message = 'Operation completed successfully.';
        $flashMessage = FlashMessage::success($message);

        $this->assertEquals([
            'message' => [
                'type' => FlashMessageType::SUCCESS,
                'text' => $message,
            ],
        ], $flashMessage->toArray());
    }

    public function test_should_creates_an_error_flash_message_with_correct_structure(): void
    {
        $message = 'An error occurred.';
        $flashMessage = FlashMessage::error($message);

        $this->assertEquals([
            'message' => [
                'type' => FlashMessageType::ERROR,
                'text' => $message,
            ],
        ], $flashMessage->toArray());
    }

    public function test_should_creates_a_warning_flash_message_with_correct_structure(): void
    {
        $message = 'This is a warning.';
        $flashMessage = FlashMessage::warning($message);

        $this->assertEquals([
            'message' => [
                'type' => FlashMessageType::WARNING,
                'text' => $message,
            ],
        ], $flashMessage->toArray());
    }

    public function test_should_creates_an_info_flash_message_with_correct_structure(): void
    {
        $message = 'This is an informational message.';
        $flashMessage = FlashMessage::info($message);

        $this->assertEquals([
            'message' => [
                'type' => FlashMessageType::INFO,
                'text' => $message,
            ],
        ], $flashMessage->toArray());
    }

    public function test_should_merge_additional_data_with_flash_message(): void
    {
        $message = 'Success with data';
        $extraData = ['id' => 123, 'status' => 'active'];

        $flashMessage = FlashMessage::success($message);
        $merged = $flashMessage->merge($extraData);

        $this->assertArrayHasKey('message', $merged);
        $this->assertEquals($message, $merged['message']['text']);

        $this->assertArrayHasKey('id', $merged);
        $this->assertEquals(123, $merged['id']);

        $this->assertArrayHasKey('status', $merged);
        $this->assertEquals('active', $merged['status']);
    }
}
