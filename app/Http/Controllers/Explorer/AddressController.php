<?php

namespace App\Http\Controllers\Explorer;

use App\Http\Controllers\Controller;
use App\Services\GndNodeApi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function show(Request $request, string $address): View
    {
        $balance = GndNodeApi::getWalletBalance($address);
        $txList = GndNodeApi::getTransactionsList(50, 0);
        $transactions = [];
        if ($txList['success'] && isset($txList['data']['list'])) {
            $transactions = array_filter($txList['data']['list'], function ($tx) use ($address) {
                $from = $tx['sender'] ?? '';
                $to = $tx['recipient'] ?? '';

                return stripos($from, $address) !== false || stripos($to, $address) !== false;
            });
        }

        return view('explorer.address', [
            'address' => $address,
            'balance' => $balance['data'] ?? null,
            'transactions' => array_slice($transactions, 0, 25),
            'balanceError' => $balance['error'] ?? null,
        ]);
    }
}
