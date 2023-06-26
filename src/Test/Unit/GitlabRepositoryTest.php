<?php

namespace Tech101\GitWebhook\Test\Unit;

use Exception;
use Illuminate\Http\Request;
use Tech101\GitWebhook\Test\BaseTestCase;
use Tech101\GitWebhook\Factories\GitlabRequestFactory;
use Tech101\GitWebhook\App\Repositories\GitlabRepository;

class GitlabRepositoryTest extends BaseTestCase
{
    /**
     * Test request validation with invalid data
     *
     * @return void
     */
    public function testRequestValidationWithInvalidData()
    {
        $repository = new GitlabRepository();
        $request = new Request();

        $this->expectExceptionCode(419);
        $this->expectExceptionMessage("Invalid request.");
        $repository->parseRequest($request);
    }

    /**
     * Test request validation with valid data
     *
     * @return void
     */
    public function testRequestValidationWithValidData()
    {
        $repository = new GitlabRepository();
        $factory = new GitlabRequestFactory();
        $requestData = $factory->mergeRequestData("merge_request", "merged");
        $request = new Request(content: json_encode($requestData));

        $result = $repository->parseRequest($request);
        $this->assertTrue((bool) $result);
    }

    /**
     * Test secret validation when given invalid secret
     *
     * @return void
     */
    public function testTokenValidationWhenGivenInvalidSecret()
    {
        $repository = new GitlabRepository();

        $request = new Request();
        $request->headers->set('X-Gitlab-Token', 'invalid');

        $this->expectExceptionCode(401);
        $this->expectExceptionMessage("Unauthorized.");
        $repository->validateSecret($request);
    }

    /**
     * Test secret validation when given valid secret
     *
     * @return void
     */
    public function testTokenValidationWhenGivenValidSecret()
    {
        $repository = new GitlabRepository();

        $request = new Request();
        $request->headers->set('X-Gitlab-Token', config('git.gitlabWebhookToken'));

        $this->addToAssertionCount(1);
        $repository->validateSecret($request);
    }

    /**
     * Test event type when given invalid event from the request
     *
     * @return void
     */
    public function testEventTypeWhenGivenInavlidEventFromTheRequest()
    {
        $repository = new GitlabRepository();
        $factory = new GitlabRequestFactory();

        $requestData = $factory->mergeRequestData("merge", "merged");
        $request = new Request(content: json_encode($requestData));
        $repository->payload = json_decode($request->getContent());

        $this->expectExceptionCode(200);
        $this->expectExceptionMessage("Invalid event type. Expected merge_request but found merge");
        $repository->validateEventType("merge_request");
    }

    /**
     * Test event type when given valid event from the request
     *
     * @return void
     */
    public function testEventTypeWhenGivenValidKindFromTheRequest()
    {
        $repository = new GitlabRepository();
        $factory = new GitlabRequestFactory();

        $requestData = $factory->mergeRequestData("merge_request", "merged");
        $request = new Request(content: json_encode($requestData));
        $repository->payload = json_decode($request->getContent());

        $this->addToAssertionCount(1);
        $repository->validateEventType("merge_request");
    }

     /**
     * Test event state when given invalid state from the request
     *
     * @return void
     */
    public function testEventStateWhenGivenInvalidStateFromTheRequest()
    {
        $repository = new GitlabRepository();
        $factory = new GitlabRequestFactory();

        $requestData = $factory->mergeRequestData("merge_request", "invalid");
        $request = new Request(content: json_encode($requestData));
        $repository->payload = json_decode($request->getContent());

        $this->expectExceptionCode(200);
        $this->expectExceptionMessage("Invalid state. Expected merged but found invalid");
        $repository->validateEventState("merged");
    }

    /**
     * Test event state when given valid state from the request
     *
     * @return void
     */
    public function testEventStateWhenGivenValidStateFromTheRequest()
    {
        $repository = new GitlabRepository();
        $factory = new GitlabRequestFactory();

        $requestData = $factory->mergeRequestData("merge_request", "merged");
        $request = new Request(content: json_encode($requestData));
        $repository->payload = json_decode($request->getContent());

        $this->addToAssertionCount(1);
        $repository->validateEventState("merged");
    }
}
