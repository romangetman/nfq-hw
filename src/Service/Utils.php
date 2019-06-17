<?php

declare(strict_types=1);

namespace App\Service;


use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Utils
{
    use ContainerAwareTrait;

    public function __construct($container)
    {
        $this->setContainer($container);
    }

    public function mongoIdFromString(string $id): \MongoId
    {
        if (!\MongoId::isValid($id)) {
            throw new \InvalidArgumentException('Invalid ID format');
        }

        return new \MongoId($id);

    }
}
