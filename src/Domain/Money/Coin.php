<?php
declare(strict_types = 1);

namespace App\Domain\Money;

use App\Domain\Money\Exception\InvalidCoinException;

class Coin
{
    private const FIVE_CENTS = 0.05;
    private const TEN_CENTS = 0.10;
    private const TWENTY_FIVE_CENTS = 0.25;
    private const ONE_UNIT = 1;
    private const VALID_VALUES = [
        self::FIVE_CENTS,
        self::TEN_CENTS,
        self::TWENTY_FIVE_CENTS,
        self::ONE_UNIT
    ];

    private $value;

    private function __construct(float $value)
    {
        $this->value = $value;
    }

    /**
     * @throws InvalidCoinException
     */
    public static function create(float $value): Coin
    {
        if (!self::isValid($value)) {
            throw new InvalidCoinException();
        }
        return new self($value);
    }

    private static function isValid(float $value): bool
    {
        return in_array($value, self::VALID_VALUES);
    }

    public function value(): float
    {
        return $this->value;
    }
}
