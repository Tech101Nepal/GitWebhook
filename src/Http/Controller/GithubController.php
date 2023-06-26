<?php

namespace Tech101\GitWebhook\Http\Controller;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tech101\GitWebhook\Service\Git;
use Tech101\GitWebhook\App\Repositories\GithubRepository;

class GithubController extends BaseController
{
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
            $this->repository->validateEventType("pull_request");

            $this->gitPull();
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

            $this->gitCheckout($this->payload->ref);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

        return $this->successResponse([], "Webhook recieved", 200);
    }
}
