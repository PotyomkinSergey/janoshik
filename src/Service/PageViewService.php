<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\Cache\CacheInterface;

readonly class PageViewService
{
    private const int USER_TTL = 60;

    private const string PAGE_VIEWS_GRAND_TOTAL = 'page_views_grand_total';

    public function __construct(
        private CacheInterface $cache,
    ) {}

    public function getTotalViews(): int
    {
        $item = $this->cache->getItem(self::PAGE_VIEWS_GRAND_TOTAL);

        return (int) ($item->get() ?? 0);
    }

    public function increment(): void
    {
        $item  = $this->cache->getItem(self::PAGE_VIEWS_GRAND_TOTAL);
        $total = (int) ($item->get() ?? 0);
        $item->set($total + 1);
        $item->expiresAfter(self::USER_TTL);
        $this->cache->save($item);
    }
}
