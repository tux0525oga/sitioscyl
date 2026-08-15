<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminQuoteRequestController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $quotes = QuoteRequest::query()
            ->with(['contact', 'status', 'serviceLinks.service'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('folio', 'like', "%{$search}%")
                        ->orWhereHas('contact', function ($query) use ($search): void {
                            $query
                                ->where('firstName', 'like', "%{$search}%")
                                ->orWhere('lastName', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('phoneNumber', 'like', "%{$search}%")
                                ->orWhere('whatsAppNumber', 'like', "%{$search}%");
                        });
                });
            })
            ->orderByDesc('createdAt')
            ->paginate(20)
            ->withQueryString();

        return view('admin.quotes.index', [
            'quotes' => $quotes,
            'search' => $search,
        ]);
    }
}
