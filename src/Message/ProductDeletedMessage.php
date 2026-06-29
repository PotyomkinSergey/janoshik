<?php

declare(strict_types=1);

namespace App\Message;

class ProductDeletedMessage
{
    public function __construct(
        public readonly string $content,
    ) {}
}
