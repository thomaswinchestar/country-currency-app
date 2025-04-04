<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchCitiesData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:cities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch cities data for each country';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching cities data...');

        // Get all countries ordered by name
        $countries = Country::orderBy('name', 'asc')->get();
        $totalCities = 0;

        foreach ($countries as $country) {
            $this->info("Fetching cities for {$country->name}...");

            // Using GeoDB Cities API (you'll need a RapidAPI key)
            // Explicitly request cities endpoint with type=CITY parameter for better filtering
            $response = Http::withHeaders([
                'X-RapidAPI-Host' => 'wft-geo-db.p.rapidapi.com',
                'X-RapidAPI-Key' => env('RAPID_API_KEY')
            ])->get("https://wft-geo-db.p.rapidapi.com/v1/geo/countries/{$country->code}/cities", [
                'limit' => 10, // Adjust as needed
                'sort' => 'population', // Sort by population to get major cities
                'types' => 'CITY' // Ensure we only get cities
            ]);

            if ($response->successful()) {
                $citiesData = $response->json()['data'] ?? [];
                $citiesAdded = 0;
                
                foreach ($citiesData as $cityData) {
                    // Double-check that we're only processing cities
                    // This is a safety check in case the API returns non-city data
                    if (isset($cityData['type']) && $cityData['type'] !== 'CITY') {
                        $this->info("  Skipping {$cityData['name']} as it's not a city (type: {$cityData['type']})");
                        continue;
                    }
                    
                    City::updateOrCreate(
                        [
                            'country_id' => $country->id,
                            'name' => $cityData['name']
                        ],
                        [
                            'latitude' => $cityData['latitude'] ?? null,
                            'longitude' => $cityData['longitude'] ?? null,
                            'state' => $cityData['region'] ?? null,
                            'population' => $cityData['population'] ?? null,
                        ]
                    );
                    $citiesAdded++;
                    $totalCities++;
                }
                
                // If no cities were added from the API and the country has a capital, add it as a fallback
                if ($citiesAdded === 0 && !empty($country->capital)) {
                    $this->info("  - No cities found from API, adding capital city: {$country->capital}");
                    
                    City::updateOrCreate(
                        [
                            'country_id' => $country->id,
                            'name' => $country->capital
                        ],
                        [
                            'latitude' => $country->latitude, // Use country coordinates
                            'longitude' => $country->longitude,
                            'state' => null,
                            'population' => null, // We don't have population data for capitals added as fallback
                        ]
                    );
                    $totalCities++;
                }
            } else {
                $this->error("Failed to fetch cities for {$country->name}: " . $response->body());
                
                // If API call fails but country has a capital, add it as a fallback
                if (!empty($country->capital)) {
                    $this->info("  - Adding capital city as fallback: {$country->capital}");
                    
                    City::updateOrCreate(
                        [
                            'country_id' => $country->id,
                            'name' => $country->capital
                        ],
                        [
                            'latitude' => $country->latitude, // Use country coordinates
                            'longitude' => $country->longitude,
                            'state' => null,
                            'population' => null, // We don't have population data for capitals added as fallback
                        ]
                    );
                    $totalCities++;
                }
            }

            // Delay to prevent API rate limiting
            sleep(1);
        }

        $this->info("Added/updated $totalCities cities");
    }
}
