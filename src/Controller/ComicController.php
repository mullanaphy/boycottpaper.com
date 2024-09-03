<?php

namespace App\Controller;

use App\Entity\Comic;
use App\Entity\Panel;
use App\Repository\ComicRepository;
use App\Service\MetaDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ComicController extends AbstractController
{
    #[Route('/comic', name: 'comic_archive')]
    public function archive(Request $request, ComicRepository $repository): Response
    {
        return $this->render('comic/archive.html.twig', [
            'page' => [
                'url' => 'comic_archive',
                'title' => 'Comic Archive',
                'parameters' => [],
            ],
            ...MetaDataService::paginator(
                $repository->all(),
                $request->query->getInt('pageId'),
                $request->query->getInt('limit'),
            )]);
    }

    #[Route('/comic/{slug}.html', name: 'comic_item')]
    public function item(string $slug, Request $request, ComicRepository $repository): Response
    {
        /* @var Comic $comic */
        $comic = $repository->findOneBy(['slug' => $slug]);

        $canonicalUrl = $this->generateUrl('comic_item', ['slug' => $comic->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL);
        $nextComic = $repository->findNextComic($comic);
        $previousComic = $repository->findPreviousComic($comic);

        return $this->render('comic/item.html.twig', [
            'comic' => $comic,
            'canonical_url' => $canonicalUrl,
            'meta' => MetaDataService::ldJson($comic, $canonicalUrl),
            'hidden_panel' => $comic->getHidden(),
            'next_comic' => $nextComic?->getSlug(),
            'previous_comic' => $previousComic?->getSlug(),
        ]);
    }
}
