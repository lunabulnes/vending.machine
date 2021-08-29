<?php

namespace App\Infrastructure\Cli\Command;

use App\Application\UseCase\AddStock;
use App\Domain\Catalog\Exception\InvalidPriceException;
use App\Domain\Catalog\Exception\InvalidProductException;
use App\Domain\Catalog\Exception\InvalidQuantityException;
use App\Infrastructure\Cli\Command;
use App\Infrastructure\Cli\Exception\MissingArgumentException;

class AddStockCommand implements Command
{
    private AddStock $addStock;

    public function __construct(AddStock $addStock)
    {
        $this->addStock = $addStock;
    }


    /**
     * @throws InvalidPriceException
     * @throws InvalidProductException
     * @throws InvalidQuantityException
     * @throws MissingArgumentException
     */
    public function __invoke(array $args): void
    {
        if (!isset($args[0]) || !is_string($args[0])) {
            throw new MissingArgumentException('product_name');
        }

        if (!isset($args[1]) || !intval($args[1])) {
            throw new MissingArgumentException('price');
        }

        if (!isset($args[2]) || !intval($args[2])) {
            throw new MissingArgumentException('quantity');
        }

        $this->addStock->execute($args[0], intval($args[1]), intval($args[2]));
    }
}
