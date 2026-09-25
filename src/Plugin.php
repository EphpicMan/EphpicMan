<?php

declare(strict_types=1);

namespace EphpicMan\EphpicMan;

use EphpicMan\EphpicMan\UnitTesting\Runner;

final class Plugin
{
    private static ?self $instance = null;

    private Autoloader $autoloader;

    private function __construct()
    {
        $this->autoloader = new Autoloader();

        $this->autoloader->addNamespace(
            'EphpicMan\EphpicMan',
            dirname(__DIR__) . '/src'
        );

        $this->autoloader->register();
    }

    public static function instance(): self
    {
        return self::$instance ??= new self();
    }

    public function autoloader(): Autoloader
    {
        return $this->autoloader;
    }

    public function boot(): void
    {
        add_action(
            'admin_menu',
            [$this, 'registerAdminMenu']
        );
    }

    public function registerAdminMenu(): void
    {
        add_menu_page(
            'Unit Testing',
            'Unit Testing',
            'manage_options',
            'ephpicman-unit-testing',
            [$this, 'renderUnitTestingPage'],
            'dashicons-yes-alt'
        );
    }

    public function renderUnitTestingPage(): void
    {
        $results = null;

        if (
            isset($_POST['ephpicman_run_tests'])
            && current_user_can('manage_options')
        ) {
            $runner = new \EphpicMan\EphpicMan\UnitTesting\Runner(
                dirname(__DIR__) . '/tests'
            );

            $results = $runner->run();
        }

        ?>
        <div class="wrap">

            <h1>Unit Testing</h1>

            <form method="post">
                <?php
                submit_button(
                    'Run Unit Tests',
                    'primary',
                    'ephpicman_run_tests'
                );
                ?>
            </form>

            <?php if ($results !== null) : ?>

                <h2>Results</h2>

                <table class="widefat">
                    <thead>
                        <tr>
                            <th>Test</th>
                            <th>Result</th>
                            <th>Error</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($results as $result) : ?>

                        <tr>

                            <td>
                                <?php
                                echo esc_html($result['name']);
                                ?>
                            </td>

                            <td>
                                <?php if ($result['passed']) : ?>

                                    <strong>PASS</strong>

                                <?php else : ?>

                                    <strong>FAIL</strong>

                                <?php endif; ?>
                            </td>

                            <td>
                                <?php
                                echo esc_html(
                                    $result['error'] ?? ''
                                );
                                ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>
                </table>

            <?php endif; ?>

        </div>
        <?php
    }

    public function init(): void
    {
    }

    public function run(): void
    {
    }

    public function activate(): void
    {
    }

    public function deactivate(): void
    {
    }

    public function uninstall(): void
    {
    }
}
