<?php

namespace Tech101\GitWebhook\Service;

use Tech101\GitWebhook\Traits\LoggingTrait;

class Git
{
    use LoggingTrait;

    /**
     * @var array<string> $output
     * @var string $location
     * @var string $defaultBranch
     */
    public array $output;
    public string $location;
    public string $defaultBranch;

    public function __construct(string $location = "..")
    {
        $this->output = [];
        $this->location = $location;
        $this->defaultBranch = config('git.defaultBranch');
    }

    /**
     * Converts ouput to JSON and stores the log
     *
     * @return void
     */
    public function storeLog(): void
    {
        $this->log(json_encode($this->output));
    }

    /**
     * Change the directory to the git location
     *
     * @return self
     */
    public function changeDirectory(): self
    {
        exec("cd {$this->location}", $this->output);
        $this->storeLog();

        return $this;
    }

    /**
     * Fetch the updates made in the git
     *
     * @return self
     */
    public function fetch(): self
    {
        exec("git fetch", $this->output);
        $this->storeLog();

        return $this;
    }

    /**
     * Resets the overall changes in the repo to git
     *
     * @return self
     */
    public function reset(): self
    {
        exec("git reset --hard", $this->output);
        $this->storeLog();

        return $this;
    }

    /**
     * Pulls the updates made in the branch
     *
     * @return self
     */
    public function pull(): self
    {
        exec("git pull origin {$this->defaultBranch}", $this->output);
        $this->storeLog();

        return $this;
    }

    /**
     * Checks out to the given tag
     *
     * @param string $ref
     *
     * @return self
     */
    public function checkoutTag(string $ref): self
    {
        exec("git checkout " . $ref, $this->output);
        $this->storeLog();

        return $this;
    }
}
