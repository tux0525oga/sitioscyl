<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $newQuoteCount = QuoteRequest::query()
            ->whereHas('status', function ($query): void {
                $query->where('code', 'New');
            })
            ->count();

        $openQuoteCount = QuoteRequest::query()
            ->whereHas('status', function ($query): void {
                $query->where('isClosed', false);
            })
            ->count();

        $recentQuotes = QuoteRequest::query()
            ->with([
                'contact',
                'status',
            ])
            ->orderByDesc('createdAt')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'newQuoteCount' => $newQuoteCount,
            'openQuoteCount' => $openQuoteCount,
            'recentQuotes' => $recentQuotes,
        ]);
    }
}