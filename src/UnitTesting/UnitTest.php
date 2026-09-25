<?php

declare(strict_types=1);

namespace EphpicMan\EphpicMan\UnitTesting;

final class UnitTest
{
    public function run(): bool
    {
        $expected = 10;
        $actual = 10;

        return $expected === $actual;
    }
}
