<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\BlogPost;

class BlogPostAfterCreateJob implements ShouldQueue
{
    use Queueable;
    private $blogPost;

    /**
     * Create a new job instance.
     */
    public function __construct(BlogPost $blogPost)
    {
        $this->blogPost = $blogPost;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        logs()->info("Створено новий запис в блозі [{$this->blogPost->id}]");
    }
}
