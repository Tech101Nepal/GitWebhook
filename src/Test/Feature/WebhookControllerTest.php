<?php

namespace Tech101\Webhook\Test\Feature;

use Illuminate\Http\Request;
use Tech101\Webhook\factories\GitlabRequestFactory;
use Tech101\Webhook\Test\BaseTestCase;
use Tech101\Webhook\Http\Controller\WebhookController;

class WebhookControllerTest extends BaseTestCase
{
    /**
     * Test when merge request hook is called with invalid request
     *
     * @return void
     */
    public function testWhenMergeRequestHookIsCalledWithInvalidRequest()
    {
        $webhookController = new WebhookController();
        $request = new Request([]);

        $result = $webhookController->mergeRequest($request);

        $this->assertEquals([
            "status" => "error",
            "message" => "Invalid request."
        ], json_decode(json_encode($result->getData()), true));

        $this->assertEquals($result->getStatusCode(), 419);
    }

    /**
     * Test when merge request hook is called with invalid token
     *
     * @return void
     */
    public function testWhenMergeRequestHookIsCalledWithInvalidToken()
    {
        $webhookController = new WebhookController();
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
     * Test when merge request hook is called with invalid object kind
     *
     * @return void
     */
    public function testWhenMergeRequestHookIsCalledWithInvalidObjectKind()
    {
        $webhookController = new WebhookController();
        $factory = new GitlabRequestFactory();
        $requestData = $factory->mergeRequestData("invalidObjectKind", "merged");
        $request = new Request(content: json_encode($requestData));
        $request->headers->set('X-Gitlab-Token', config('git.gitlabWebhookToken'));


        $result = $webhookController->mergeRequest($request);

        $this->assertEquals([
            "status" => "error",
            "message" => "Invalid object kind. Expected merge_request but found invalidObjectKind"
        ], json_decode(json_encode($result->getData()), true));

        $this->assertEquals($result->getStatusCode(), 200);
    }

    /**
     * Test when merge request hook is called with invalid object state
     *
     * @return void
     */
    public function testWhenMergeRequestHookIsCalledWithInvalidObjectState()
    {
        $webhookController = new WebhookController();
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
        $webhookController =  new WebhookController();
        $factory = new GitlabRequestFactory();
        $requestData = $factory->mergeRequestData("merge_request", "merged");

        $request = new Request(content: json_encode($requestData));
        $request->headers->set('X-Gitlab-Token', config('git.gitlabWebhookToken'));
        $result = $webhookController->mergeRequest($request);

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
        $webhookController = new WebhookController();
        $request = new Request([]);

        $result = $webhookController->tagPush($request);

        $this->assertEquals([
            "status" => "error",
            "message" => "Invalid request."
        ], json_decode(json_encode($result->getData()), true));

        $this->assertEquals($result->getStatusCode(), 419);
    }

    /**
     * Test when tag push hook is called with invalid token
     *
     * @return void
     */
    public function testWhenTagPushHookIsCalledWithInvalidToken()
    {
        $webhookController = new WebhookController();
        $factory = new GitlabRequestFactory();
        $requestData = $factory->tagRequestData("tag_request", "v1");
        $request = new Request(content: json_encode($requestData));
        $request->headers->set('X-Gitlab-Token', "invalid");

        $result = $webhookController->tagPush($request);

        $this->assertEquals([
            "status" => "error",
            "message" => "Unauthorized."
        ], json_decode(json_encode($result->getData()), true));

        $this->assertEquals($result->getStatusCode(), 401);
    }

    /**
     * Test when tage push hook is called with invalid object kind
     *
     * @return void
     */
    public function testWhenTagPushHookIsCalledWithInvalidObjectKind()
    {
        $webhookController = new WebhookController();
        $factory = new GitlabRequestFactory();
        $requestData = $factory->tagRequestData("invalidObjectKind", "v1");
        $request = new Request(content: json_encode($requestData));
        $request->headers->set('X-Gitlab-Token', config('git.gitlabWebhookToken'));


        $result = $webhookController->tagPush($request);

        $this->assertEquals([
            "status" => "error",
            "message" => "Invalid object kind. Expected tag_push but found invalidObjectKind"
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
        $webhookController =  new WebhookController();
        $factory = new GitlabRequestFactory();
        $requestData = $factory->tagRequestData("tag_push", "v1");

        $request = new Request(content: json_encode($requestData));
        $request->headers->set('X-Gitlab-Token', config('git.gitlabWebhookToken'));
        $result = $webhookController->tagPush($request);

        $this->assertEquals([
            "status" => "success",
            "message" => "Webhook recieved"
        ], json_decode(json_encode($result->getData()), true));
        $this->assertEquals($result->getStatusCode(), 200);
    }

    /**
     * Test Ty method
     *
     * @return void
     */
    public function testTy()
    {
        $webhookController = new WebhookController();
        $result = $webhookController->test();

        $this->assertEquals($result->getContent(), "Ty");
        $this->assertEquals($result->getStatusCode(), 200);
    }
}
