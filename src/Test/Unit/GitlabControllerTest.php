<?php

namespace Tech101\GitWebhook\Test\Unit;

use Illuminate\Http\Request;
use Tech101\GitWebhook\Test\BaseTestCase;
use Tech101\GitWebhook\Factories\GitlabRequestFactory;
use Tech101\GitWebhook\Http\Controller\GitlabController;
use Tech101\GitWebhook\App\Repositories\GitlabRepository;

class GitlabControllerTest extends BaseTestCase
 {
    /**
     * Test when merge request hook is called with invalid request
     *
     * @return void
     */
    public function testWhenMergeRequestHookIsCalledWithInvalidRequest()
    {
        $webhookController = new GitlabController(new GitlabRepository);
        $request = new Request([]);

        $result = $webhookController->mergeRequest($request);

        $this->assertEquals([
            "status" => "error",
            "message" => "Invalid request."
        ], json_decode(json_encode($result->getData()), true));

        $this->assertEquals($result->getStatusCode(), 419);
    }

    /**
     * Test when merge request hook is called with invalid secret
     *
     * @return void
     */
    public function testWhenMergeRequestHookIsCalledWithInvalidSecret()
    {
        $webhookController = new GitlabController(new GitlabRepository);
        $factory = new GitlabRequestFactory();
        $requestData = $factory->mergeRequestData("merge_request", "merged");
        $request = new Request(content: json_encode($requestData));
        $request->headers->set('X-Gitlab-Token', "invalid");

        $result = $webhookController->mergeRequest($request);

        $this->assertEquals([
            "status" => "error",
            "message" => "Unauthorized."
        ], json_decode(json_encode($result->getData()), true));

        $this->assertEquals($result->getStatusCode(), 401);
    }

    /**
     * Test when merge request hook is called with invalid event type
     *
     * @return void
     */
    public function testWhenMergeRequestHookIsCalledWithInvalidEvent()
    {
        $webhookController = new GitlabController(new GitlabRepository);
        $factory = new GitlabRequestFactory();
        $requestData = $factory->mergeRequestData("invalidEventType", "merged");
        $request = new Request(content: json_encode($requestData));
        $request->headers->set('X-Gitlab-Token', config('git.gitlabWebhookToken'));


        $result = $webhookController->mergeRequest($request);

        $this->assertEquals([
            "status" => "error",
            "message" => "Invalid event type. Expected merge_request but found invalidEventType"
        ], json_decode(json_encode($result->getData()), true));

        $this->assertEquals($result->getStatusCode(), 200);
    }

    /**
     * Test when merge request hook is called with invalid event state
     *
     * @return void
     */
    public function testWhenMergeRequestHookIsCalledWithInvalidEventState()
    {
        $webhookController = new GitlabController(new GitlabRepository);
        $factory = new GitlabRequestFactory();
        $requestData = $factory->mergeRequestData("merge_request", "not_merged");

        $request = new Request(content: json_encode($requestData));
        $request->headers->set('X-Gitlab-Token', config('git.gitlabWebhookToken'));
        $result = $webhookController->mergeRequest($request);

        $this->assertEquals([
            "status" => "error",
            "message" => "Invalid state. Expected merged but found not_merged"
        ], json_decode(json_encode($result->getData()), true));

        $this->assertEquals($result->getStatusCode(), 200);
    }

    /**
     * Test when merge request hook is called with valid request
     *
     * @return void
     */
    public function testWhenMergeRequestHookIsCalledWithValidRequest()
    {
        $controller =  new GitlabController(new GitlabRepository);
        $factory = new GitlabRequestFactory();
        $requestData = $factory->mergeRequestData("merge_request", "merged");

        $request = new Request(content: json_encode($requestData));
        $request->headers->set('X-Gitlab-Token', config('git.gitlabWebhookToken'));
        $result = $controller->mergeRequest($request);

        $this->assertEquals([
            "status" => "success",
            "message" => "Webhook recieved"
        ], json_decode(json_encode($result->getData()), true));
        $this->assertEquals($result->getStatusCode(), 200);
    }

    /**
     * Test when tag push hook is called with invalid request
     *
     * @return void
     */
    public function testWhenTagPushHookIsCalledWithInvalidRequest()
    {
        $controller = new GitlabController(new GitlabRepository);
        $request = new Request([]);

        $result = $controller->tagPush($request);

        $this->assertEquals([
            "status" => "error",
            "message" => "Invalid request."
        ], json_decode(json_encode($result->getData()), true));

        $this->assertEquals($result->getStatusCode(), 419);
    }

    /**
     * Test when tag push hook is called with invalid secret
     *
     * @return void
     */
    public function testWhenTagPushHookIsCalledWithInvalidSecret()
    {
        $controller = new GitlabController(new GitlabRepository);
        $factory = new GitlabRequestFactory();
        $requestData = $factory->tagRequestData("tag_request", "v1");
        $request = new Request(content: json_encode($requestData));
        $request->headers->set('X-Gitlab-Token', "invalid");

        $result = $controller->tagPush($request);

        $this->assertEquals([
            "status" => "error",
            "message" => "Unauthorized."
        ], json_decode(json_encode($result->getData()), true));

        $this->assertEquals($result->getStatusCode(), 401);
    }

    /**
     * Test when tage push hook is called with invalid event type
     *
     * @return void
     */
    public function testWhenTagPushHookIsCalledWithInvalidEventType()
    {
        $controller = new GitlabController(new GitlabRepository);
        $factory = new GitlabRequestFactory();
        $requestData = $factory->tagRequestData("invalidEventType", "v1");
        $request = new Request(content: json_encode($requestData));
        $request->headers->set('X-Gitlab-Token', config('git.gitlabWebhookToken'));


        $result = $controller->tagPush($request);

        $this->assertEquals([
            "status" => "error",
            "message" => "Invalid event type. Expected tag_push but found invalidEventType"
        ], json_decode(json_encode($result->getData()), true));

        $this->assertEquals($result->getStatusCode(), 200);
    }

    /**
     * Test when tag push hook is called with valid request
     *
     * @return void
     */
    public function testWhenTagPushHookIsCalledWithValidRequest()
    {
        $controller =  new GitlabController(new GitlabRepository);
        $factory = new GitlabRequestFactory();
        $requestData = $factory->tagRequestData("tag_push", "v1");

        $request = new Request(content: json_encode($requestData));
        $request->headers->set('X-Gitlab-Token', config('git.gitlabWebhookToken'));
        $result = $controller->tagPush($request);

        $this->assertEquals([
            "status" => "success",
            "message" => "Webhook recieved"
        ], json_decode(json_encode($result->getData()), true));
        $this->assertEquals($result->getStatusCode(), 200);
    }
}