<?php

namespace Tests\Functional;

class HomepageTest extends BaseTestCase
{
    /**
     * Test that the index route returns a rendered response containing the text 'SlimFramework' but not a greeting
     */
    public function testGetLoginPage()
    {
        $response = $this->runApp('GET', '/login');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Login', (string)$response->getBody());
    }

    public function testGetRegisterPage()
    {
        $response = $this->runApp('GET', '/register');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Login', (string)$response->getBody());
    }

    /**
     * Test that the index route won't accept a post request
     */
    public function testPostHomepageNotAllowed()
    {
        $response = $this->runApp('POST', '/', ['test']);

        $this->assertEquals(405, $response->getStatusCode());
        $this->assertContains('Method not allowed', (string)$response->getBody());
    }
}