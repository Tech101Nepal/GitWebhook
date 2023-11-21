Webhook package for gitlab - Laravel Package
=============================
The webhook package can be used to trigger the git command based on the events that gets by triggered by the gitlab.

## Getting started
The package can be installed in the following steps:

Install the package with:
``` bash
composer require tech101/gitwebhook
```
Publish the provider
``` bash
php artisan vendor:publish --provider="Tech101\Webhook\WebhookServiceProvider"
```

## Configuration
Add the following variables to the project environment
``` bash
DEFAULT_BRANCH="your branch name that you want in your system"
GITLAB_WEBHOOK_TOKEN="token for the webhook that you have set in gitlab"
OR
GITHUB_WEBHOOK_TOKEN="token for the webhook that you have set in github.
```
## Routes
To use the webhook you have to set url based on the following basis
``` bash
Github
- For pull request: "your_url"/webhook/github/pull-request
- For tag create: "your_url"/webhook/github/tag-create

Gitlab
- For merge request: "your_url"/webhook/gitlab/merge-request
- For tag push: "your_url"/webhook/gitlab/tag-push
```
