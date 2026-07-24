<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure the shared-catalog connection also uses in-memory SQLite during tests.
        // phpunit.xml sets SHARED_DB_CONNECTION=sqlite; this wires the config key.
        config(['database.shared' => 'sqlite']);

        // Disable Vite asset bundler in tests (no build/ manifest in CI).
        $this->withoutVite();
    }
}
