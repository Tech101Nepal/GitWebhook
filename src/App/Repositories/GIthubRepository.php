<?php

namespace Tech101\GitWebhook\App\Repositories;

use Exception;
use Illuminate\Http\Request;
use Tech101\GitWebhook\Service\Git;
use Tech101\GitWebhook\App\Interface\GitInterface;

class GithubRepository implements GitInterface
{
    public ?object $payload;
    public $git;

    public function __construct()
    {
        $this->payload = null;
        $this->git = new Git();
    }

    /**
     * Checks the secret matches with the request secret
     *
     * @param Request $request
     *
     * @return void
     */
    public function validateSecret(Request $request): void
    {
        $signature = $request->header('X-Hub-Signature');
        $payload = json_encode(json_decode($request->getContent()));
        $payload = $request->getContent();

        list($algo, $recievedSignature) = explode('=', $signature, 2);

        $expected_signature = hash_hmac($algo, $payload, config('git.gitlabWebhookToken'));

        if ($recievedSignature !== $expected_signature) {
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
    public function parseRequest(Request $request): object
    {
        $this->payload = json_decode($request->getContent());

        if (!$this->payload) {
            throw new Exception("Invalid request.", 419);
        }

        return $this->payload;
    }

    public function validateEventState(string $state): void
    {
    }

    /**
     * Checks the event type is the required event type
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function validateEventType(string $type): void
    {
        if ($events = $this->payload->hook->events) {
            if (!in_array($type, $events)) {
                throw new Exception(
                    "Invalid event type. Expected {$type} but found " . implode(", ", $events),
                    200
                );
            }
        }
    }

    /**
     * Checks if the event is tag event
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function validateTagEvent()
    {
        if ($this->payload->ref_type != "tag") {
            throw new Exception(
                "Invalid event type. Expected ref_type as tag",
                200
            );
        }
    }
}
