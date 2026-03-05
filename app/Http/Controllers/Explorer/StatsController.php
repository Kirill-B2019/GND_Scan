<?php

namespace App\Http\Controllers\Explorer;

use App\Http\Controllers\Controller;
use App\Services\GndNodeApi;
use Illuminate\View\View;

class StatsController extends Controller
{
    public function index(): View
    {
        $metrics = GndNodeApi::isConfigured() ? GndNodeApi::getMetrics() : ['success' => false, 'data' => null];
        $txMetrics = GndNodeApi::isConfigured() ? GndNodeApi::getTransactionMetrics() : ['success' => false, 'data' => null];
        $feeMetrics = GndNodeApi::isConfigured() ? GndNodeApi::getFeeMetrics() : ['success' => false, 'data' => null];

        $sections = self::buildReadableSections(
            $metrics['data'] ?? null,
            $txMetrics['data'] ?? null,
            $feeMetrics['data'] ?? null
        );

        return view('explorer.stats', [
            'sections' => $sections,
            'gndConfigured' => GndNodeApi::isConfigured(),
        ]);
    }

    /**
     * Собирает секции с читабельными подписями и значениями для страницы /stats.
     */
    private static function buildReadableSections($metrics, $txMetrics, $feeMetrics): array
    {
        $sections = [];

        if ($metrics && is_array($metrics)) {
            $block = $metrics['BlockMetrics'] ?? $metrics['block_metrics'] ?? [];
            $consensus = $metrics['ConsensusMetrics'] ?? $metrics['consensus_metrics'] ?? [];

            $rows = [];
            if (isset($block['TotalBlocks']) || isset($block['total_blocks'])) {
                $v = $block['TotalBlocks'] ?? $block['total_blocks'] ?? 0;
                $rows[] = ['Блоков всего', number_format((int) $v, 0, ',', ' ')];
            }
            if (isset($block['BlocksPerMinute']) || isset($block['blocks_per_minute'])) {
                $v = $block['BlocksPerMinute'] ?? $block['blocks_per_minute'] ?? 0;
                $rows[] = ['Блоков в минуту', round((float) $v, 2)];
            }
            if (isset($block['GasUsed']) || isset($block['gas_used'])) {
                $rows[] = ['Gas использовано', number_format((int) ($block['GasUsed'] ?? $block['gas_used'] ?? 0), 0, ',', ' ')];
            }
            if (isset($block['GasLimit']) || isset($block['gas_limit'])) {
                $rows[] = ['Gas лимит', number_format((int) ($block['GasLimit'] ?? $block['gas_limit'] ?? 0), 0, ',', ' ')];
            }
            if (isset($consensus['ValidatorsCount']) || isset($consensus['validators_count'])) {
                $v = $consensus['ValidatorsCount'] ?? $consensus['validators_count'] ?? 0;
                $rows[] = ['Валидаторов', $v];
            }
            if (isset($consensus['ActiveValidators']) || isset($consensus['active_validators'])) {
                $rows[] = ['Активных валидаторов', $consensus['ActiveValidators'] ?? $consensus['active_validators'] ?? 0];
            }
            if (! empty($rows)) {
                $sections[] = ['title' => 'Блоки и сеть', 'rows' => $rows];
            }
        }

        if ($txMetrics && is_array($txMetrics)) {
            $rows = [];
            if (isset($txMetrics['TotalTransactions']) || isset($txMetrics['total_transactions'])) {
                $v = $txMetrics['TotalTransactions'] ?? $txMetrics['total_transactions'] ?? 0;
                $rows[] = ['Всего транзакций', number_format((int) $v, 0, ',', ' ')];
            }
            if (isset($txMetrics['TransactionsPerMinute']) || isset($txMetrics['transactions_per_minute'])) {
                $v = $txMetrics['TransactionsPerMinute'] ?? $txMetrics['transactions_per_minute'] ?? 0;
                $rows[] = ['Транзакций в минуту', round((float) $v, 2)];
            }
            if (isset($txMetrics['PendingTransactions']) || isset($txMetrics['pending_transactions'])) {
                $rows[] = ['В ожидании', $txMetrics['PendingTransactions'] ?? $txMetrics['pending_transactions'] ?? 0];
            }
            if (isset($txMetrics['FailedTransactions']) || isset($txMetrics['failed_transactions'])) {
                $rows[] = ['Неуспешных', $txMetrics['FailedTransactions'] ?? $txMetrics['failed_transactions'] ?? 0];
            }
            if (isset($txMetrics['LastMinuteCount']) || isset($txMetrics['last_minute_count'])) {
                $rows[] = ['За последнюю минуту', $txMetrics['LastMinuteCount'] ?? $txMetrics['last_minute_count'] ?? 0];
            }
            if (isset($txMetrics['LastHourCount']) || isset($txMetrics['last_hour_count'])) {
                $rows[] = ['За последний час', $txMetrics['LastHourCount'] ?? $txMetrics['last_hour_count'] ?? 0];
            }
            if (! empty($rows)) {
                $sections[] = ['title' => 'Транзакции', 'rows' => $rows];
            }
        }

        if ($feeMetrics && is_array($feeMetrics)) {
            $rows = [];
            if (isset($feeMetrics['AverageFee']) || isset($feeMetrics['average_fee'])) {
                $v = $feeMetrics['AverageFee'] ?? $feeMetrics['average_fee'] ?? 0;
                $rows[] = ['Средняя комиссия (GND)', round((float) $v, 6)];
            }
            if (isset($feeMetrics['MinFee']) || isset($feeMetrics['min_fee'])) {
                $v = $feeMetrics['MinFee'] ?? $feeMetrics['min_fee'];
                $rows[] = ['Минимальная комиссия', self::formatFee($v)];
            }
            if (isset($feeMetrics['MaxFee']) || isset($feeMetrics['max_fee'])) {
                $v = $feeMetrics['MaxFee'] ?? $feeMetrics['max_fee'];
                $rows[] = ['Максимальная комиссия', self::formatFee($v)];
            }
            if (isset($feeMetrics['TotalFee']) || isset($feeMetrics['total_fee'])) {
                $v = $feeMetrics['TotalFee'] ?? $feeMetrics['total_fee'];
                $rows[] = ['Всего комиссий собрано', self::formatFee($v)];
            }
            if (! empty($rows)) {
                $sections[] = ['title' => 'Комиссии', 'rows' => $rows];
            }
        }

        return $sections;
    }

    private static function formatFee($v): string
    {
        if ($v === null || $v === '') {
            return '—';
        }
        if (is_array($v)) {
            return isset($v['value']) ? number_format((float) $v['value'], 6) . ' GND' : '—';
        }
        if (is_numeric($v)) {
            return number_format((float) $v, 6) . ' GND';
        }
        return (string) $v;
    }
}
