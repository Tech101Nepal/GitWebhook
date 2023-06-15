<?php

namespace Tech101\Webhook\Test\Feature;

use Illuminate\Http\Request;
use Tech101\Webhook\Test\BaseTestCase;
use Tech101\Webhook\factories\GitlabRequestFactory;
use Tech101\Webhook\Http\Controller\BaseController;

class BaseControllerTest extends BaseTestCase
{
    public function testRequestValidationWithInvalidData()
    {
        $baseController = new BaseController();
        $request = new Request();

        $method = $this->getProtectedMethod(BaseController::class, "validateRequest");

        $this->expectExceptionCode(419);
        $this->expectExceptionMessage("Invalid request.");
        $method->invoke($baseController, $request);
    }

    public function testRequestValidationWithValidData()
    {
        $baseController = new BaseController();
        $factory = new GitlabRequestFactory();
        $requestData = $factory->mergeRequestData("merge_request", "merged");

        $request = new Request(content: json_encode($requestData));

        $method = $this->getProtectedMethod(BaseController::class, "validateRequest");

        try {
            $method->invoke($baseController, $request);
        } catch (\Exception $exception) {
            $this->fail('Exception was thrown: ' . $exception->getMessage());
        }

        $this->addToAssertionCount(1);
    }

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

    public function testTokenValidationWhenGivenValidToken()
    {
        $baseController = new BaseController();

        $request = new Request();
        $request->headers->set('X-Gitlab-Token', config('git.gitlabWebhookToken'));

        $method = $this->getProtectedMethod(BaseController::class, "validateToken");

        try {
            $method->invoke($baseController, $request);
        } catch (\Exception $exception) {
            $this->fail('Exception was thrown: ' . $exception->getMessage());
        }

        $this->addToAssertionCount(1);
    }

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

    public function testObjectKindWhenGivenValidKindFromTheRequest()
    {
        $baseController = new BaseController();
        $factory = new GitlabRequestFactory();

        $requestData = $factory->mergeRequestData("merge_request", "merged");
        $request = new Request(content: json_encode($requestData));
        $baseController->payload = json_decode($request->getContent());

        $method = $this->getProtectedMethod(BaseController::class, "checkObjectKind");

        try {
            $method->invoke($baseController, "merge_request");
        } catch (\Exception $exception) {
            $this->fail('Exception was thrown: ' . $exception->getMessage());
        }

        $this->addToAssertionCount(1);
    }
}
