<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchCountriesData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:countries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch countries data from public API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching countries data...');

        // Using the RestCountries API
        $response = Http::get('https://restcountries.com/v3.1/all');

        if ($response->successful()) {
            $countries = $response->json();
            $count = 0;

            foreach ($countries as $countryData) {
                try {
                    // Skip if essential data is incomplete (we need at least a name and a 2-letter code)
                    if (!isset($countryData['name']['common']) || !isset($countryData['cca2'])) {
                        $this->warn("Skipping country with incomplete data: " . json_encode(array_keys($countryData)));
                        continue;
                    }

                    // Extract capital safely
                    $capital = null;
                    if (isset($countryData['capital']) && is_array($countryData['capital']) && !empty($countryData['capital'])) {
                        $capital = $countryData['capital'][0];
                    }

                    // Extract coordinates safely
                    $latitude = null;
                    $longitude = null;
                    if (isset($countryData['latlng']) && is_array($countryData['latlng']) && count($countryData['latlng']) >= 2) {
                        $latitude = $countryData['latlng'][0];
                        $longitude = $countryData['latlng'][1];
                    }

                    Country::updateOrCreate(
                        ['code' => $countryData['cca2']],
                        [
                            'name' => $countryData['name']['common'],
                            'capital' => $capital,
                            'region' => $countryData['region'] ?? null,
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                        ]
                    );
                    $count++;
                } catch (\Exception $e) {
                    $countryName = $countryData['name']['common'] ?? 'Unknown';
                    $this->error("Error adding country {$countryName}: {$e->getMessage()}");
                }
            }

            $this->info("Added/updated $count countries");
        } else {
            $this->error('Failed to fetch countries data');
        }
    }
}
