<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        
        $query = City::with('country');
        
        if ($search) {
            // Split the search term into keywords
            $keywords = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);
            
            $query->where(function($q) use ($search, $keywords) {
                // Search for the full term in city name or state
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('state', 'like', "%{$search}%")
                  // Also search for the beginning of words
                  ->orWhere('name', 'like', "{$search}%")
                  ->orWhere('state', 'like', "{$search}%");
                  
                // Also search in country name via relationship
                $q->orWhereHas('country', function($countryQuery) use ($search) {
                    $countryQuery->where('name', 'like', "%{$search}%")
                                 ->orWhere('name', 'like', "{$search}%");
                });
                
                // Also search for each keyword individually
                foreach ($keywords as $keyword) {
                    $q->orWhere('name', 'like', "%{$keyword}%")
                      ->orWhere('state', 'like', "%{$keyword}%")
                      // Add beginning of word matches for keywords
                      ->orWhere('name', 'like', "{$keyword}%")
                      ->orWhere('state', 'like', "{$keyword}%")
                      ->orWhereHas('country', function($countryQuery) use ($keyword) {
                        $countryQuery->where('name', 'like', "%{$keyword}%")
                                     ->orWhere('name', 'like', "{$keyword}%");
                    });
                }
                
                // For single character searches, be more specific
                if (strlen($search) === 1) {
                    $q->orWhere('name', 'like', "{$search}%");
                }
            });
        }
        
        $cities = $query->orderBy('name')
            ->paginate(15)
            ->withQueryString()
            ->through(function ($city) {
                return [
                    'id' => $city->id,
                    'name' => $city->name,
                    'state' => $city->state,
                    'latitude' => $city->latitude,
                    'longitude' => $city->longitude,
                    'population' => $city->population,
                    'country' => [
                        'id' => $city->country->id,
                        'name' => $city->country->name,
                    ]
                ];
            });

        return Inertia::render('Cities/Index', [
            'cities' => $cities,
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
        $countries = Country::orderBy('name')->get(['id', 'name']);

        return Inertia::render('Cities/Create', [
            'countries' => $countries
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        City::create($validated);

        return redirect()->route('cities.index')
            ->with('message', 'City created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        return Inertia::render('Cities/Show', [
            'city' => [
                'id' => $city->id,
                'name' => $city->name,
                'state' => $city->state,
                'country' => [
                    'id' => $city->country->id,
                    'name' => $city->country->name,
                ]
            ]
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(City $city)
    {
        $countries = Country::orderBy('name')->get(['id', 'name']);

        return Inertia::render('Cities/Edit', [
            'city' => $city,
            'countries' => $countries
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, City $city)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $city->update($validated);

        return redirect()->route('cities.index')
            ->with('message', 'City updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        $city->delete();

        return redirect()->route('cities.index')
            ->with('message', 'City deleted successfully');
    }
}
