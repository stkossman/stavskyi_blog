<?php

namespace App\Jobs\GenerateCatalog;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendPriceRequestJob extends AbstractJob
{
    use Queueable;
}
