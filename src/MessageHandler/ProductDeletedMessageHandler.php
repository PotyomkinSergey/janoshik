<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\ProductCreatedMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ProductDeletedMessageHandler
{
    public function __invoke(ProductCreatedMessage $message): void
    {
        echo $message->content;
    }
}
