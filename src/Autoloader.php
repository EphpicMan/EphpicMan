<?php

declare(strict_types=1);

namespace EphpicMan\EphpicMan;

use InvalidArgumentException;

final class Autoloader
{
    /**
     * @var array<string, string>
     */
    private array $namespaces = [];

    /**
     * Register a PSR-4 namespace prefix.
     *
     * @param string $namespace Namespace prefix.
     * @param string $directory Base directory for the namespace.
     */
    public function addNamespace(
        string $namespace,
        string $directory
    ): void {
        $namespace = trim($namespace, '\\');

        if ($namespace === '') {
            throw new InvalidArgumentException(
                'Namespace cannot be empty.'
            );
        }

        $namespace .= '\\';

        $directory = rtrim(
            $directory,
            DIRECTORY_SEPARATOR . '/\\'
        );

        if ($directory === '') {
            throw new InvalidArgumentException(
                'Namespace directory cannot be empty.'
            );
        }

        $this->namespaces[$namespace] = $directory;

        /*
         * When namespace prefixes overlap, the most specific
         * namespace must be checked first.
         *
         * Example:
         *
         * Vendor\
         * Vendor\Package\
         */
        uksort(
            $this->namespaces,
            static fn (string $a, string $b): int
                => strlen($b) <=> strlen($a)
        );
    }

    /**
     * Register this autoloader with PHP.
     */
    public function register(): void
    {
        spl_autoload_register(
            [$this, 'load']
        );
    }

    /**
     * Resolve and load a class using PSR-4.
     */
    private function load(string $class): void
    {
        foreach ($this->namespaces as $namespace => $directory) {
            if (! str_starts_with($class, $namespace)) {
                continue;
            }

            $relativeClass = substr(
                $class,
                strlen($namespace)
            );

            $file = $directory
                . DIRECTORY_SEPARATOR
                . str_replace(
                    '\\',
                    DIRECTORY_SEPARATOR,
                    $relativeClass
                )
                . '.php';

            if (! is_file($file)) {
                continue;
            }

            require_once $file;

            return;
        }
    }
}
