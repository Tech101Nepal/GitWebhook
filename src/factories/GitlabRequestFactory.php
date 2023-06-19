<?php

namespace Tech101\Webhook\factories;

class GitlabRequestFactory
{
    public function mergeRequestData($object_kind, $state): array
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

    public function tagRequestData($object_kind, $ref): array
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
