<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\Order;
use App\Message\OrderCreatedMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class OrderCreatedMessageHandler
{
    public function __invoke(OrderCreatedMessage $message): Order
    {
        return $message->order;
    }
}
