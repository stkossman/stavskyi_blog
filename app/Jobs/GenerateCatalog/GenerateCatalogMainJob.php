<?php

namespace App\Jobs\GenerateCatalog;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;
use Illuminate\Support\Collection;

class GenerateCatalogMainJob extends AbstractJob
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
     * @throws Throwable
     */
    public function handle(): void
    {
        $this->debug('start');

        GenerateCatalogCacheJob::dispatchSync();

        $chainPrices = $this->getChainPrices();

        $chainMain = [
            new GenerateCategoriesJob(),
            new GenerateDeliveriesJob(),
            new GeneratePointsJob(),
        ];

        $chainLast = [
            new ArchiveUploadsJob(),
            new SendPriceRequestJob(),
        ];

        $chain = array_merge($chainPrices, $chainMain, $chainLast);

         GenerateGoodsFileJob::withChain($chain)->dispatch();
        //GenerateGoodsFileJob::dispatch()->chain($chain);

        $this->debug('finish');
    }

    /**
     * Формування ланцюгів підзавдань по генерації файлів з цінами
     *
     * @return array<AbstractJob>
     */
    private function getChainPrices(): array
    {
        $result = [];
        $products = collect([1, 2, 3, 4, 5, 6, 7]);
        $fileNum = 1;
        $chunkSize = 2;

        foreach ($products->chunk($chunkSize) as $chunk) {
            $result[] = new GeneratePricesFileChunkJob($chunk->toArray(), $fileNum);
            $fileNum++;
        }

        return $result;
    }
}
