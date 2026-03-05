<?php

namespace App\Http\Controllers\Explorer;

use App\Http\Controllers\Controller;
use App\Services\GndNodeApi;
use Illuminate\View\View;

class ContractController extends Controller
{
    public function show(string $address): View
    {
        $contract = GndNodeApi::getContract($address);
        $view = GndNodeApi::getContractView($address);
        if (! $contract['success'] && ! $view['success']) {
            abort(404, $contract['error'] ?? $view['error'] ?? 'Контракт не найден');
        }

        return view('explorer.contract', [
            'address' => $address,
            'contract' => $contract['data'],
            'contractView' => $view['data'],
        ]);
    }
}
