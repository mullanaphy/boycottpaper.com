<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class IsCurrentPageFunction extends AbstractExtension
{
    public function __construct(private readonly RequestStack $requestStack)
    {

    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('isCurrentPage', [$this, 'isCurrentPage']),
        ];
    }

    public function isCurrentPage(string $page): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        return $request->getPathInfo() === $page || $request->getUri() === $page;
    }
}
