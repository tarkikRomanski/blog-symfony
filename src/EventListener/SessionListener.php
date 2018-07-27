<?php

namespace App\EventListener;

use App\Service\SessionHelper;
use App\Service\SetHelper;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class SessionListener
{
    /**
     * @var SessionHelper
     */
    private $sessionHelper;


    public function __construct(SessionHelper $sessionHelper)
    {
        $this->sessionHelper = $sessionHelper;
    }

    /**
     * @param GetResponseEvent $getResponseEvent
     */
    public function onKernelRequest(GetResponseEvent $getResponseEvent)
    {
        $request = $getResponseEvent->getRequest();
        if (!$request->getSession()->has('check_session')) {
            $data = [
                'ip' => $request->getClientIp(),
                'user_agent' => $request->headers->get('User-Agent')
            ];
            $this->sessionHelper->createHttpSessionRecord($data);
            $request->getSession()->set('check_session', true);
        }
    }
}