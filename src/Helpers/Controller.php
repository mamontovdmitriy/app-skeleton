<?php

namespace App\Helpers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class Controller
 * @package App\Controller
 */
class Controller extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Controller constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param string $id
     * @param array $parameters
     * @param null $domain
     * @param null $locale
     * @return string
     */
    public function trans($id, array $parameters = [], $domain = null, $locale = null)
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }


    /**
     * @param Request $request
     * @return string
     */
    protected function getMyLocale(Request $request)
    {
        if ($this->getUser()) {
            return $this->getUser()->getLocale();
        }

        $locale = $request->getSession()->get('_locale');

        return $locale ?: $request->getDefaultLocale();
    }
}
