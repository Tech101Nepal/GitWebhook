<?php

namespace Tech101\GitWebhook\Factories;

class GitlabRequestFactory
{
    /**
     * Generates dummy data for gitlab request merge
     *
     * @param string $object_kind
     * @param string $state
     *
     * @return array<string>
     */
    public function mergeRequestData(string $object_kind, string $state): array
    {
        return [
            "object_kind" => $object_kind,
            "event_type" => $object_kind,
            "user" => [
                "id" => fake()->numberBetween(1, 100),
                "name" => fake()->name(),
                "username" => fake()->name(),
            ],
            "project" => [
                "id" => fake()->phoneNumber(),
                "name" => fake()->name(),
                "description" => "",
                "default_branch" => fake()->name,
                "namespace" =>  fake()->name,
            ],
            "object_attributes" => [
                "state" => $state
            ]
        ];
    }

    /**
     * Generates dummy data for gitlab tag request
     *
     * @param string $object_kind
     * @param string $ref
     *
     * @return array<string>
     */
    public function tagRequestData(string $object_kind, string $ref): array
    {
        return [
            "object_kind" => $object_kind,
            "event_name" => $object_kind,
            "before" => "0000000000000000000000000000000000000000",
            "after" => fake()->numerify,
            "ref" => "refs/tags/" . $ref,
            "checkout_sha" => fake()->numerify(),
            "message" => fake()->text("30"),
            "user_id" => fake()->phoneNumber(),
            "user_name" => fake()->name(),
        ];
    }
}
