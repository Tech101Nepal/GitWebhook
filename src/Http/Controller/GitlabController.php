<?php

namespace Tech101\GitWebhook\Http\Controller;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tech101\GitWebhook\Service\Git;
use Tech101\GitWebhook\App\Repositories\GitlabRepository;

class GitlabController extends BaseController
{
    public function __construct(GitlabRepository $gitlabRepository)
    {
        $this->repository = $gitlabRepository;
        $this->git = new Git();
    }

    /**
     * Function to trigger webhook based on merge request event
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function mergeRequest(Request $request): JsonResponse
    {
        try {
            $this->validateAll($request);
            $this->repository->validateEventType("merge_request");
            $this->repository->validateEventState("merged");

            $this->gitPull();
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

        return $this->successResponse([], "Webhook recieved", 200);
    }

    /**
     * Function to trigger webhook based on tag push event
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function tagPush(Request $request): JsonResponse
    {
        try {
            $this->validateAll($request);
            $this->repository->validateEventType("tag_push");

            $this->gitCheckout(str_replace("refs/tags/", "", $this->payload->ref));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

        return $this->successResponse([], "Webhook recieved", 200);
    }
}
