<?php

declare(strict_types=1);

namespace Intervention\Validation\Tests\Rules;

use PHPUnit\Framework\Attributes\DataProvider;
use Intervention\Validation\Rules\Ean;
use PHPUnit\Framework\TestCase;

final class EanTest extends TestCase
{
    #[DataProvider('dataProvider')]
    public function testValidation($result, $value): void
    {
        $valid = (new Ean())->isValid($value);
        $this->assertEquals($result, $valid);
    }

    #[DataProvider('dataProviderEan13')]
    public function testValidationEan13($result, $value): void
    {
        $valid = (new Ean([13]))->isValid($value);
        $this->assertEquals($result, $valid);
    }

    #[DataProvider('dataProviderEan8')]
    public function testValidationEan8($result, $value): void
    {
        $valid = (new Ean([8]))->isValid($value);
        $this->assertEquals($result, $valid);
    }

    public static function dataProvider(): array
    {
        return [
            [true, '9789510475270'],
            [true, '4012345678901'],
            [true, '0712345678911'],
            [true, '5901234123457'],
            [true, '40123455'],
            [true, '96385074'],
            [true, '65833254'],
            [false, 'foo'],
            [false, '0000000000001'],
            [false, 'FFFFFFFFFFFFF'],
            [false, 'FFFFFFFFFFFF0'],
            [false, '4012345678903'],
            [false, '1xxxxxxxxxxx0'],
            [false, '4012342678901'],
            [false, '07123456789110712345678911'],
            [false, '10123455'],
            [false, '40113455'],
            [false, '978-3499255496'],
            [false, '00123456000018'], // GTIN-14
            [false, '012345678905'], // GTIN-12
            [true, 5901234123457],
            [true, 40123455],
        ];
    }

    public static function dataProviderEan13(): array
    {
        return [
            [true, '9789510475270'],
            [true, '4012345678901'],
            [true, '0712345678911'],
            [true, '5901234123457'],
            [true, 5901234123457],
            [false, '40123455'],
            [false, '96385074'],
            [false, '65833254'],
        ];
    }

    public static function dataProviderEan8(): array
    {
        return [
            [false, '4012345678901'],
            [false, '0712345678911'],
            [false, '5901234123457'],
            [true, '40123455'],
            [true, '96385074'],
            [true, '65833254'],
            [true, 65833254],
        ];
    }
}
