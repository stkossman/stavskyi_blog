<?php

namespace App\Jobs\GenerateCatalog;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

abstract class AbstractJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        $this->onQueue('generate-catalog');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->debug('done');
    }

    /**
     * Хелпер для запису в лог з інформацією про клас Job.
     * @param string $msg
     * @return void
     */
    protected function debug(string $msg): void
    {
        $class = static::class;
        $msg = $msg . " [{$class}]";
        Log::info($msg);
    }
}
