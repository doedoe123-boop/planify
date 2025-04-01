<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteRequest;
use App\Models\Quote;
use App\Models\WebsiteType;
use App\Services\QuoteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class QuoteController extends Controller
{
    protected $quoteService;

    public function __construct(QuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
    }

    public function index()
    {
        $quotes = Quote::with(['website_type'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return Inertia::render('Quotes/Index', [
            'quotes' => $quotes
        ]);
    }

    public function create()
    {
        return Inertia::render('Quotes/Create', [
            'websiteTypes' => $this->quoteService->getWebsiteTypesWithFeatures()
        ]);
    }

    public function store(QuoteRequest $request)
    {
        $quote = $this->quoteService->createQuote($request->validated());

        return redirect()->route('quotes.show', $quote)
            ->with('success', 'Quote generated successfully!');
    }

    public function show(Quote $quote)
    {
        // Ensure the user can only view their own quotes
        if ($quote->user_id !== auth()->id()) {
            abort(403);
        }

        return Inertia::render('Quotes/Show', [
            'quote' => $quote->load(['website_type', 'selected_features'])
        ]);
    }
} 