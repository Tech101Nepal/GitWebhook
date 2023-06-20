<?php

namespace Tech101\GitWebhook\Test\Unit;

use Tech101\GitWebhook\Test\BaseTestCase;
use Tech101\GitWebhook\WebhookServiceProvider;

class ServiceProviderTest extends BaseTestCase
{
    public function testWebhookServiceProviderClass()
    {
        $provider = new WebhookServiceProvider($this->app);

        $this->assertInstanceOf(WebhookServiceProvider::class, $provider);
    }

    public function testBootMethodWorks()
    {
        $provider = new WebhookServiceProvider($this->app);
        $provider->boot();

        $this->assertTrue(true);
    }

    public function testRegisterMethodWorks()
    {
        $provider = new WebhookServiceProvider($this->app);
        $provider->register();

        $this->assertTrue(true);
    }
}
