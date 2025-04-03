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

        // Get all countries
        $countries = Country::all();
        $totalCities = 0;

        foreach ($countries as $country) {
            $this->info("Fetching cities for {$country->name}...");

            // Using GeoDB Cities API (you'll need a RapidAPI key)
            $response = Http::withHeaders([
                'X-RapidAPI-Host' => 'wft-geo-db.p.rapidapi.com',
                'X-RapidAPI-Key' => env('RAPID_API_KEY', 'f40f6a0747msh16d59d9a727f32cp1b2429jsn3e70abbc1687')
            ])->get("https://wft-geo-db.p.rapidapi.com/v1/geo/countries/{$country->code}/cities", [
                'limit' => 10 // Adjust as needed
            ]);

            if ($response->successful()) {
                $citiesData = $response->json()['data'] ?? [];

                foreach ($citiesData as $cityData) {
                    City::updateOrCreate(
                        [
                            'country_id' => $country->id,
                            'name' => $cityData['name']
                        ],
                        [
                            'latitude' => $cityData['latitude'] ?? null,
                            'longitude' => $cityData['longitude'] ?? null,
                            'state' => $cityData['region'] ?? null,
                        ]
                    );
                    $totalCities++;
                }
            } else {
                $this->error("Failed to fetch cities for {$country->name}: " . $response->body());
            }

            // Delay to prevent API rate limiting
            sleep(1);
        }

        $this->info("Added/updated $totalCities cities");
    }
}
