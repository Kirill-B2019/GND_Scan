<?php

namespace App\Http\Controllers\Explorer;

use App\Http\Controllers\Controller;
use App\Services\GndNodeApi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    private const PER_PAGE = 25;

    /**
     * Страница со списком транзакций (пагинация).
     */
    public function index(Request $request): View
    {
        $page = max(1, (int) $request->get('page', 1));
        $offset = ($page - 1) * self::PER_PAGE;

        $transactions = [];
        $total = 0;

        if (GndNodeApi::isConfigured()) {
            $response = GndNodeApi::getTransactionsList(self::PER_PAGE, $offset);
            if (! empty($response['success']) && isset($response['data']['list'])) {
                $transactions = $response['data']['list'];
            }
            if (! empty($response['data']['total'])) {
                $total = (int) $response['data']['total'];
            }
        }

        $hasMore = count($transactions) >= self::PER_PAGE;
        if ($total === 0 && $hasMore) {
            $total = $offset + count($transactions) + 1;
        }

        return view('explorer.transactions', [
            'transactions' => $transactions,
            'page' => $page,
            'perPage' => self::PER_PAGE,
            'total' => $total,
            'hasPrev' => $page > 1,
            'hasNext' => $hasMore,
            'gndConfigured' => GndNodeApi::isConfigured(),
        ]);
    }

    /**
     * Страница одной транзакции по хэшу.
     */
    public function show(string $hash): View
    {
        $tx = GndNodeApi::getTransaction($hash);
        if (! $tx['success'] || $tx['data'] === null) {
            abort(404, $tx['error'] ?? 'Транзакция не найдена');
        }

        return view('explorer.transaction', ['tx' => $tx['data']]);
    }
}
