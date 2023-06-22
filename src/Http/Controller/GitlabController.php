<?php

namespace Tech101\GitWebhook\Http\Controller;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Tech101\GitWebhook\Service\Git;
use Tech101\GitWebhook\App\Repositories\GitlabRepository;
use Tech101\GitWebhook\Traits\ApiResponse;

class GitlabController extends Controller
{
    use ApiResponse;

    private $repository;
    private $git;
    private object $payload;

    public function __construct(GitlabRepository $gitlabRepository)
    {
        $this->repository = $gitlabRepository;
        $this->git = new Git();
    }

    public function mergeRequest(Request $request)
    {
        try {
            $this->validateAll($request);
            $this->repository->validateEventType("merge_request");
            $this->repository->validateEventState("merged");

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

    public function tagPush(Request $request)
    {
        try {
            $this->validateAll($request);
            $this->repository->validateEventType("tag_push");

            $this->git
                ->changeDirectory()
                ->fetch()
                ->checkoutTag($this->payload->ref);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

        return $this->successResponse([], "Webhook recieved", 200);
    }

    public function validateAll(Request $request)
    {
        $this->payload = $this->repository->parseRequest($request);
        $this->repository->validateSecret($request);
    }
}
