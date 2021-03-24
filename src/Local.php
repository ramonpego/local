<?php

namespace RamonPego\CLI;

use Closure;
use Symfony\Component\Process\Process;

class Local
{
    protected string $path;

    protected string $user;

    protected bool $quietMode = false;

    protected Closure $processConfigurationClosure;

    protected Closure $onOutput;

    public function __construct(string $path, string $user)
    {
        $this->path = $path;
        $this->user = $user;

        $this->processConfigurationClosure = fn(Process $process) => null;

        $this->onOutput = fn($type, $line) => null;
    }

    public static function create(string $path, string $user): self
    {
        return new static($path, $user);
    }


    public function configureProcess(Closure $processConfigurationClosure): self
    {
        $this->processConfigurationClosure = $processConfigurationClosure;

        return $this;
    }

    public function onOutput(Closure $onOutput): self
    {
        $this->onOutput = $onOutput;

        return $this;
    }

    /**
     * @param string|array $command
     *
     * @return string
     */
    public function getExecuteCommand($command): string
    {
        $commands = $this->wrapArray($command);

        $commandString = implode(PHP_EOL, $commands);

        return "(cd {$this->fullPath()} && runuser {$this->user} {$commandString} )";
    }

    /**
     * @param string|array $command
     *
     * @return \Symfony\Component\Process\Process
     */
    public function execute($command): Process
    {
        $sshCommand = $this->getExecuteCommand($command);

        return $this->run($sshCommand);
    }

    /**
     * @param string|array $command
     *
     * @return \Symfony\Component\Process\Process
     */
    public function executeAsync($command): Process
    {
        $sshCommand = $this->getExecuteCommand($command);

        return $this->run($sshCommand, 'start');
    }

    protected function wrapArray($arrayOrString): array
    {
        return (array)$arrayOrString;
    }

    protected function run(string $command, string $method = 'run'): Process
    {
        $process = Process::fromShellCommandline($command);

        $process->setTimeout(0);

        ($this->processConfigurationClosure)($process);

        $process->{$method}($this->onOutput);

        return $process;
    }

    protected function fullPath(): string
    {
        return $this->path . '/' . $this->user;
    }
}
