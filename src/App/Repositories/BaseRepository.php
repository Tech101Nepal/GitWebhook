<?php

namespace Tech101\GitWebhook\App\Repositories;

use Illuminate\Http\Request;
use Tech101\GitWebhook\Service\Git;
use Tech101\GitWebhook\App\Interface\GitInterface;

class BaseRepository
{
    /**
     * @var object $payload
     * @var Git $git
     */
    public ?object $payload;
    public Git $git;

    public function __construct()
    {
        $this->payload = null;
        $this->git = new Git();
    }
}
