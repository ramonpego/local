<?php

namespace Ramon\Local\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;
use Ramon\Local\Local;
use Symfony\Component\Process\Process;

class SshTest extends TestCase
{
    use MatchesSnapshots;

    private Local $ssh;

    public function setUp(): void
    {
        parent::setUp();

        $this->ssh = (new Local('/home/ramon/PhpstormProjects/sistema_adm', 'ramon'));
    }

    /** @test */
    public function it_can_run_a_single_command()
    {
        $command = $this->ssh->getExecuteCommand('whoami');

        $this->assertMatchesSnapshot($command);
    }

    /** @test */
    public function it_can_run_multiple_commands()
    {
        $command = $this->ssh->getExecuteCommand(['whoami', 'cd /var/log']);

        $this->assertMatchesSnapshot($command);
    }



    /** @test */
    public function it_can_instantiate_via_the_create_method()
    {
        $this->assertInstanceOf(Local::class, Local::create('user', 'example.com'));
    }


    /** @test */
    public function it_can_enable_quiet_mode()
    {
        $command = (new Local('user', 'example.com'))->enableQuietMode()->getExecuteCommand('whoami');

        $this->assertMatchesSnapshot($command);
    }

    /** @test */
    public function it_can_configure_the_used_process()
    {
        $command = $this->ssh->configureProcess(function (Process $process) {
            $process->setTimeout(0);
        })->getExecuteCommand('whoami');

        $this->assertMatchesSnapshot($command);
    }
}
