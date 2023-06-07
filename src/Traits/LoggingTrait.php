<?php

namespace Tech101\Webhook\Traits;

use Illuminate\Support\Facades\Log;

trait LoggingTrait
{
    /**
     * Stores the message in log
     *
     * @param string $message
     *
     * @return void
     */
    public function log(string $message): void
    {
        $log_file = 'git-log-' . date('Y-m-d') . '.log';
        Log::info($message, ['file' => $log_file]);
    }
}
