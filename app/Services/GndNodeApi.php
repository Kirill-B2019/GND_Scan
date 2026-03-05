<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Публичный API ноды GND для блокчейн-сканера (без X-Admin-Token).
 * Базовый URL: config('services.gnd.node_url'), эндпоинты /api/v1/*.
 */
class GndNodeApi
{
    public static function baseUrl(): string
    {
        return rtrim(config('services.gnd.node_url', 'http://localhost:8182'), '/');
    }

    public static function isConfigured(): bool
    {
        return ! empty(self::baseUrl());
    }

    private static function get(string $path): array
    {
        $r = Http::acceptJson()->timeout(15)->get(self::baseUrl() . $path);
        if (! $r->successful()) {
            return ['success' => false, 'data' => null, 'error' => $r->json('error', $r->body() ?: $r->reason())];
        }
        $body = $r->json();
        return ['success' => (bool) ($body['success'] ?? true), 'data' => $body['data'] ?? null, 'error' => null];
    }

    public static function getMetrics(): array
    {
        return self::get('/api/v1/metrics');
    }

    public static function getTransactionMetrics(): array
    {
        return self::get('/api/v1/metrics/transactions');
    }

    public static function getFeeMetrics(): array
    {
        return self::get('/api/v1/metrics/fees');
    }

    public static function getHealth(): array
    {
        return self::get('/api/v1/health');
    }

    public static function getLatestBlock(): array
    {
        return self::get('/api/v1/block/latest');
    }

    public static function getBlockByNumber(int|string $number): array
    {
        return self::get('/api/v1/block/' . $number);
    }

    public static function getTransaction(string $hash): array
    {
        return self::get('/api/v1/transaction/' . rawurlencode($hash));
    }

    /** Список транзакций из БД: limit, offset */
    public static function getTransactionsList(int $limit = 20, int $offset = 0): array
    {
        return self::get('/api/v1/transactions/list?limit=' . $limit . '&offset=' . $offset);
    }

    public static function getWalletBalance(string $address): array
    {
        return self::get('/api/v1/wallet/' . rawurlencode($address) . '/balance');
    }

    public static function getCoinSupply(string $symbol): array
    {
        return self::get('/api/v1/coin/' . rawurlencode($symbol) . '/supply');
    }

    public static function getCoinBalance(string $symbol, string $owner): array
    {
        return self::get('/api/v1/coin/' . rawurlencode($symbol) . '/balance/' . rawurlencode($owner));
    }

    public static function getContract(string $address): array
    {
        return self::get('/api/v1/contract/' . rawurlencode($address));
    }

    public static function getContractView(string $address): array
    {
        return self::get('/api/v1/contract/' . rawurlencode($address) . '/view');
    }

    public static function getContractState(string $address, ?string $addresses = null): array
    {
        $path = '/api/v1/contract/' . rawurlencode($address) . '/state';
        if ($addresses !== null && $addresses !== '') {
            $path .= '?addresses=' . rawurlencode($addresses);
        }
        return self::get($path);
    }

    public static function getTokenBalance(string $tokenAddress, string $owner): array
    {
        return self::get('/api/v1/token/' . rawurlencode($tokenAddress) . '/balance/' . rawurlencode($owner));
    }
}
