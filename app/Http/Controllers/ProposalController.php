<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ProposalController extends Controller
{
    public function index()
    {
        return Inertia::render('Proposals/Index');
    }

    public function create()
    {
        return Inertia::render('Proposals/Create');
    }

    public function store(Request $request)
    {
        // TODO: Implement proposal creation logic
        return redirect()->route('proposals');
    }
} 