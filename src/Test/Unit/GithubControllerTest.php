<?php

namespace Tech101\GitWebhook\Test\Unit;

use Exception;
use Illuminate\Http\Request;
use Tech101\GitWebhook\Test\BaseTestCase;
use Tech101\GitWebhook\Http\Controller\GithubController;
use Tech101\GitWebhook\App\Repositories\GithubRepository;
use Tech101\GitWebhook\Factories\GithubRequestFactory;

class GithubControllerTest extends BaseTestCase
{
    /**
     * Test when pull request hook is called with invalid request
     *
     * @return void
     */
    public function testPullRequestHookIsCalledWithInvalidRequest(): void
    {
        $webhookController = new GithubController(new GithubRepository());

        $request = new Request([]);

        $result = $webhookController->pullRequest($request);
        $this->assertEquals([
            "status" => "error",
            "message" => "Invalid request."
        ], json_decode(json_encode($result->getData()), true));

        $this->assertEquals($result->getStatusCode(), 419);
    }


    /**
     * Test when pull request hook is called with invalid signature
     *
     * @return void
     */
    public function testWhenPullRequestHookIsCalledWithInvalidSignature(): void
    {
        $webhookController = new GithubController(new GithubRepository());
        $factory = new GithubRequestFactory();
        [$requestData, $secret] = $factory->pullRequestData(["pull_request"]);
        $request = new Request(array($requestData), content: $requestData);
        $request->headers->set('X-Hub-Signature', "invalid=");
        $request->headers->set('X-GitHub-Event', 'pull_request');

        $result = $webhookController->pullRequest($request);

        $this->assertEquals([
            "status" => "error",
            "message" => "Invalid signature."
        ], json_decode(json_encode($result->getData()), true));

        $this->assertEquals($result->getStatusCode(), 401);
    }

    /**
     * Test when pull request hook is called with invalid secret
     *
     * @return void
     */
    public function testWhenPullRequestHookIsCalledWithInvalidSecret(): void
    {
        $webhookController = new GithubController(new GithubRepository());
        $factory = new GithubRequestFactory();
        [$requestData, $secret] = $factory->pullRequestData(["pull_request"]);
        $request = new Request(array($requestData), content: $requestData);
        $request->headers->set('X-Hub-Signature', "sha1=");
        $request->headers->set('X-GitHub-Event', 'pull_request');

        $result = $webhookController->pullRequest($request);

        $this->assertEquals([
            "status" => "error",
            "message" => "Unauthorized."
        ], json_decode(json_encode($result->getData()), true));

        $this->assertEquals($result->getStatusCode(), 401);
    }


    /**
     * Test when pull request hook is called with valid request
     *
     * @return void
     */
    public function testWhenPullRequestHookIsCalledWithValidRequest(): void
    {
        $webhookController = new GithubController(new GithubRepository());
        $factory = new GithubRequestFactory();
        [$requestData, $secret] = $factory->pullRequestData(["pull_request"]);
        $request = new Request(array($requestData), content: $requestData);
        $request->headers->set('X-Hub-Signature', $secret);
        $request->headers->set('X-GitHub-Event', 'pull_request');

        $result = $webhookController->pullRequest($request);

        $this->assertEquals([
            "status" => "success",
            "message" => "Webhook recieved"
        ], json_decode(json_encode($result->getData()), true));

        $this->assertEquals($result->getStatusCode(), 200);
    }

    /**
     * Test when tag create hook is called with invalid request
     *
     * @return void
     */
    public function testTagCreateHookIsCalledWithInvalidRequest(): void
    {
        $webhookController = new GithubController(new GithubRepository());

        $request = new Request([]);

        $result = $webhookController->tagCreate($request);
        $this->assertEquals([
            "status" => "error",
            "message" => "Invalid request."
        ], json_decode(json_encode($result->getData()), true));

        $this->assertEquals($result->getStatusCode(), 419);
    }

    /**
     * Test when pull request hook is called with invalid signature
     *
     * @return void
     */
    public function testWhenCreateTagHookIsCalledWithInvalidSignature(): void
    {
        $webhookController = new GithubController(new GithubRepository());
        $factory = new GithubRequestFactory();
        [$requestData, $secret] = $factory->tagCreateData("v1", "tag");
        $request = new Request(array($requestData), content: $requestData);
        $request->headers->set('X-Hub-Signature', "invalid=");
        $request->headers->set('X-GitHub-Event', 'tag');

        $result = $webhookController->tagCreate($request);

        $this->assertEquals([
            "status" => "error",
            "message" => "Invalid signature."
        ], json_decode(json_encode($result->getData()), true));

        $this->assertEquals($result->getStatusCode(), 401);
    }

    /**
     * Test when pull request hook is called with invalid secret
     *
     * @return void
     */
    public function testWhenTagCreateHookIsCalledWithInvalidSecret(): void
    {
        $webhookController = new GithubController(new GithubRepository());
        $factory = new GithubRequestFactory();
        [$requestData, $secret] = $factory->tagCreateData("v1", "tag");
        $request = new Request(array($requestData), content: $requestData);
        $request->headers->set('X-Hub-Signature', "sha1=");

        $result = $webhookController->tagCreate($request);

        $this->assertEquals([
            "status" => "error",
            "message" => "Unauthorized."
        ], json_decode(json_encode($result->getData()), true));

        $this->assertEquals($result->getStatusCode(), 401);
    }

    /**
     * Test when create tag hook is called with invalid tag ref
     *
     * @return void
     */
    public function testWhenTagCreateHookIsCalledWithInvalidTagRef(): void
    {
        $webhookController = new GithubController(new GithubRepository());
        $factory = new GithubRequestFactory();
        [$requestData, $secret] = $factory->tagCreateData("v1", "invalid");
        $request = new Request(array($requestData), content: $requestData);
        $request->headers->set('X-Hub-Signature', $secret);

        $result = $webhookController->tagCreate($request);

        $this->assertEquals([
            "status" => "error",
            "message" => "Invalid event type. Expected ref_type as tag"
        ], json_decode(json_encode($result->getData()), true));

        $this->assertEquals($result->getStatusCode(), 200);
    }

    /**
     * Test when create tag hook is called with invalid tag request data
     *
     * @return void
     */
    public function testWhenTagCreateHookIsCalledWithInvalidTagRequestData(): void
    {
        $webhookController = new GithubController(new GithubRepository());
        $factory = new GithubRequestFactory();
        [$requestData, $secret] = $factory->pullRequestData(["tag"]);
        $request = new Request(array($requestData), content: $requestData);
        $request->headers->set('X-Hub-Signature', $secret);

        $result = $webhookController->tagCreate($request);

        $this->assertEquals([
            "status" => "error",
            "message" => "Invalid tag request."
        ], json_decode(json_encode($result->getData()), true));

        $this->assertEquals($result->getStatusCode(), 200);
    }

    /**
     * Test when create tag hook is called with valid request data
     *
     * @return void
     */
    public function testWhenTagCreateHookIsCalledWithValidRequestData(): void
    {
        $webhookController = new GithubController(new GithubRepository());
        $factory = new GithubRequestFactory();
        [$requestData, $secret] = $factory->tagCreateData("v1", "tag");
        $request = new Request(array($requestData), content: $requestData);
        $request->headers->set('X-Hub-Signature', $secret);

        $result = $webhookController->tagCreate($request);

        $this->assertEquals([
            "status" => "success",
            "message" => "Webhook recieved"
        ], json_decode(json_encode($result->getData()), true));

        $this->assertEquals($result->getStatusCode(), 200);
    }
}
