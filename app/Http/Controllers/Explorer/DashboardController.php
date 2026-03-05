<?php

namespace App\Http\Controllers\Explorer;

use App\Http\Controllers\Controller;
use App\Services\GndNodeApi;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $metrics = null;
        $latestBlock = ['success' => false, 'data' => null];
        $txList = ['success' => false, 'data' => null];

        if (GndNodeApi::isConfigured()) {
            $metricsResponse = GndNodeApi::getMetrics();
            $latestBlock = GndNodeApi::getLatestBlock();
            $txList = GndNodeApi::getTransactionsList(15, 0);

            // Нормализация метрик: API ноды возвращает вложенные BlockMetrics, TransactionMetrics (PascalCase)
            if ($metricsResponse['success'] && $metricsResponse['data']) {
                $metrics = self::normalizeMetrics($metricsResponse['data']);
            }
        }

        $blocks = [];
        $blockData = $latestBlock['data'] ?? null;
        $hasBlock = $blockData && (isset($blockData['ID']) || isset($blockData['id']) || isset($blockData['Height']) || isset($blockData['height']));
        if ($hasBlock) {
            $blocks[] = $blockData;
            $height = (int) ($blockData['Height'] ?? $blockData['height'] ?? $blockData['ID'] ?? $blockData['id'] ?? 0);
            for ($i = 1; $i < 10 && $height - $i >= 0; $i++) {
                $b = GndNodeApi::getBlockByNumber($height - $i);
                if (! empty($b['success']) && ! empty($b['data'])) {
                    $blocks[] = $b['data'];
                }
            }
        }

        $metrics = self::ensureBlocksCountFromChain($metrics ?? [], $blocks);

        $transactions = [];
        if (! empty($txList['success']) && isset($txList['data']['list'])) {
            $transactions = $txList['data']['list'];
        }

        return view('explorer.dashboard', [
            'metrics' => $metrics,
            'blocks' => $blocks,
            'transactions' => $transactions,
            'gndConfigured' => GndNodeApi::isConfigured(),
        ]);
    }

    /**
     * Данные дашборда в JSON для автообновления (карточки, блоки, транзакции).
     */
    public function data(): JsonResponse
    {
        $metrics = null;
        $latestBlock = ['success' => false, 'data' => null];
        $txList = ['success' => false, 'data' => null];

        if (GndNodeApi::isConfigured()) {
            $metricsResponse = GndNodeApi::getMetrics();
            $latestBlock = GndNodeApi::getLatestBlock();
            $txList = GndNodeApi::getTransactionsList(15, 0);

            if ($metricsResponse['success'] && $metricsResponse['data']) {
                $metrics = self::normalizeMetrics($metricsResponse['data']);
            }
        }

        $blocks = [];
        $blockData = $latestBlock['data'] ?? null;
        $hasBlock = $blockData && (isset($blockData['ID']) || isset($blockData['id']) || isset($blockData['Height']) || isset($blockData['height']));
        if ($hasBlock) {
            $blocks[] = $blockData;
            $height = (int) ($blockData['Height'] ?? $blockData['height'] ?? $blockData['ID'] ?? $blockData['id'] ?? 0);
            for ($i = 1; $i < 10 && $height - $i >= 0; $i++) {
                $b = GndNodeApi::getBlockByNumber($height - $i);
                if (! empty($b['success']) && ! empty($b['data'])) {
                    $blocks[] = $b['data'];
                }
            }
        }

        $metrics = self::ensureBlocksCountFromChain($metrics ?? [], $blocks);

        $transactions = [];
        if (! empty($txList['success']) && isset($txList['data']['list'])) {
            $transactions = $txList['data']['list'];
        }

        return response()->json([
            'metrics' => $metrics,
            'blocks' => $blocks,
            'transactions' => $transactions,
        ]);
    }

    /**
     * Количество блоков всегда берём из высоты последнего блока (актуальное состояние цепи).
     * Метрики ноды могут быть неверными или не обновляться.
     */
    private static function ensureBlocksCountFromChain(array $metrics, array $blocks): array
    {
        if (empty($blocks)) {
            return $metrics;
        }
        $latest = $blocks[0];
        $height = (int) ($latest['Index'] ?? $latest['index'] ?? $latest['Height'] ?? $latest['height'] ?? $latest['ID'] ?? $latest['id'] ?? 0);
        if ($height >= 0) {
            $metrics['blocks_count'] = $height;
            $metrics['total_blocks'] = $height;
        }
        return $metrics;
    }

    /**
     * Приводит ответ /api/v1/metrics (вложенные BlockMetrics, TransactionMetrics, ConsensusMetrics)
     * к плоскому массиву для шаблона.
     */
    private static function normalizeMetrics(array $data): array
    {
        $block = $data['BlockMetrics'] ?? $data['block_metrics'] ?? [];
        $tx = $data['TransactionMetrics'] ?? $data['transaction_metrics'] ?? [];
        $consensus = $data['ConsensusMetrics'] ?? $data['consensus_metrics'] ?? [];

        $totalBlocks = $block['TotalBlocks'] ?? $block['total_blocks'] ?? 0;
        $totalTx = $tx['TotalTransactions'] ?? $tx['total_transactions'] ?? 0;
        $txPerMin = $tx['TransactionsPerMinute'] ?? $tx['transactions_per_minute'] ?? 0;
        $avgFee = $tx['AverageFee'] ?? $tx['average_fee'] ?? null;
        $validators = $consensus['ValidatorsCount'] ?? $consensus['validators_count'] ?? null;

        return [
            'blocks_count' => $totalBlocks,
            'total_blocks' => $totalBlocks,
            'transactions_count' => $totalTx,
            'total_transactions' => $totalTx,
            'tps' => $txPerMin > 0 ? round($txPerMin / 60, 2) : 0,
            'transactions_per_second' => $txPerMin > 0 ? round($txPerMin / 60, 2) : 0,
            'avg_fee' => $avgFee !== null ? round((float) $avgFee, 6) : null,
            'average_gas_price' => $avgFee,
            'validators_count' => $validators,
        ];
    }
}
