<?php

namespace Tech101\Webhook\Http\Controller;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tech101\Webhook\Traits\ApiResponse;
use Tech101\Webhook\Http\Controller\BaseController;

class WebhookController extends BaseController
{
    use ApiResponse;

    public ?object $payload;
    public $git;

    /**
     * Execute git commmand  when merge request event gets triggerd
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function mergeRequest(Request $request): JsonResponse
    {
        try {
            $this->validateAll($request);
            $this->checkObjectKind("merge_request");
            $this->checkObjectState("merged");

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
     * Execute git commmand  when tag push event gets triggerd
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function tagPush(Request $request): JsonResponse
    {
        try {
            $this->validateAll($request);
            $this->checkObjectKind("tag_push");

            $this->git
                ->changeDirectory()
                ->fetch()
                ->checkoutTag($this->payload->ref);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
        return $this->successResponse([], "Webhook recieved", 200);
    }

    public function test()
    {
        return response("Ty", 200);
    }
}
