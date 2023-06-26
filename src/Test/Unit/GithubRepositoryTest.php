<?php

namespace Tech101\GitWebhook\Test\Unit;

use Exception;
use Illuminate\Http\Request;
use Tech101\GitWebhook\Test\BaseTestCase;
use Tech101\GitWebhook\Factories\GithubRequestFactory;
use Tech101\GitWebhook\App\Repositories\GithubRepository;

class GithubRepositoryTest extends BaseTestCase
{
    /**
     * Test request validation with invalid data
     *
     * @return void
     */
    public function testRequestValidationWithInvalidData()
    {
        $repository = new GithubRepository();
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
        $repository = new GithubRepository();
        $factory = new GithubRequestFactory();
        $requestData = $factory->pullRequestData(["pull_request"])[0];
        $request = new Request(content: $requestData);

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
        $repository = new GithubRepository();
        $factory = new GithubRequestFactory();
        [$requestData, $secret] = $factory->pullRequestData(["pull_request"]);
        $request =  new Request(array($requestData), content: $requestData);
        $request->headers->set('X-Hub-Signature', "sha1=invalid");

        $this->expectExceptionCode(401);
        $this->expectExceptionMessage("Unauthorized.");
        $repository->validateSecret($request);
    }

    /**
     * Test secret validation when given invalid secret
     *
     * @return void
     */
    public function testTokenValidationWhenGivenInvalidSignature()
    {
        $repository = new GithubRepository();
        $factory = new GithubRequestFactory();
        [$requestData, $secret] = $factory->pullRequestData(["pull_request"]);
        $request = new Request(array($requestData), content: $requestData);
        $request->headers->set('X-Hub-Signature', "invalid");

        $this->expectExceptionCode(401);
        $this->expectExceptionMessage("Invalid signature.");
        $repository->validateSecret($request);
    }

    /**
     * Test secret validation when given invalid secret
     *
     * @return void
     */
    public function testTokenValidationWhenGivenValidSecret()
    {
        $repository = new GithubRepository();
        $factory = new GithubRequestFactory();
        [$requestData, $secret] = $factory->pullRequestData(["pull_request"]);
        $request = new Request(array($requestData), content: $requestData);
        $request->headers->set('X-Hub-Signature', $secret);

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
        $repository = new GithubRepository();
        $factory = new GithubRequestFactory();

        [$requestData, $secret] = $factory->pullRequestData(["invalid"]);
        $request = new Request(array($requestData), content: $requestData);
        $repository->payload = json_decode($request->getContent());

        $this->expectExceptionCode(200);
        $this->expectExceptionMessage("Invalid event type. Expected pull_request but found invalid");
        $repository->validateEventType("pull_request");
    }

    /**
     * test request validation is for tag creation
     *
     * @return void
     */
    public function testTagEventWithInvalidData()
    {
        $factory = new GithubRequestFactory();
        [$requestData, $secret] = $factory->pullRequestData(["something"]);
        $payload = json_encode($requestData);

        $this->expectExceptionCode(200);
        $this->expectExceptionMessage("Invalid tag request.");

        $repository = new GithubRepository($payload);
        $repository->validateTagEvent();
    }


    /**
     * test request vaklidation is for tag creation
     *
     * @return void
     */
    public function testTagEventWithInvalidRef()
    {
        $repository = new GithubRepository();
        $factory = new GithubRequestFactory();
        [$requestData, $secret] = $factory->tagCreateData("v1", "invalid");
        $request = new Request(array($requestData), content: $requestData);
        $repository->payload = json_decode($request->getContent());

        $this->expectExceptionCode(200);
        $this->expectExceptionMessage("Invalid event type. Expected ref_type as tag");

        $repository->validateTagEvent($request);
    }
}
