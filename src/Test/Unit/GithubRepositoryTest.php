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
    public function testRequestValidationWithInvalidData(): void
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
    public function testRequestValidationWithValidData(): void
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
    public function testTokenValidationWhenGivenInvalidSecret(): void
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
    public function testTokenValidationWhenGivenInvalidSignature(): void
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
    public function testTokenValidationWhenGivenValidSecret(): void
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
    public function testEventTypeWhenGivenInavlidEventFromTheRequest(): void
    {
        $repository = new GithubRepository();
        $factory = new GithubRequestFactory();

        [$requestData, $secret] = $factory->pullRequestData(["invalid"]);
        $request = new Request(array($requestData), content: $requestData);
        $repository->payload = json_decode($request->getContent());
        $repository->event = "invalid";

        $this->expectExceptionCode(200);
        $this->expectExceptionMessage("Invalid event type. Expected pull_request but found invalid");
        $repository->validateEventType("pull_request");
    }

    /**
     * Test request validation is for tag creation
     *
     * @return void
     */
    public function testTagEventWithInvalidData(): void
    {
        $factory = new GithubRequestFactory();
        $requestData = $factory->pullRequestData(["something"])[0];

        $this->expectExceptionCode(200);
        $this->expectExceptionMessage("Invalid tag request.");

        $repository = new GithubRepository();
        $repository->payload = json_decode($requestData);
        $repository->validateTagEvent();
    }


    /**
     * Test request validation is for tag creation
     *
     * @return void
     */
    public function testTagEventWithInvalidRef(): void
    {
        $repository = new GithubRepository();
        $factory = new GithubRequestFactory();
        [$requestData, $secret] = $factory->tagCreateData("v1", "invalid");
        $request = new Request(array($requestData), content: $requestData);
        $repository->payload = json_decode($request->getContent());

        $this->expectExceptionCode(200);
        $this->expectExceptionMessage("Invalid event type. Expected ref_type as tag");

        $repository->validateTagEvent();
    }

    /**
     * Test validateEventState blank method
     *
     * @return void
     */
    public function testvalidateEventMethod(): void
    {
        $repository = new GithubRepository();
        $repository->validateEventState("nothing");

        $this->expectNotToPerformAssertions();
    }
}
