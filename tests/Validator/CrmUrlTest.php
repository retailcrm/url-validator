<?php

namespace RetailCrm\Tests\Validator;

use PHPUnit\Framework\TestCase;
use RetailCrm\Validator\CrmUrl;

/**
 * Class CrmUrlTest
 *
 * @package RetailCrm\Tests\Validator
 */
class CrmUrlTest extends TestCase
{
    public function testGetTargets(): void
    {
        $crmUrl = new CrmUrl();
        self::assertEquals('property', $crmUrl->getTargets());
    }
}
