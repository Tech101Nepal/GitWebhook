<?php

return [
    /**
     * Stores the value from the env of the branch that you want to pull in
     */
    "defaultBranch" => env("DEFAULT_BRANCH", "main"),
    /**
     * Stores the webhook token set in the env
     */
    "gitlabWebhookToken" => env("GITLAB_WEBHOOK_TOKEN"),
    "githubWebhookToken" => env("GITHUB_WEBHOOK_TOKEN")
];
