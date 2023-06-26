<?php

namespace Tech101\GitWebhook\Http\Controller;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Tech101\GitWebhook\Traits\ApiResponse;

class BaseController extends Controller
{
    use ApiResponse;

    public $repository;
    public $git;
    public object $payload;

    /**
     * Function to validate request and secret sent
     *
     * @param Request $request
     *
     * @return void
     */
    public function validateAll(Request $request): void
    {
        $this->repository->parseRequest($request);
        $this->repository->validateSecret($request);
    }

    /**
     * Function to git pull
     */
    public function gitPull(): void
    {
        $this->git
            ->changeDirectory()
            ->fetch()
            ->reset()
            ->pull();
    }

    /**
     * Function to git tag checkout
     */
    public function gitCheckout($ref): void
    {
        $this->git
            ->changeDirectory()
            ->fetch()
            ->checkoutTag($ref);
    }
}
