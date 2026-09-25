<?php

declare(strict_types=1);

namespace EphpicMan\EphpicMan\Tests;

use EphpicMan\EphpicMan\Autoloader;
use EphpicMan\EphpicMan\Plugin;
use EphpicMan\EphpicMan\UnitTesting\Test;

final class PluginTest implements Test
{
    public function run(): bool
    {
        return $this->returnsSameInstance()
            && $this->providesAutoloader();
    }

    private function returnsSameInstance(): bool
    {
        $first = Plugin::instance();
        $second = Plugin::instance();

        return $first === $second;
    }

    private function providesAutoloader(): bool
    {
        return Plugin::instance()
            ->autoloader()
            instanceof Autoloader;
    }
}
