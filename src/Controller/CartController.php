<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Message\OrderCreatedMessage;
use App\Service\CartService;
use App\Service\ConfirmOrderService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cart')]
class CartController extends AbstractController
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly ConfirmOrderService $confirmOrderService,
        private readonly EntityManagerInterface $em,
        private readonly MessageBusInterface $bus,
    ) {}

    #[Route('/', name: 'cart_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'items' => $this->cartService->getCartItems(),
            'total' => $this->cartService->getTotalPrice(),
        ]);
    }

    #[Route('/add/{id}', name: 'cart_add', methods: ['POST'])]
    public function add(Product $product): Response
    {
        if ($product->stock <= 0) {
            $this->addFlash('error', sprintf('"%s" is out of stock.', $product->name));
            return $this->redirectToRoute('product_index');
        }

        $this->cartService->add($product->id);
        $this->addFlash('success', sprintf('"%s" added to cart.', $product->name));

        return $this->redirectToRoute('product_index');
    }

    #[Route('/decrease/{id}', name: 'cart_decrease', methods: ['POST'])]
    public function decrease(Product $product): Response
    {
        $this->cartService->decrease($product->id);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/remove/{id}', name: 'cart_remove', methods: ['POST'])]
    public function remove(Product $product): Response
    {
        $this->cartService->remove($product->id);
        $this->addFlash('info', sprintf('"%s" removed from cart.', $product->name));
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/confirm', name: 'cart_confirm', methods: ['POST'])]
    public function confirm(): Response
    {
        try {
            $order = $this->confirmOrderService->process($this->cartService);
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('cart_index');
        }

        $this->em->persist($order);
        $this->em->flush();
        $this->bus->dispatch(new OrderCreatedMessage($order));

        $this->addFlash('success', sprintf('Order #%d confirmed successfully!', $order->id));

        return $this->redirectToRoute('product_index');
    }

    #[Route('/decline', name: 'cart_decline', methods: ['POST'])]
    public function decline(): Response
    {
        $this->cartService->clear();
        $this->addFlash('info', 'Order canceled.');

        return $this->redirectToRoute('product_index');
    }
}
