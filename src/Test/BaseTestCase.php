<?php

namespace Tech101\GitWebhook\Test;

use Illuminate\Support\Facades\Config;

class BaseTestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Config::set('git.gitlabWebhookToken', 'token');
        Config::set('git.githubWebhookToken', '123');
        Config::set('git.defaultBranch', 'main');

    }
}
