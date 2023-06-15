<?php

namespace Tech101\Webhook\Test;

use Tests\TestCase;

class BaseTestCase extends TestCase
{
    public function getProtectedMethod(string $className, string $methodName)
    {
        $class = new \ReflectionClass($className);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);
        return $method;
    }
}
