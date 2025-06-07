<?php

namespace App\Jobs\GenerateCatalog;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GeneratePointsJob extends AbstractJob
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // $f = 1 / 0; // !! РОЗКОМЕНТУЙТЕ ЦЕЙ РЯДОК ПІСЛЯ ПЕРШОГО УСПІШНОГО ЗАПУСКУ ЛАНЦЮГА, ЩОБ СИМУЛЮВАТИ ПОМИЛКУ !!

        parent::handle();
    }
}
