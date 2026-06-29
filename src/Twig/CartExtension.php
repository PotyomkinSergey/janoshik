<?php
declare(strict_types=1);

namespace App\Twig;

use App\Service\CartService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CartExtension extends AbstractExtension
{
    public function __construct(
        private readonly CartService $cartService,
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('cart_total_quantity', [$this->cartService, 'getTotalQuantity']),
            new TwigFunction('cart_total_price', [$this->cartService, 'getTotalPrice']),
        ];
    }
}
