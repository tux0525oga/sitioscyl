<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use App\Models\QuoteStatus;
use App\Services\QuoteRequestManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminQuoteRequestController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim(
            (string) $request->query('search', '')
        );

        $quotes = QuoteRequest::query()
            ->with([
                'contact',
                'status',
                'serviceLinks.service',
            ])
            ->when(
                $search !== '',
                function ($query) use ($search): void {
                    $query->where(
                        function ($query) use ($search): void {
                            $query
                                ->where(
                                    'folio',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhereHas(
                                    'contact',
                                    function ($query) use ($search): void {
                                        $query
                                            ->where(
                                                'firstName',
                                                'like',
                                                "%{$search}%"
                                            )
                                            ->orWhere(
                                                'lastName',
                                                'like',
                                                "%{$search}%"
                                            )
                                            ->orWhere(
                                                'email',
                                                'like',
                                                "%{$search}%"
                                            )
                                            ->orWhere(
                                                'phoneNumber',
                                                'like',
                                                "%{$search}%"
                                            )
                                            ->orWhere(
                                                'whatsAppNumber',
                                                'like',
                                                "%{$search}%"
                                            );
                                    }
                                );
                        }
                    );
                }
            )
            ->orderByDesc('createdAt')
            ->paginate(20)
            ->withQueryString();

        return view('admin.quotes.index', [
            'quotes' => $quotes,
            'search' => $search,
        ]);
    }

    public function show(
        QuoteRequest $quoteRequest
    ): View {
        $quoteRequest->load([
            'contact.preferredContactMethod',
            'preferredTimeframe',
            'referenceProject',
            'status',
            'serviceLinks.service',
            'notes.userAccount',
            'statusHistory.status',
            'statusHistory.changedByUser',
            'files.category',
        ]);

        $statuses = QuoteStatus::query()
            ->where('isActive', true)
            ->orderBy('displayOrder')
            ->get();

        return view('admin.quotes.show', [
            'quote' => $quoteRequest,
            'statuses' => $statuses,
        ]);
    }

    public function updateStatus(
        Request $request,
        QuoteRequest $quoteRequest,
        QuoteRequestManager $quoteRequestManager
    ): RedirectResponse {
        $validated = $request->validate([
            'statusCode' => [
                'required',
                'string',
                'max:80',
            ],
        ]);

        $quoteRequestManager->changeStatus(
            $quoteRequest->quoteRequestId,
            $validated['statusCode'],
            (string) $request->user()->userId
        );

        return redirect()
            ->route(
                'admin.quotes.show',
                $quoteRequest
            )
            ->with(
                'success',
                'Estado actualizado correctamente.'
            );
    }

    public function storeNote(
        Request $request,
        QuoteRequest $quoteRequest,
        QuoteRequestManager $quoteRequestManager
    ): RedirectResponse {
        $validated = $request->validate([
            'noteText' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        $quoteRequestManager->addInternalNote(
            $quoteRequest->quoteRequestId,
            $validated['noteText'],
            (string) $request->user()->userId
        );

        return redirect()
            ->route(
                'admin.quotes.show',
                $quoteRequest
            )
            ->with(
                'success',
                'Nota interna agregada correctamente.'
            );
    }
}
