<?php

declare(strict_types=1);

namespace App\Message;

use App\Entity\Order;

class OrderCreatedMessage
{
    public function __construct(
        public readonly Order $order,
    ) {}
}
