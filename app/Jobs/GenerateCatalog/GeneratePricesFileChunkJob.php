<?php

namespace App\Jobs\GenerateCatalog;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GeneratePricesFileChunkJob extends AbstractJob
{
    use Queueable;
    private array $chunk;
    private int $fileNum;

    public function __construct(array $chunk, int $fileNum)
    {
        parent::__construct();
        $this->chunk = $chunk;
        $this->fileNum = $fileNum;
    }

    public function handle(): void
    {
        $this->debug("Processing chunk {$this->fileNum} with products: " . implode(', ', $this->chunk));
    }
}
