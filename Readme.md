## Getting started
The package can be installed in the followin steps:

set the following line in the composer.json:
``` bash
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/Tech101Nepal/Webhook.git"
        }
    ],
    "require": {
        "tech101/webhook": "dev-main"
    }
```

Update the composer
``` bash
    compsoer update
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
