<?php

namespace App\Services\Payments;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ShurjoPayService
{
    public function getToken(): ?array
    {
        $response = Http::asForm()->post($this->baseUrl() . '/api/get_token', [
            'username' => config('services.shurjopay.username'),
            'password' => config('services.shurjopay.password'),
        ]);

        return $this->toArray($response);
    }

    public function createPayment(array $payload): ?array
    {
        $response = Http::asForm()->post($this->baseUrl() . '/api/secret-pay', $payload);

        return $this->toArray($response);
    }

    public function verifyPayment(array $payload): ?array
    {
        $response = Http::asForm()->post($this->baseUrl() . '/api/verification', $payload);

        return $this->toArray($response);
    }

    private function baseUrl(): string
    {
        return config('services.shurjopay.sandbox', true)
            ? 'https://sandbox.shurjopayment.com'
            : 'https://engine.shurjopayment.com';
    }

    private function toArray(Response $response): ?array
    {
        return $response->ok() ? $response->json() : null;
    }
}
