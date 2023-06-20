<?php

namespace Tech101\GitWebhook\Http\Controller;

use Exception;
use Illuminate\Http\Request;
use Tech101\GitWebhook\Service\Git;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    public ?object $payload;
    public $git;

    public function __construct()
    {
        $this->payload = null;
        $this->git = new Git();
    }

    /**
     * Validate all the request
     *
     * @param Request $request
     *
     * @return void
     */
    protected function validateAll(Request $request): void
    {
        $this->validateRequest($request);
        $this->validateToken($request);
    }

    /**
     * Check the token that comes with the request matches the project's token
     *
     * @param Request $request
     *
     * @return void
     */
    protected function validateToken(Request $request): void
    {
        $token = $request->header("X-Gitlab-Token");

        if ($token === null || $token !== config('git.gitlabWebhookToken')) {
            throw new Exception("Unauthorized.", 401);
        }
    }

    /**
     * Checks if the request is valid or not
     *
     * @param Request $request
     *
     * @return void
     */
    protected function validateRequest(Request $request): void
    {
        $this->payload = json_decode($request->getContent());

        if (!$this->payload) {
            throw new Exception("Invalid request.", 419);
        }
    }

    /**
     * Checks the object kind is the required object kind
     *
     * @param string $required_kind
     *
     * @return void
     */
    protected function checkObjectKind(string $required_kind): void
    {
        if ($this->payload->object_kind != $required_kind) {
            throw new Exception("Invalid object kind. Expected " . $required_kind . " but found " . $this->payload->object_kind, 200);
        }
    }

    /**
     * Checks the object state is the required object state
     *
     * @param string $required_state
     *
     * @return void
     */
    protected function checkObjectState(string $required_state): void
    {
        if ($this->payload->object_attributes->state != $required_state) {
            throw new Exception("Invalid state. Expected " . $required_state . " but found " . $this->payload->object_attributes->state, 200);
        }
    }
}
