<?php
/**
 *  +-------------------------------------------------------------
 *  | Coffin [ 花开不同赏，花落不同悲。欲问相思处，花开花落时。 ]
 *  +-------------------------------------------------------------
 *  | Copyright (c) 2025~2025 All rights reserved.
 *  +-------------------------------------------------------------
 *  | @author: coffin's laughter | <chuanshuo_yongyuan@163.com>
 *  +-------------------------------------------------------------
 */

namespace Coffin\Support;

use Coffin\Exceptions\FailedException;
use Illuminate\Support\Composer as LaravelComposer;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Laravel\SerializableClosure\Exceptions\PhpVersionNotSupportedException;

class Composer extends LaravelComposer
{
    protected bool $ignorePlatformReqs = false;


    /**
     *
     * @return $this
     */
    public function ignorePlatFormReqs(): static
    {
        $this->ignorePlatformReqs = true;

        return $this;
    }


    /**
     * remove
     *
     * @param string $package
     */
    public function remove(string $package)
    {
        $this->runCommand([
            'remove', $package
        ]);
    }


    /**
     * require package
     * @param string $package
     * @return string
     * @throws PhpVersionNotSupportedException
     */
    public function require(string $package): string
    {
        $this->checkPHPVersion();

        $command = ['require', $package];

        return $this->runCommand($command);
    }

    /**
     * require dev-package
     *
     * @param string $package
     * @return string
     * @throws PhpVersionNotSupportedException
     */
    public function requireDev(string $package): string
    {
        $this->checkPHPVersion();

        $command = ['require', '--dev', $package];

        return $this->runCommand($command);
    }

    /**
     *
     * @throws PhpVersionNotSupportedException
     * @return void
     */
    protected function checkPHPVersion(): void
    {
        $composerJson = json_decode(File::get(base_path() . DIRECTORY_SEPARATOR . 'composer.json'), true);

        $phpVersion = PHP_VERSION;

        $needPHPVersion = Str::of($composerJson['require']['php'])->remove('^');

        if (version_compare($phpVersion, $needPHPVersion, '<') && !$this->ignorePlatformReqs) {
            throw new PhpVersionNotSupportedException("PHP $phpVersion 版本太低, 需要 PHP {$needPHPVersion}!如果想忽略版本要求, s可使用 {ignorePlatFormReqs} 方法然后安装");
        }
    }

    /**
     *
     * @param array $command
     * @return string
     */
    protected function runCommand(array $command): string
    {
        $command = array_merge($this->findComposer(), $command);

        if ($this->ignorePlatformReqs) {
            $command[] = '--ignore-platform-reqs';
        }

        $process = $this->getProcess($command);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new FailedException($process->getErrorOutput());
        }

        return $process->getOutput();
    }
}
