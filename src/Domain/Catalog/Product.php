<?php
declare(strict_types = 1);

namespace App\Domain\Catalog;

use App\Domain\Catalog\Exception\InvalidProductException;

class Product
{
    private const WATER = 'Water';
    private const JUICE = 'Juice';
    private const SODA = 'Soda';
    private const VALID_NAMES = [
        self::WATER,
        self::JUICE,
        self::SODA
    ];

    private string $name;

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @throws InvalidProductException
     */
    public static function create(string $name): Product
    {
        if (!self::isValid($name)) {
            throw new InvalidProductException();
        }
        return new self($name);
    }

    private static function isValid(string $name): bool
    {
        return in_array($name, self::VALID_NAMES);
    }

    public function name(): string
    {
        return $this->name;
    }
}
