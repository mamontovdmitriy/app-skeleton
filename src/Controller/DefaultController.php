<?php

namespace App\Controller;

use App\Helpers\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function index(Request $request)
    {
        return $this->redirectToRoute('main', ['_locale' => $this->getMyLocale($request)]);
    }

    /**
     * @param Request $request
     * @param RouterInterface $router
     * @return RedirectResponse
     */
    public function locale(Request $request, RouterInterface $router)
    {
        $locale = $request->get('_locale'); // todo check locale => _locale
        $request->getSession()->set('_locale', $locale);
        $referer = $request->headers->get('referer', '');
        $route = $router->match($referer);

        return $this->redirectToRoute($route['_route'], ['_locale' => $locale]);
    }

    /**
     * @return Response
     */
    public function main()
    {
        return $this->render('default/main.html.twig', []);
    }
}
