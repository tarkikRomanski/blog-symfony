<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class SessionListener
{
    /**
     * @param GetResponseEvent $getResponseEvent
     */
    public function onKernelRequest(GetResponseEvent $getResponseEvent)
    {
        $request = $getResponseEvent->getRequest();
        if (!$request->getSession()->has('check_session')) {
            $clientIp = $request->getClientIp();
            $userAgent = $request->headers->get('User-Agent');
            $request->getSession()->set('check_session', true);
        }
    }
}