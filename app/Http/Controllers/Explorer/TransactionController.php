<?php

namespace App\Http\Controllers\Explorer;

use App\Http\Controllers\Controller;
use App\Services\GndNodeApi;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function show(string $hash): View
    {
        $tx = GndNodeApi::getTransaction($hash);
        if (! $tx['success'] || $tx['data'] === null) {
            abort(404, $tx['error'] ?? 'Транзакция не найдена');
        }

        return view('explorer.transaction', ['tx' => $tx['data']]);
    }
}
