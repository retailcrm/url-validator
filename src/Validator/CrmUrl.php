<?php

namespace RetailCrm\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class CrmUrl
 *
 * @Annotation
 * @Target({"PROPERTY"})
 * @package Retailcrm\Validator
 */
#[\Attribute]
class CrmUrl extends Constraint
{
    public string $schemeFail = 'Incorrect protocol. Only https is allowed.';

    public string $pathFail = 'The domain path must be empty.';

    public string $portFail = 'The port does not need to be specified.';

    public string $domainFail = 'An invalid domain is specified.';

    public string $noValidUrlHost = 'Incorrect Host URL.';

    public string $noValidUrl = 'Incorrect URL.';

    public string $queryFail = 'The query must be blank.';

    public string $fragmentFail = 'The fragment should be blank.';

    public string $authFail = 'No need to provide authorization data.';

    public string $getFileError = 'Unable to obtain reference values.';

    public function getTargets(): array|string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
