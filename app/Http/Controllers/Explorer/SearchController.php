<?php

namespace App\Http\Controllers\Explorer;

use App\Http\Controllers\Controller;
use App\Services\GndNodeApi;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class SearchController extends Controller
{
    public function search(Request $request): RedirectResponse
    {
        $q = trim((string) $request->input('q', ''));
        if ($q === '') {
            return redirect()->route('explorer.dashboard');
        }

        $q = preg_replace('/\s+/', '', $q);

        if (is_numeric($q)) {
            return redirect()->route('explorer.block.show', ['number' => $q]);
        }

        if (strlen($q) === 64 && ctype_xdigit($q)) {
            return redirect()->route('explorer.transaction.show', ['hash' => $q]);
        }

        if (preg_match('/^(GN_|GND|GNDct|0x)[a-zA-Z0-9]+$/i', $q)) {
            $addr = $q;
            if (str_starts_with(strtolower($q), '0x')) {
                $addr = 'GND' . substr($q, 2);
            }
            $contract = GndNodeApi::getContract($addr);
            if ($contract['success'] && $contract['data']) {
                return redirect()->route('explorer.contract.show', ['address' => $addr]);
            }

            return redirect()->route('explorer.address.show', ['address' => $addr]);
        }

        return redirect()->route('explorer.dashboard')->with('message', 'Ничего не найдено по запросу: ' . $q);
    }
}
