<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Enum\OrderStatusEnum;
use Exception;

class ConfirmOrderService
{
    /**
     * @throws Exception
     */
    public function process(CartService $cartService): Order
    {
        $items = $cartService->getCartItems();

        if (count($items) === 0) {
            throw new Exception('Your cart is empty.');
        }

        // Validate stock for all items first
        foreach ($items as $item) {
            /** @var Product $product */
            $product = $item['product'];
            $quantity = $item['quantity'];

            if ($product->stock < $quantity) {
                throw new Exception(sprintf('Not enough stock for "%s". Only %d left.', $product->name, $product->stock));
            }
        }

        // Create new Order entity
        $order = new Order();
        $order->status = OrderStatusEnum::CONFIRMED;
        $totalPrice = 0.0;

        // Deduct stock and add items to order
        foreach ($items as $item) {
            /** @var Product $product */
            $product = $item['product'];
            $quantity = $item['quantity'];
            $price = $product->price;

            $product->stock -= $quantity;

            $orderItem = new OrderItem($product, $quantity, $price);
            $order->addItem($orderItem);

            $totalPrice += (float)$price * $quantity;
        }

        $order->totalPrice = number_format($totalPrice, 2, '.', '');
        $cartService->clear();

        return $order;
    }
}
