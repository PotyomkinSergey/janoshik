<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_item')]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public private(set) ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) ?Order $order = null;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) ?Product $product = null;

    #[ORM\Column]
    public int $quantity = 1;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    public string $price = '0.00';

    public function __construct(Product $product, int $quantity, string $price)
    {
        $this->product = $product;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }
}
