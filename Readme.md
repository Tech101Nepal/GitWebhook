## Getting started
The package can be installed in the following steps:

Install the package with:
``` bash
    composer require tech101/webhook
```
Publish the provider
``` bash
    php artisan vendor:publish --provider="Tech101\Webhook\WebhookServiceProvider"
```

## Configuration
Add the following variables to the project environment
``` bash
    DEFAULT_BRANCH="your branch name that you want in your system"
    GITLAB_WEBHOOK_TOKEN="token for the webhook that you have set in the gitlab"
```
