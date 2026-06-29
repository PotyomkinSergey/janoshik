<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\ProductDeletedMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ProductCreatedMessageHandler
{
    public function __invoke(ProductDeletedMessage $message): void
    {
        echo $message->content;
    }
}
