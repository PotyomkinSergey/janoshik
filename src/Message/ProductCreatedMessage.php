<?php

declare(strict_types=1);

namespace App\Message;

class ProductCreatedMessage
{
    public function __construct(
        public readonly string $content,
    ) {}
}
