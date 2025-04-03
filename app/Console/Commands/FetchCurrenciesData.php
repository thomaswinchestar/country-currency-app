<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchCurrenciesData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:currencies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch currencies data from public API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching currencies data...');

        // Using Open Exchange Rates API (you'll need an API key)
        // First, get currency symbols and names
        $symbolsResponse = Http::get('https://openexchangerates.org/api/currencies.json');

        if (!$symbolsResponse->successful()) {
            $this->error('Failed to fetch currency symbols');
            return;
        }

        $currencyNames = $symbolsResponse->json();

        // Then get latest exchange rates
        $ratesResponse = Http::get('https://openexchangerates.org/api/latest.json', [
            'app_id' => env('OPEN_EXCHANGE_RATES_API_KEY', '6fd1dcd40abe4321b2bde233ca30c0c4')
        ]);

        if (!$ratesResponse->successful()) {
            $this->error('Failed to fetch exchange rates');
            return;
        }

        $ratesData = $ratesResponse->json();
        $rates = $ratesData['rates'] ?? [];
        $timestamp = $ratesData['timestamp'] ?? time();

        $count = 0;
        foreach ($rates as $code => $rate) {
            if (isset($currencyNames[$code])) {
                try {
                    Currency::updateOrCreate(
                        ['code' => $code],
                        [
                            'name' => $currencyNames[$code],
                            'rate' => $rate,
                            'last_updated' => date('Y-m-d H:i:s', $timestamp)
                        ]
                    );
                    $count++;
                } catch (\Exception $e) {
                    $this->error("Error adding currency $code: {$e->getMessage()}");
                }
            }
        }

        $this->info("Added/updated $count currencies");
    }
}
