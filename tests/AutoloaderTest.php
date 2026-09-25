<?php

declare(strict_types=1);

namespace EphpicMan\EphpicMan\Tests;

use EphpicMan\EphpicMan\Autoloader;
use EphpicMan\EphpicMan\UnitTesting\Test;

final class AutoloaderTest implements Test
{
    public function run(): bool
    {
        return $this->canRegisterNamespace()
            && $this->canLoadClass()
            && $this->ignoresUnknownClass()
            && $this->canRegisterMultipleNamespaces()
            && $this->canNormaliseNamespace();
    }

    private function canRegisterNamespace(): bool
    {
        $autoloader = new Autoloader();

        $autoloader->addNamespace(
            'EphpicMan\Tests\Fixtures',
            __DIR__ . '/fixtures'
        );

        return true;
    }

    private function canLoadClass(): bool
    {
        $autoloader = new Autoloader();

        $autoloader->addNamespace(
            'EphpicMan\Tests\Fixtures',
            __DIR__ . '/fixtures'
        );

        $autoloader->register();

        return class_exists(
            'EphpicMan\Tests\Fixtures\Example'
        );
    }

    private function ignoresUnknownClass(): bool
    {
        $autoloader = new Autoloader();

        $autoloader->addNamespace(
            'EphpicMan\Tests\Fixtures',
            __DIR__ . '/fixtures'
        );

        $autoloader->register();

        return ! class_exists(
            'EphpicMan\Tests\Fixtures\DoesNotExist'
        );
    }

    private function canRegisterMultipleNamespaces(): bool
    {
        $autoloader = new Autoloader();

        $autoloader->addNamespace(
            'EphpicMan\Tests\Fixtures',
            __DIR__ . '/fixtures'
        );

        $autoloader->addNamespace(
            'EphpicMan\Tests\OtherFixtures',
            __DIR__ . '/other-fixtures'
        );

        return true;
    }

    private function canNormaliseNamespace(): bool
    {
        $autoloader = new Autoloader();

        $autoloader->addNamespace(
            '\\EphpicMan\\Tests\\Fixtures\\',
            __DIR__ . '/fixtures/'
        );

        $autoloader->register();

        return class_exists(
            'EphpicMan\Tests\Fixtures\Normalised'
        );
    }
}
