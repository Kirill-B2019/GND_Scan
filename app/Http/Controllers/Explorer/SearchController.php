<?php

namespace App\Http\Controllers\Explorer;

use App\Http\Controllers\Controller;
use App\Services\GndNodeApi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Подсказки для выпадающего списка поиска: по формату ввода возвращаем варианты (тип, подпись, url).
     */
    public function suggest(Request $request): JsonResponse
    {
        $q = trim(preg_replace('/\s+/', '', (string) $request->input('q', '')));
        $suggestions = [];

        if (strlen($q) < 1) {
            return response()->json(['suggestions' => []]);
        }

        if (is_numeric($q)) {
            $suggestions[] = [
                'type' => 'block',
                'label' => 'Блок #' . $q,
                'url' => route('explorer.block.show', ['number' => $q]),
            ];
        }

        if (strlen($q) === 64 && ctype_xdigit($q)) {
            $short = substr($q, 0, 10) . '…' . substr($q, -6);
            $suggestions[] = [
                'type' => 'transaction',
                'label' => 'Транзакция ' . $short,
                'url' => route('explorer.transaction.show', ['hash' => $q]),
            ];
        }

        if (preg_match('/^(GN_|GND|GNDct|0x)[a-zA-Z0-9]+$/i', $q)) {
            $addr = str_starts_with(strtolower($q), '0x') ? 'GND' . substr($q, 2) : $q;
            $suggestions[] = [
                'type' => 'address',
                'label' => 'Адрес ' . (strlen($addr) > 20 ? substr($addr, 0, 10) . '…' . substr($addr, -8) : $addr),
                'url' => route('explorer.address.show', ['address' => $addr]),
            ];
            $suggestions[] = [
                'type' => 'contract',
                'label' => 'Контракт ' . (strlen($addr) > 20 ? substr($addr, 0, 10) . '…' : $addr),
                'url' => route('explorer.contract.show', ['address' => $addr]),
            ];
        }

        return response()->json(['suggestions' => $suggestions]);
    }

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
