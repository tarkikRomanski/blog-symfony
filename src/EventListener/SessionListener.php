<?php

namespace App\EventListener;

use App\Service\SessionService;
use App\Service\SetHelper;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class SessionListener
{
    /**
     * @var SessionService
     */
    private $sessionHelper;


    public function __construct(SessionService $sessionHelper)
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