<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    public function testValidTypeAndSingleValue()
    {
        $response = $this->json('POST', '/api/currency', [
            'type' => 'code',
            'value' => 'ANG'
        ]);

        $response->assertStatus(200);
    }

    public function testValidTypeAndArrayValues()
    {
        $response = $this->json('POST', '/api/currency', [
            'type' => 'code_list',
            'value' => ['ANG', 'DKK']
        ]);

        $response->assertStatus(200);
    }

    public function testInvalidType()
    {
        $response = $this->json('POST', '/api/currency', [
            'type' => 'invalid_type',
            'value' => 'ANG'
        ]);

        $response->assertStatus(422);
    }

    public function testInvalidValueByType()
    {
        $response = $this->json('POST', '/api/currency', [
            'type' => 'code',
            'value' => ['ANG', 'DKK']
        ]);

        $response->assertStatus(422);
    }
}
