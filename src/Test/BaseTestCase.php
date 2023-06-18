<?php

namespace Tech101\Webhook\Test;

use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\TestCase;
use Tech101\Webhook\src\Test\CreatesApplication;

class BaseTestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Config::set('git.gitlabWebhookToken', 'token');
        Config::set('git.defaultBranch', 'main');

    }
    public function getProtectedMethod(string $className, string $methodName)
    {
        $class = new \ReflectionClass($className);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);
        return $method;
    }
}
