<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchRandomUserData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Send HTTP request to the randomuser API
        $response = Http::get('https://randomuser.me/api/');

        // Check if the request was successful
        if ($response->successful()) {
            // Extract and log only the "results" part of the response
            $results = $response->json('results');
            Log::info('Random User Data:', $results);
        } else {
            Log::error('Failed to fetch data from randomuser API.');
        }
    }
}
