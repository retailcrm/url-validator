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
class CrmUrl extends Constraint
{
    /**
     * @var string
     */
    public $schemeFail = 'Incorrect protocol. Only https is allowed.';

    /**
     * @var string
     */
    public $pathFail = 'The domain path must be empty.';

    /**
     * @var string
     */
    public $portFail = 'The port does not need to be specified.';

    /**
     * @var string
     */
    public $domainFail = 'An invalid domain is specified.';

    /**
     * @var string
     */
    public $noValidUrlHost = 'Incorrect Host URL.';

    /**
     * @var string
     */
    public $noValidUrl = 'Incorrect URL.';

    /**
     * @var string
     */
    public $queryFail = 'The query must be blank.';

    /**
     * @var string
     */
    public $fragmentFail = 'The fragment should be blank.';

    /**
     * @var string
     */
    public $authFail = 'No need to provide authorization data.';

    /**
     * @var string
     */
    public $getFileError = 'Unable to obtain reference values.';

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
