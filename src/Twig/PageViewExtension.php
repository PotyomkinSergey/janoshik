<?php

declare(strict_types=1);

namespace App\Twig;

use App\Service\PageViewService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PageViewExtension extends AbstractExtension
{
    public function __construct(
        private readonly PageViewService $pageViewService,
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('page_view_total', [$this->pageViewService, 'getTotalViews'])
        ];
    }
}
