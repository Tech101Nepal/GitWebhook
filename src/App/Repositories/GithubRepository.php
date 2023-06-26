<?php

namespace Tech101\GitWebhook\App\Repositories;

use Exception;
use Illuminate\Http\Request;
use Tech101\GitWebhook\App\Interface\GitInterface;

class GithubRepository extends BaseRepository implements GitInterface
{
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
        $payload = $request->getContent();

        if (strpos($signature, "sha1=") !== 0) {
            throw new Exception("Invalid signature.", 401);
        }

        list($algo, $recievedSignature) = explode('=', $signature, 2);

        $expected_signature = hash_hmac($algo, $payload, config('git.githubWebhookToken'));

        if ($recievedSignature !== $expected_signature) {
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

    public function validateEventState(string $state): void
    {
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
     * @return void
     */
    public function validateTagEvent(): void
    {
        if (!isset($this->payload->ref_type)) {
            throw new Exception(
                "Invalid tag request.",
                200
            );
        }
        if ($this->payload->ref_type != "tag") {
            throw new Exception(
                "Invalid event type. Expected ref_type as tag",
                200
            );
        }
    }
}
