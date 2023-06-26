<?php

namespace Tech101\GitWebhook\Test\Unit;

use Tech101\GitWebhook\Test\BaseTestCase;
use Tech101\GitWebhook\WebhookServiceProvider;

class ServiceProviderTest extends BaseTestCase
{
    /**
     * Testing WebhookServiceProvider class
     *
     * @return void
     */
    public function testWebhookServiceProviderClass(): void
    {
        $provider = new WebhookServiceProvider($this->app);

        $this->assertInstanceOf(WebhookServiceProvider::class, $provider);
    }

    /**
     * Testing WebhookServiceProvider class boot method
     *
     * @return void
     */
    public function testBootMethodWorks(): void
    {
        $provider = new WebhookServiceProvider($this->app);
        $provider->boot();

        $this->assertTrue(true);
    }

    /**
     * Testing WebhookServiceProvider class register method
     *
     * @return void
     */
    public function testRegisterMethodWorks(): void
    {
        $provider = new WebhookServiceProvider($this->app);
        $provider->register();

        $this->assertTrue(true);
    }
}
