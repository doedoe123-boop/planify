<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteRequest;
use App\Http\Requests\UpdateQuoteTasksRequest;
use App\Models\Quote;
use App\Models\WebsiteType;
use App\Services\QuoteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

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
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return Inertia::render('Quotes/Index', [
            'quotes' => $quotes
        ]);
    }

    public function create()
    {
        // Get available industries
        $industries = [
            'Ecommerce',
            'Healthcare',
            'Education',
            'Real-Estate',
            'Finance',
            'Technology',
            'Manufacturing',
            'Hospitality',
            'Non-Profit',
            'Other'
        ];
        
        return Inertia::render('Quotes/Create', [
            'websiteTypes' => $this->quoteService->getWebsiteTypesWithFeatures(),
            'industries' => $industries
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
        if ($quote->user_id !== Auth::id()) {
            abort(403);
        }

        // Get tasks breakdown by feature
        $tasksBreakdown = $this->quoteService->getTasksBreakdown($quote);
        
        // Get deliverables list
        $deliverables = $this->quoteService->getDeliverables($quote);

        return Inertia::render('Quotes/Show', [
            'quote' => $quote->load(['website_type', 'selected_features']),
            'tasksBreakdown' => $tasksBreakdown,
            'deliverables' => $deliverables
        ]);
    }
    
    public function updateTasks(UpdateQuoteTasksRequest $request, Quote $quote)
    {
        // Ensure the user can only modify their own quotes
        if ($quote->user_id !== Auth::id()) {
            abort(403);
        }
        
        $quote = $this->quoteService->updateQuoteTasks($quote, $request->tasks);
        
        return redirect()->route('quotes.show', $quote)
            ->with('success', 'Quote tasks updated successfully!');
    }
    
    /**
     * Get suggested features based on project description and industry
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSuggestedFeatures(Request $request)
    {
        $request->validate([
            'project_description' => 'required|string',
            'industry' => 'nullable|string'
        ]);
        
        $suggestions = $this->quoteService->getSuggestedFeatures(
            $request->project_description,
            $request->industry
        );
        
        return response()->json(['suggestions' => $suggestions]);
    }
    
    /**
     * Generate and download PDF for a quote
     * 
     * @param Quote $quote
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf(Quote $quote)
    {
        // Ensure the user can only download their own quotes
        if ($quote->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Load all required relationships
        $quote->load(['website_type', 'selected_features', 'tasks', 'user']);
        
        // Get tasks breakdown by feature
        $taskBreakdown = collect($this->quoteService->getTasksBreakdown($quote))
            ->flatten(1)
            ->values()
            ->all();
        
        // Get deliverables list
        $deliverables = $this->quoteService->getDeliverables($quote);
        
        // Calculate total amount
        $totalAmount = $quote->total_cost;
        
        // Generate PDF
        $pdf = PDF::loadView('pdf.quote', [
            'quote' => $quote,
            'taskBreakdown' => $taskBreakdown,
            'deliverables' => $deliverables,
            'date' => now()->format('F d, Y'),
            'totalAmount' => $totalAmount
        ]);
        
        // Set PDF options
        $pdf->setPaper('a4');
        $pdf->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
        ]);
        
        return $pdf->download("quote-{$quote->id}-{$quote->project_name}.pdf");
    }
}