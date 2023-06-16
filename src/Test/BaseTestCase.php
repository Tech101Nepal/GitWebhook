<?php

namespace Tech101\Webhook\Test;

use Illuminate\Foundation\Testing\TestCase;
use Tech101\Webhook\src\Test\CreatesApplication;

class BaseTestCase extends TestCase
{
    use CreatesApplication;

    public function getProtectedMethod(string $className, string $methodName)
    {
        $class = new \ReflectionClass($className);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);
        return $method;
    }
}
