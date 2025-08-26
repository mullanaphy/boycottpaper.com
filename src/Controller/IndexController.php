<?php

namespace App\Controller;

use App\Config;
use App\Repository\ComicRepository;
use App\Service\SearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ComicRepository $repository): Response
    {
        $latestComic = $repository->findLatestComic();
        if (!$latestComic) {
            return $this->render('empty.html.twig');
        }
        return $this->forward(implode('::', [ComicController::class, 'item']), ['slug' => $latestComic->getSlug()]);
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(Request $request, SearchService $service): Response
    {
        $q = $request->query->get('q');
        if (!$q) {
            return $this->redirectToRoute('index');
        }

        return $this->render('search.html.twig', $service->search($q, $request->query->get('pageId'), $request->query->get('limit')));
    }

    #[Route('/feed', name: 'feed', methods: ['GET'])]
    public function feed(ComicRepository $repository): Response
    {
        $latest = $repository->findLatestComic();
        $feed = $repository->findBy([], ['id' => 'DESC'], Config::SITE['feedLimit']);
        $response = $this->render('feed.xml.twig', [
            'feed' => $feed,
            'feedLimit' => Config::SITE['feedLimit'],
            'latest' => $latest?->getCreated() ?? new \DateTimeImmutable(),
        ]);
        $response->headers->set('Content-Type', 'application/rss+xml');

        return $response;
    }
}
