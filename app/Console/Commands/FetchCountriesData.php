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
                    // Skip if data is incomplete
                    if (!isset($countryData['cca3']) || !isset($countryData['name']['common'])) {
                        continue;
                    }

                    Country::updateOrCreate(
                        ['code' => $countryData['cca3']],
                        [
                            'name' => $countryData['name']['common'],
                            'capital' => $countryData['capital'][0] ?? null,
                            'region' => $countryData['region'] ?? null,
                            'latitude' => $countryData['latlng'][0] ?? null,
                            'longitude' => $countryData['latlng'][1] ?? null,
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
