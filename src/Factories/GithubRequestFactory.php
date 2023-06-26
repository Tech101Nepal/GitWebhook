<?php

namespace Tech101\GitWebhook\Factories;

class GithubRequestFactory
{
    public function pullRequestData(array $events): array
    {
        $content = [
            "zen" =>  "Encourage flow.",
            "hook_id" =>  fake()->numberBetween(989898, 7878787878),
            "hook" =>  [
                "type" =>  "Repository",
                "id" =>  fake()->numberBetween(989898, 7878787878),
                "name" =>  "web",
                "active" =>  true,
                "events" =>  $events,
                "config" =>  [
                    "content_type" =>  "form",
                    "insecure_ssl" =>  "0",
                    "secret" =>  "********",
                    "url" =>  "/"
                ],
                "updated_at" =>  "",
                "created_at" =>  "",
                "url" =>  "",
                "test_url" =>  "",
                "ping_url" =>  "",
                "deliveries_url" =>  "",
                "last_response" =>  [
                    "code" =>  null,
                    "status" =>  "unused",
                    "message" =>  null
                ]
            ]
        ];

        $contentJson = json_encode($content);
        $signature = "sha1=" . hash_hmac("sha1", $contentJson, config('git.githubWebhookToken'));

        return [$contentJson, $signature];
    }

    function tagCreateData($tagname, $ref_type): array
    {
        $content = [
            "ref" => $tagname,
            "ref_type" => $ref_type,
            "master_branch" => "main",
            "repository" => [
                "id" => 650482965,
                "node_id" => "R_kgDOJsWVFQ",
                "name" => "GitWebhook",
                "full_name" => "Tech101Nepal/GitWebhook",
                "private" => false,
                "owner" => [
                    "login" => "Tech101Nepal",
                    "id" => fake()->numerify("343"),
                    "node_id" => fake()->numerify("adsfjkh"),
                    "avatar_url" => "",
                    "gravatar_id" => "",
                    "url" => "",
                    "html_url" => "",
                    "followers_url" => "",
                    "following_url" => "",
                    "gists_url" => "",
                    "starred_url" => "",
                    "subscriptions_url" => "",
                    "organizations_url" => "",
                    "repos_url" => "",
                    "events_url" => "",
                    "received_events_url" => "",
                    "type" => "Organization",
                    "site_admin" => false
                ]
            ]
        ];

        $contentJson = json_encode($content);
        $signature = "sha1=" . hash_hmac("sha1", $contentJson, config('git.githubWebhookToken'));

        return [$contentJson, $signature];
    }
}
