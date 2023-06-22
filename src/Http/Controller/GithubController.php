<?php

namespace Tech101\GitWebhook\Http\Controller;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Tech101\GitWebhook\Service\Git;
use Tech101\GitWebhook\Traits\ApiResponse;
use Tech101\GitWebhook\App\Repositories\GithubRepository;

class GithubController extends Controller
{
    use ApiResponse;

    private $repository;
    private $git;
    private object $payload;

    public function __construct(GithubRepository $githubRepository)
    {
        $this->repository = $githubRepository;
        $this->git = new Git();
    }

    /**
     * Function to trigger webhook based on pull request event
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function pullRequest(Request $request): JsonResponse
    {
        try {
            $this->validateAll($request);
            $this->repository->validateEventType("merge");

            $this->git
                ->changeDirectory()
                ->fetch()
                ->reset()
                ->pull();
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

        return $this->successResponse([], "Webhook recieved", 200);
    }

    /**
     * Function to trigger webhook based on tag create event
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function tagCreate(Request $request): JsonResponse
    {
        try {
            $this->validateAll($request);
            $this->repository->validateTagEvent();

            $this->git
                ->changeDirectory()
                ->fetch()
                ->checkoutTag($this->payload->ref);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

        return $this->successResponse([], "Webhook Recieved", 200);
    }

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
}
