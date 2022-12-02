<?php

namespace App\Jobs;

use App\Models\LoggedRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * @method static void dispatch(string $requestDetails)
 */
class LogRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param  string  $requestDetails
     * @return void
     */
    public function __construct(public string $requestDetails)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        LoggedRequest::create([
            'request' => $this->requestDetails,
        ]);
    }
}
