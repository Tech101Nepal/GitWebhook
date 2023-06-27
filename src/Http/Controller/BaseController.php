<?php

namespace Tech101\GitWebhook\Http\Controller;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Tech101\GitWebhook\Service\Git;
use Tech101\GitWebhook\Traits\ApiResponse;

class BaseController extends Controller
{
    use ApiResponse;

    /**
     * @var object $repository
     * @var Git $git
     * @var object $payload
     */
    public object $repository;
    public Git $git;
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
        $this->payload = $this->repository->parseRequest($request);
        $this->repository->validateSecret($request);
    }

    /**
     * Function to git pull
     *
     * @return void
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
     *
     * @param string $ref
     *
     * @return void
     */
    public function gitCheckout(string $ref): void
    {
        $this->git
            ->changeDirectory()
            ->fetch()
            ->checkoutTag($ref);
    }
}
