<?php

declare(strict_types=1);

namespace EphpicMan\EphpicMan\UnitTesting;

use Throwable;

final class Runner
{
    private string $testsDirectory;

    public function __construct(string $testsDirectory)
    {
        $this->testsDirectory = rtrim(
            $testsDirectory,
            DIRECTORY_SEPARATOR
        );
    }

    /**
     * @return array<int, array{
     *     name: string,
     *     passed: bool,
     *     error: ?string
     * }>
     */
    public function run(): array
    {
        $this->loadTests();

        $results = [];

        foreach ($this->findTestClasses() as $class) {
            $results[] = $this->runTest($class);
        }

        return $results;
    }

    private function loadTests(): void
    {
        $files = glob(
            $this->testsDirectory . '/*Test.php'
        );

        foreach ($files as $file) {
            require_once $file;
        }
    }

    /**
     * @return array<int, class-string<Test>>
     */
    private function findTestClasses(): array
    {
        $classes = get_declared_classes();

        return array_values(
            array_filter(
                $classes,
                static function (string $class): bool {
                    return is_subclass_of($class, Test::class);
                }
            )
        );
    }

    /**
     * @param class-string<Test> $class
     *
     * @return array{
     *     name: string,
     *     passed: bool,
     *     error: ?string
     * }
     */
    private function runTest(string $class): array
    {
        try {
            $test = new $class();

            return [
                'name' => $class,
                'passed' => $test->run(),
                'error' => null,
            ];
        } catch (Throwable $exception) {
            return [
                'name' => $class,
                'passed' => false,
                'error' => $exception->getMessage(),
            ];
        }
    }
}
