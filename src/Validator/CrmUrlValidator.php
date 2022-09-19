<?php

namespace RetailCrm\Validator;

use JsonException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class CrmUrlValidator
 *
 * @package Retailcrm\Validator
 */
class CrmUrlValidator extends ConstraintValidator
{
    public const BOX_DOMAINS_URL = "https://infra-data.retailcrm.tech/box-domains.json";
    public const CRM_DOMAINS_URL = "https://infra-data.retailcrm.tech/crm-domains.json";

    private Constraint $constraint;

    /**
     * Validate CRM URL
     *
     * @param mixed      $value URL from form
     * @param Constraint $constraint Restriction for validation
     */
    public function validate($value, Constraint $constraint): void
    {
        $this->constraint = $constraint;
        $filteredUrl = filter_var($value, FILTER_VALIDATE_URL);

        if (false === $filteredUrl) {
            $this->context->buildViolation($constraint->noValidUrl)->addViolation();

            return;
        }

        $urlArray = parse_url($filteredUrl);

        if ($this->checkUrlFormat($urlArray)) {
            $mainDomain = $this->getMainDomain($urlArray['host']);
            $existInCrm = $this->checkDomains(self::CRM_DOMAINS_URL, $mainDomain);
            $existInBox = $this->checkDomains(self::BOX_DOMAINS_URL, $urlArray['host']);

            if (false === $existInCrm && false === $existInBox) {
                $this->context->buildViolation($constraint->domainFail)->addViolation();
            }
        }
    }

    /**
     * @param array $crmUrl
     *
     * @return bool
     */
    private function checkUrlFormat(array $crmUrl): bool
    {
        $checkResult = true;
        $checkAuth = $this->checkAuth($crmUrl);
        $checkFragment = $this->checkFragment($crmUrl);
        $checkHost = $this->checkHost($crmUrl);
        $checkPath = $this->checkPath($crmUrl);
        $checkPort = $this->checkPort($crmUrl);
        $checkQuery = $this->checkQuery($crmUrl);
        $checkScheme = $this->checkScheme($crmUrl);

        if (
            !$checkAuth
            || !$checkFragment
            || !$checkHost
            || !$checkPath
            || !$checkPort
            || !$checkQuery
            || !$checkScheme
        ) {
            $checkResult = false;
        }

        return $checkResult;
    }

    /**
     * @param array $crmUrl
     *
     * @return bool
     */
    private function checkHost(array $crmUrl): bool
    {
        if (!isset($crmUrl['host'])) {
            $this->context->buildViolation($this->constraint->noValidUrlHost)->addViolation();

            return false;
        }

        return true;
    }

    /**
     * @param array $crmUrl
     *
     * @return bool
     */
    private function checkQuery(array $crmUrl): bool
    {
        if (isset($crmUrl['query']) && !empty($crmUrl['query'])) {
            $this->context->buildViolation($this->constraint->queryFail)->addViolation();

            return false;
        }

        return true;
    }

    /**
     * @param array $crmUrl
     *
     * @return bool
     */
    private function checkAuth(array $crmUrl): bool
    {
        if (
            (isset($crmUrl['pass']) && !empty($crmUrl['pass']))
            || (isset($crmUrl['user']) && !empty($crmUrl['user']))
        ) {
            $this->context->buildViolation($this->constraint->authFail)->addViolation();

            return false;
        }

        return true;
    }

    /**
     * @param array $crmUrl
     *
     * @return bool
     */
    private function checkFragment(array $crmUrl): bool
    {
        if (isset($crmUrl['fragment']) && !empty($crmUrl['fragment'])) {
            $this->context->buildViolation($this->constraint->fragmentFail)->addViolation();

            return false;
        }

        return true;
    }

    /**
     * @param array $crmUrl
     *
     * @return bool
     */
    private function checkScheme(array $crmUrl): bool
    {
        if (isset($crmUrl['scheme']) && $crmUrl['scheme'] !== 'https') {
            $this->context->buildViolation($this->constraint->schemeFail)->addViolation();

            return false;
        }

        return true;
    }

    /**
     * @param array $crmUrl
     *
     * @return bool
     */
    private function checkPath(array $crmUrl): bool
    {
        if (isset($crmUrl['path']) && $crmUrl['path'] !== '/' && $crmUrl['path'] !== '') {
            $this->context->buildViolation($this->constraint->pathFail)->addViolation();

            return false;
        }

        return true;
    }

    /**
     * @param array $crmUrl
     *
     * @return bool
     */
    private function checkPort(array $crmUrl): bool
    {
        if (isset($crmUrl['port']) && !empty($crmUrl['port'])) {
            $this->context->buildViolation($this->constraint->portFail)->addViolation();

            return false;
        }

        return true;
    }

    /**
     * @param string $domainUrl
     *
     * @return array
     */
    private function getValidDomains(string $domainUrl): array
    {
        try {
            $content = json_decode(file_get_contents($domainUrl), true, 512, JSON_THROW_ON_ERROR);

            return array_column($content['domains'], 'domain');
        } catch (JsonException $exception) {
            $this->context->buildViolation($this->constraint->getFileError)->addViolation();

            return [];
        }
    }

    /**
     * @param string $host
     *
     * @return string
     */
    private function getMainDomain(string $host): string
    {
        $hostArray = explode('.', $host);
        unset($hostArray[0]);

        return implode('.', $hostArray);
    }

    /**
     * @param string $crmDomainsUrl
     * @param string $domainHost
     *
     * @return bool
     */
    private function checkDomains(string $crmDomainsUrl, string $domainHost): bool
    {
        return in_array($domainHost, $this->getValidDomains($crmDomainsUrl), true);
    }
}
