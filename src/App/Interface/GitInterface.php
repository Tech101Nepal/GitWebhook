<?php

namespace Tech101\GitWebhook\App\Interface;

use Illuminate\Http\Request;

interface GitInterface
{
    /**
     * Checks if the request is valid or not
     *
     * @param Request $request
     *
     * @return object
     */
    public function parseRequest(Request $request): object;

    /**
     * Check the token that comes with the request matches the project's token
     *
     * @param Request $request
     *
     * @return void
     */
    public function validateSecret(Request $request): void;

    /**
     * Checks the event type is the required event type
     *
     * @param string $type
     *
     * @return void
     */
    public function validateEventType(string $type): void;

    /**
     * Checks the event state is the required event state
     *
     * @param string $state
     *
     * @return void
     */
    public function validateEventState(string $state): void;
}
