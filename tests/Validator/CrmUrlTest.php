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
    public function testValidatedBy(): void
    {
        $crmUrl = new CrmUrl();

        self::assertEquals('An invalid domain is specified.', $crmUrl->domainFail);
        self::assertEquals('Incorrect Host URL.', $crmUrl->noValidUrlHost);
        self::assertEquals('The port does not need to be specified.', $crmUrl->portFail);
        self::assertEquals('Incorrect protocol. Only https is allowed.', $crmUrl->schemeFail);
        self::assertEquals('The domain path must be empty.', $crmUrl->pathFail);
        self::assertEquals(CrmUrl::class . 'Validator', $crmUrl->validatedBy());
    }

    public function testGetTargets(): void
    {
        $crmUrl = new CrmUrl();
        self::assertEquals('property', $crmUrl->getTargets());
    }
}
