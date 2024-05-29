<?php

declare(strict_types=1);

namespace Intervention\Validation\Rules;

use Intervention\Validation\AbstractRule;

class AustrianInsuranceNumber extends AbstractRule
{
    /**
     * Austrian insurance number length
     *
     * @var int
     */
    private int $length = 10;

    /**
     * Multiplier series to calculate checksum
     * https://www.sozialversicherung.at/cdscontent/?contentid=10007.820902&viewmode=content
     *
     * @var array<int>
     */
    private array $multiplierSeries = [
        3, 7, 9, 5, 8, 4, 2, 1, 6
    ];

    /**
     * Determine if the validation rule passes.
     *
     * @param mixed $value
     * @return bool
     */
    public function isValid(mixed $value): bool
    {
        $value = str_replace(' ', '', (string) $value);

        return is_numeric($value)
            && $this->startsNotWithZero($value)
            && $this->hasValidLength($value)
            && $this->checkChecksum($value);
    }

    private function hasValidLength(string $svnumber): bool
    {
        return $this->length === strlen($svnumber);
    }

    private function startsNotWithZero(string $svnumber): bool
    {
        return (int) $svnumber[0] !== 0;
    }

    private function checkChecksum(string $svnumber): bool
    {
        if (strlen($svnumber) !== $this->length) {
            return false;
        }

        $checksumSVNumber = (int) $svnumber[3];
        $svnumberWithoutChecksum = substr($svnumber, 0, 3) . substr($svnumber, 4);

        $sum = 0;
        for ($c = 0, $cMax = strlen($svnumberWithoutChecksum); $c < $cMax; $c++) {
            $result = (int) $svnumberWithoutChecksum[$c] * $this->multiplierSeries[$c];
            $sum += $result;
        }
        $checksum = $sum % 11;

        if ($checksum === 10) {
            return false;
        }
        return $checksum === $checksumSVNumber;
    }
}