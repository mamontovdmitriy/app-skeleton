<?php

namespace App\Controller;

use App\Entity\User;
use App\Forms\Cabinet\UploadAvatarType;
use App\Forms\Cabinet\UserProfileType;
use App\Helpers\Controller;
use App\Helpers\Flash;
use App\Service\AvatarService;
use App\Service\UserService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CabinetController
 * @package App\Controller
 */
class CabinetController extends Controller
{
    /**
     * Settings page.
     *
     * @Route(path="/cabinet/settings/", name="settings")
     *
     * @param Request $request
     * @param AvatarService $avatarService
     * @param UserService $userService
     * @param string $locales
     *
     * @return Response
     * @throws Exception
     */
    public function settings(Request $request, AvatarService $avatarService, UserService $userService, $locales)
    {
        /** @var User $user */
        $user = $this->getUser();

        // Handle settings form
        $form = $this->createForm(UserProfileType::class, $user, ['locales' => $locales]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userService->saveUser($form->getData());
            $this->addFlash(Flash::TYPE_SUCCESS, $this->trans('message.settings.save'));
            return $this->redirectToRoute('settings');
        }

        // Handle avatar uploading form
        $formUpload = $this->createForm(UploadAvatarType::class, null, [
            'src' => $avatarService->getAvatarUrl($user),
        ]);
        $formUpload->handleRequest($request);
        if ($formUpload->isSubmitted() && $formUpload->isValid()) {
            $success = $avatarService->saveAvatar($user, $formUpload[UploadAvatarType::IMAGE_NAME]->getData());
            if ($success) {
                $this->addFlash(Flash::TYPE_SUCCESS, $this->trans('message.upload.avatar'));
            } else {
                $this->addFlash(Flash::TYPE_ERROR, $this->trans('error.upload.avatar'));
            }
            return $this->redirectToRoute('settings');
        }

        return $this->render('cabinet/settings.html.twig', [
            'user' => $this->getUser(),
            'form' => $form->createView(),
            'formUpload' => $formUpload->createView(),
        ]);
    }
}
