<?php

namespace App\Controller;

use App\Helpers\Controller;
use App\Service\UserService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProfileController
 * @package App\Controller
 */
class ProfileController extends Controller
{
    /**
     * @Route(path="/profile/{id}", name="profile")
     *
     * @param Request $request
     * @param UserService $userService
     *
     * @return Response
     * @throws NonUniqueResultException
     * @throws NotFoundHttpException
     */
    public function profile(Request $request, UserService $userService)
    {
        $id = $request->get('id');
        $user = $userService->findUserById($id);

        return $this->render('profile/profile.html.twig', ['user' => $user]);
    }
}
