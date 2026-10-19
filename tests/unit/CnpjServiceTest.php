<?php

use App\Services\CnpjService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class CnpjServiceTest extends CIUnitTestCase
{
    public function testAcceptsThePublicOpenCnpjExampleValue(): void
    {
        $this->assertTrue(CnpjService::valido('00.000.000/0001-91'));
    }

    public function testRejectsRepeatedDigitsSequence(): void
    {
        $this->assertFalse(CnpjService::valido('11.111.111/1111-11'));
    }

    public function testAcceptsAValidCnpjShape(): void
    {
        $this->assertTrue(CnpjService::valido('60.318.981/0001-41'));
    }
}
