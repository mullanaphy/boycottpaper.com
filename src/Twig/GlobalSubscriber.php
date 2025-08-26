<?php

namespace App\Twig;

use App\Config;
use App\Entity\Comic;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Twig\Environment;

class GlobalSubscriber implements EventSubscriberInterface
{
    const EXPIRATION = 86400 * 7;

    public function __construct(
        private readonly Environment            $twig,
        private readonly EntityManagerInterface $entityManager,
        private readonly TagAwareCacheInterface $cache
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function injectGlobalVariables(): void
    {
        $this->twig->addGlobal('site', Config::SITE);
        $this->twig->addGlobal('social_media', Config::SOCIAL_MEDIA);
        $this->twig->addGlobal('header_navigation', $this->cache->get('header_navigation', function (ItemInterface $item): array {
            $item->expiresAfter(self::EXPIRATION);
            $item->tag('comic');
            $comicRepository = $this->entityManager->getRepository(Comic::class);

            return [
                'current' => $comicRepository->findOneBy([], ['id' => 'DESC'])?->getSlug(),
                'first' => $comicRepository->findOneBy([], ['id' => 'ASC'])?->getSlug(),
            ];
        }));
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::CONTROLLER => 'injectGlobalVariables'];
    }
}
