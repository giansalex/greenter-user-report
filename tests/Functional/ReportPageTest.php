<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 10/10/2017
 * Time: 10:54 AM
 */

namespace Tests\Functional;

/**
 * Class ReportPageTest
 * @package Tests\Functional
 */
class ReportPageTest extends BaseTestCase
{
    public function testGetReportPage()
    {
        $response = $this->runApp('GET', '/report');

        $this->assertEquals(405, $response->getStatusCode());
        $this->assertContains('Method not allowed', (string)$response->getBody());
    }

    public function testPostReportPage()
    {
        $response = $this->runApp('POST', '/report');

        $this->assertEquals(302, $response->getStatusCode());
    }
}