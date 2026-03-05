<?php

namespace App\Http\Controllers\Explorer;

use App\Http\Controllers\Controller;
use App\Services\GndNodeApi;
use Illuminate\View\View;

class TokenController extends Controller
{
    public function show(string $address): View
    {
        $contract = GndNodeApi::getContract($address);
        $state = GndNodeApi::getContractState($address);
        $view = GndNodeApi::getContractView($address);
        if (! $contract['success'] && ! $state['success']) {
            abort(404, $contract['error'] ?? $state['error'] ?? 'Токен не найден');
        }

        return view('explorer.token', [
            'address' => $address,
            'contract' => $contract['data'],
            'state' => $state['data'],
            'contractView' => $view['data'],
        ]);
    }
}
