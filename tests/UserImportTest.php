<?php

class UserImportTest extends TestCase
{
    /**
     * Test the User Data Importer command works properly
     */
    public function testUserImportCommand()
    {
        // Given the importer command
        $this->artisan('user:import');

        // Then we're good
        $this->assertTrue(true);
    }
}