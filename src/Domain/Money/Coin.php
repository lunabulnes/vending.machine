<?php
declare(strict_types = 1);

namespace App\Domain\Money;

use App\Domain\Money\Exception\InvalidCoinException;

class Coin
{
    private const ONE_UNIT = 100;
    private const TWENTY_FIVE_CENTS = 25;
    private const TEN_CENTS = 10;
    private const FIVE_CENTS = 5;
    private const VALID_VALUES = [
        self::ONE_UNIT,
        self::TWENTY_FIVE_CENTS,
        self::TEN_CENTS,
        self::FIVE_CENTS
    ];

    private $value;

    private function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @throws InvalidCoinException
     */
    public static function create(int $value): Coin
    {
        if (!self::isValid($value)) {
            throw new InvalidCoinException();
        }
        return new self($value);
    }

    private static function isValid(int $value): bool
    {
        return in_array($value, self::VALID_VALUES);
    }

    public function value(): int
    {
        return $this->value;
    }
}
