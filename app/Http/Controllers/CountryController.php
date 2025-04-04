<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        
        $query = Country::query();
        
        if ($search) {
            // Split the search term into keywords
            $keywords = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);
            
            $query->where(function($q) use ($search, $keywords) {
                // Search for the full term
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('capital', 'like', "%{$search}%")
                  ->orWhere('region', 'like', "%{$search}%");
                  
                // Also search for each keyword individually
                foreach ($keywords as $keyword) {
                    $q->orWhere('name', 'like', "%{$keyword}%")
                      ->orWhere('code', 'like', "%{$keyword}%")
                      ->orWhere('capital', 'like', "%{$keyword}%")
                      ->orWhere('region', 'like', "%{$keyword}%");
                }
            });
        }
        
        $countries = $query->orderBy('name')
            ->paginate(15)
            ->withQueryString()
            ->through(function ($country) {
                return [
                    'id' => $country->id,
                    'name' => $country->name,
                    'code' => $country->code,
                    'capital' => $country->capital,
                    'region' => $country->region,
                ];
            });

        return Inertia::render('Countries/Index', [
            'countries' => $countries,
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
        return Inertia::render('Countries/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|size:3|unique:countries',
            'capital' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        Country::create($validated);

        return redirect()->route('countries.index')
            ->with('message', 'Country created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $country = Country::findOrFail($id);
        return Inertia::render('Countries/Show', [
            'country' => [
                'id' => $country->id,
                'name' => $country->name,
                'code' => $country->code,
                'capital' => $country->capital,
                'region' => $country->region,
                'latitude' => $country->latitude,
                'longitude' => $country->longitude,
                'cities' => $country->cities->map(function ($city) {
                    return [
                        'id' => $city->id,
                        'name' => $city->name,
                        'state' => $city->state,
                    ];
                }),
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $country = Country::findOrFail($id);
        return Inertia::render('Countries/Edit', [
            'country' => $country
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $country = Country::findOrFail($id);
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|size:3|unique:countries,code,'.$country->id,
            'capital' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $country->update($validated);

        return redirect()->route('countries.index')
            ->with('message', 'Country updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $country = Country::findOrFail($id);
        $country->delete();

        return redirect()->route('countries.index')
            ->with('message', 'Country deleted successfully');
    }
}
