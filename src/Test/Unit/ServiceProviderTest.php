<?php

namespace Tech101\Webhook\Test\Unit;

use Illuminate\Support\Facades\Route;
use Tech101\Webhook\Test\BaseTestCase;
use Tech101\Webhook\WebhookServiceProvider;

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
