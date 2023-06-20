<?php

namespace Tech101\GitWebhook\Test\Unit;

use Illuminate\Http\Request;
use Tech101\GitWebhook\Test\BaseTestCase;
use Tech101\GitWebhook\Factories\GitlabRequestFactory;
use Tech101\GitWebhook\Http\Controller\BaseController;

class BaseControllerTest extends BaseTestCase
{
    /**
     * Test request validation with invalid data
     *
     * @return void
     */
    public function testRequestValidationWithInvalidData()
    {
        $baseController = new BaseController();
        $request = new Request();

        $method = $this->getProtectedMethod(BaseController::class, "validateRequest");

        $this->expectExceptionCode(419);
        $this->expectExceptionMessage("Invalid request.");
        $method->invoke($baseController, $request);
    }

    /**
     * Test request validation with valid data
     *
     * @return void
     */
    public function testRequestValidationWithValidData()
    {
        $baseController = new BaseController();
        $factory = new GitlabRequestFactory();
        $requestData = $factory->mergeRequestData("merge_request", "merged");

        $request = new Request(content: json_encode($requestData));

        $method = $this->getProtectedMethod(BaseController::class, "validateRequest");

        $method->invoke($baseController, $request);
        $this->addToAssertionCount(1);
    }

    /**
     * Test token validation when given invalid token
     *
     * @return void
     */
    public function testTokenValidationWhenGivenInvalidToken()
    {
        $baseController = new BaseController();

        $request = new Request();
        $request->headers->set('X-Gitlab-Token', 'invalid');

        $method = $this->getProtectedMethod(BaseController::class, "validateToken");

        $this->expectExceptionCode(401);
        $this->expectExceptionMessage("Unauthorized.");
        $method->invoke($baseController, $request);
    }

    /**
     * Test token validation when given valid token
     *
     * @return void
     */
    public function testTokenValidationWhenGivenValidToken()
    {
        $baseController = new BaseController();

        $request = new Request();
        $request->headers->set('X-Gitlab-Token', config('git.gitlabWebhookToken'));

        $method = $this->getProtectedMethod(BaseController::class, "validateToken");

        $method->invoke($baseController, $request);
        $this->addToAssertionCount(1);
    }

    /**
     * Test object kind when given invalid kind from the request
     *
     * @return void
     */
    public function testObjectKindWhenGivenInavlidKindFromTheRequest()
    {
        $baseController = new BaseController();
        $factory = new GitlabRequestFactory();

        $requestData = $factory->mergeRequestData("merge", "merged");
        $request = new Request(content: json_encode($requestData));
        $baseController->payload = json_decode($request->getContent());

        $method = $this->getProtectedMethod(BaseController::class, "checkObjectKind");

        $this->expectExceptionCode(200);
        $this->expectExceptionMessage("Invalid object kind. Expected merge_request but found merge");
        $method->invoke($baseController, "merge_request", "merge");
    }

    /**
     * test object kind when given valid kind from the request
     *
     * @return void
     */
    public function testObjectKindWhenGivenValidKindFromTheRequest()
    {
        $baseController = new BaseController();
        $factory = new GitlabRequestFactory();

        $requestData = $factory->mergeRequestData("merge_request", "merged");
        $request = new Request(content: json_encode($requestData));
        $baseController->payload = json_decode($request->getContent());

        $method = $this->getProtectedMethod(BaseController::class, "checkObjectKind");

        $method->invoke($baseController, "merge_request");
        $this->addToAssertionCount(1);
    }
}
