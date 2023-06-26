<?php

namespace Tech101\GitWebhook\Test\Unit;

use Tech101\GitWebhook\Test\BaseTestCase;
use Tech101\GitWebhook\WebhookServiceProvider;

class ServiceProviderTest extends BaseTestCase
{
    /**
     * Testing WebhookServiceProvider class
     */
    public function testWebhookServiceProviderClass()
    {
        $provider = new WebhookServiceProvider($this->app);

        $this->assertInstanceOf(WebhookServiceProvider::class, $provider);
    }

    /**
     * Testing WebhookServiceProvider class boot method
     */
    public function testBootMethodWorks()
    {
        $provider = new WebhookServiceProvider($this->app);
        $provider->boot();

        $this->assertTrue(true);
    }

    /**
     * Testing WebhookServiceProvider class register method
     */
    public function testRegisterMethodWorks()
    {
        $provider = new WebhookServiceProvider($this->app);
        $provider->register();

        $this->assertTrue(true);
    }
}
