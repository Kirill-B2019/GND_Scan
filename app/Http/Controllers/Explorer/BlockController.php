<?php

namespace App\Http\Controllers\Explorer;

use App\Http\Controllers\Controller;
use App\Services\GndNodeApi;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;

class BlockController extends Controller
{
    /**
     * Показать блок по номеру (index/height). При отсутствии блока на ноде — кастомная страница 404.
     */
    public function show(Request $request, string $number): View|Response
    {
        $block = GndNodeApi::getBlockByNumber($number);
        if (! $block['success'] || $block['data'] === null) {
            return response()->view('explorer.block_not_found', [
                'number' => $number,
                'error' => $block['error'] ?? 'Блок не найден',
            ], 404);
        }

        return view('explorer.block', ['block' => $block['data']]);
    }
}
