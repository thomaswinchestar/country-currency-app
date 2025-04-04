<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        
        $query = Currency::query();
        
        if ($search) {
            // Split the search term into keywords
            $keywords = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);
            
            $query->where(function($q) use ($search, $keywords) {
                // Search for the full term
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
                  
                // Also search for each keyword individually
                foreach ($keywords as $keyword) {
                    $q->orWhere('name', 'like', "%{$keyword}%")
                      ->orWhere('code', 'like', "%{$keyword}%");
                }
            });
        }
        
        $currencies = $query->orderBy('name')
            ->paginate(15)
            ->withQueryString()
            ->through(function ($currency) {
                return [
                    'id' => $currency->id,
                    'code' => $currency->code,
                    'name' => $currency->name,
                    'symbol' => $currency->symbol,
                    'rate' => $currency->rate,
                    'last_updated' => $currency->last_updated ? $currency->last_updated->format('Y-m-d H:i:s') : null,
                ];
            });

        return Inertia::render('Currencies/Index', [
            'currencies' => $currencies,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Currencies/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|size:3|unique:currencies',
            'name' => 'required|string|max:255',
            'symbol' => 'nullable|string|max:10',
            'rate' => 'nullable|numeric',
        ]);

        $validated['last_updated'] = now();

        Currency::create($validated);

        return redirect()->route('currencies.index')
            ->with('message', 'Currency created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Currency $currency)
    {
        return Inertia::render('Currencies/Show', [
            'currency' => [
                'id' => $currency->id,
                'code' => $currency->code,
                'name' => $currency->name,
                'symbol' => $currency->symbol,
                'rate' => $currency->rate,
                'last_updated' => $currency->last_updated ? $currency->last_updated->format('Y-m-d H:i:s') : null,
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Currency $currency)
    {
        return Inertia::render('Currencies/Edit', [
            'currency' => $currency
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Currency $currency)
    {
        $validated = $request->validate([
            'code' => 'required|string|size:3|unique:currencies,code,' . $currency->id,
            'name' => 'required|string|max:255',
            'symbol' => 'nullable|string|max:10',
            'rate' => 'nullable|numeric',
        ]);

        $validated['last_updated'] = now();

        $currency->update($validated);

        return redirect()->route('currencies.index')
            ->with('message', 'Currency updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Currency $currency)
    {
        $currency->delete();

        return redirect()->route('currencies.index')
            ->with('message', 'Currency deleted successfully');
    }
}
