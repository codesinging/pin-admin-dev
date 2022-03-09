<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Support\Routing;

use CodeSinging\PinAdmin\Support\Routing\ApiResponse;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class ApiResponseTest extends TestCase
{
    public function testSuccess()
    {
        self::assertInstanceOf(JsonResponse::class, ApiResponse::success());
        self::assertEquals(200, ApiResponse::success()->status());
        self::assertEquals('message', ApiResponse::success('message')->getData(true)['message']);
        self::assertEquals(0, ApiResponse::success('message')->getData(true)['code']);
        self::assertEquals(['id' => 1], ApiResponse::success(['id' => 1])->getData(true)['data']);
    }

    public function testError()
    {
        self::assertInstanceOf(JsonResponse::class, ApiResponse::error());
        self::assertEquals(200, ApiResponse::error()->status());
        self::assertEquals('message', ApiResponse::error('message')->getData(true)['message']);
        self::assertEquals(-1, ApiResponse::error('message')->getData(true)['code']);
        self::assertEquals(10010, ApiResponse::error('message', 10010)->getData(true)['code']);
    }
}
