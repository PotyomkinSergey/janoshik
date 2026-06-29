<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Service\PageViewService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

readonly class PageViewSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private PageViewService $pageViewService,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onRequest',
        ];
    }

    public function onRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if ($request->getMethod() !== Request::METHOD_GET) {
            return;
        }
        $this->pageViewService->increment();
    }
}
