<?php

namespace Mary\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class TranslatorController extends BaseController
{
    public function indexAction()
    {
        echo $this->trans('Hello World');
        exit;
    }

    public function useDomainAction()
    {
        echo $this->trans('Home Page', [], 'navigation');
        exit;
    }

    public function passParamAction(Request $request, $name)
    {
        echo $this->trans('Hello %name%', [
            '%name%' => $name
        ]);
        exit;
    }

    public function forceLocaleAction(Request $request, $locale)
    {
        echo $this->trans('Hello World', [], 'messages', $locale);
        exit;
    }
}