<?php

namespace Tech101\GitWebhook\App\Repositories;

use Exception;
use Illuminate\Http\Request;
use Tech101\GitWebhook\App\Interface\GitInterface;

class GitlabRepository extends BaseRepository implements GitInterface
{
    /**
     * Check the token that comes with the request matches the project's token
     *
     * @param Request $request
     *
     * @return void
     */
    public function validateSecret(Request $request): void
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
     * @return object
     */
    public function parseRequest(Request $request): object
    {
        $this->payload = json_decode($request->getContent());

        if (!$this->payload) {
            throw new Exception("Invalid request.", 419);
        }

        return $this->payload;
    }

    /**
     * Checks the event type is the required event type
     *
     * @param string $type
     *
     * @return void
     */
    public function validateEventType(string $type): void
    {
        if ($this->payload->object_kind !== $type) {
            throw new Exception(
                "Invalid event type. Expected {$type} but found {$this->payload->object_kind}",
                200
            );
        }
    }

    /**
     * Checks the event state is the required event state
     *
     * @param string $state
     *
     * @return void
     */
    public function validateEventState(string $state): void
    {
        if ($this->payload->object_attributes->state !== $state) {
            throw new Exception(
                "Invalid state. Expected {$state} but found {$this->payload->object_attributes->state}",
                200
            );
        }
    }
}
