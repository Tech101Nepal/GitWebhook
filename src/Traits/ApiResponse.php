<?php

namespace Tech101\GitWebhook\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Function to retun response in json
     *
     * @param bool $status
     * @param string $message
     * @param ?array<string> $payload
     * @param int $response_code
     *
     * @return JsonResponse
     */
    public function response(bool $status, string $message, ?array $payload = null, int $response_code = 200): JsonResponse
    {
        $response = [
            "status" => $status ? "success" : "error",
            "payload" => ["data" => $payload],
            "message" => json_decode($message) ?? $message
        ];

        if ($payload == null) {
            unset($response["payload"]);
        }

        return response()->json($response, $response_code);
    }

     /**
     * Function to retun success response in json
     *
     * @param ?array<string> $payload
     * @param string $message
     * @param int $response_code
     *
     * @return JsonResponse
     */
    public function successResponse(?array $payload, string $message, int $response_code = 200): JsonResponse
    {
        return $this->response(true, $message, $payload, $response_code);
    }

     /**
     * Function to retun error response in json
     *
     * @param string $message
     * @param int $response_code
     *
     * @return JsonResponse
     */
    public function errorResponse(string $message, int $response_code = 500): JsonResponse
    {
        return $this->response(false, $message, response_code: $response_code ?: 500);
    }
}
