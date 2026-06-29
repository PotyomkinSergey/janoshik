<?php
declare(strict_types=1);

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

readonly class CartService
{
    public function __construct(
        private RequestStack $requestStack,
        private ProductRepository $productRepository,
    ) {}

    public function add(int $productId, int $quantity = 1): void
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        $session->set('cart', $cart);
    }

    public function decrease(int $productId, int $quantity = 1): void
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId] -= $quantity;
            if ($cart[$productId] <= 0) {
                unset($cart[$productId]);
            }
        }

        $session->set('cart', $cart);
    }

    public function remove(int $productId): void
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }

        $session->set('cart', $cart);
    }

    public function clear(): void
    {
        $session = $this->requestStack->getSession();
        $session->remove('cart');
    }

    /**
     * @return array[]
     * @todo return an array of objects
     */
    public function getCartItems(): array
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);
        $items = [];

        foreach ($cart as $productId => $quantity) {
            $product = $this->productRepository->find($productId);
            if ($product) {
                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => (float)$product->price * $quantity,
                ];
            } else {
                // Clean up deleted products from cart
                unset($cart[$productId]);
                $session->set('cart', $cart);
            }
        }

        return $items;
    }

    public function getTotalPrice(): float
    {
        $total = 0.0;
        foreach ($this->getCartItems() as $item) {
            $total += $item['subtotal'];
        }
        return $total;
    }

    public function getTotalQuantity(): int
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);
        $total = 0;
        foreach ($cart as $quantity) {
            $total += $quantity;
        }
        return $total;
    }
}
