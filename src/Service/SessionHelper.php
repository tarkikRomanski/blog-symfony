<?php

namespace App\Service;


use App\Entity\Session;
use Psr\Container\ContainerInterface;

class SessionHelper
{
    private $doctrine;

    /**
     * GetHelper constructor.
     * @param ContainerInterface $container
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        if (!$container->has('doctrine')) {
            throw new \LogicException('The DoctrineBundle is not registered in your application. Try running "composer require symfony/orm-pack".');
        }

        $this->doctrine = $container->get('doctrine');
    }


    /**
     * Creates new record in sessions table
     *
     * @param array $data
     * @return Session
     */
    public function createHttpSessionRecord(array $data): Session
    {
        $entityManager = $this->doctrine->getManager();
        $session = new Session();

        $session->setIpAddress($data['ip']);
        $session->setUserAgent($data['user_agent']);

        $entityManager->persist($session);
        $entityManager->flush();

        return $session;
    }

    /**
     * Returns quantity of browsers
     *
     * @return array
     */
    public function getBrowsersQuantity()
    {
        $sessionRepository = $this->getDoctrine()
            ->getRepository(Session::class);

        return [
            'chrome' => count($sessionRepository->getChromeUsers()),
            'firefox' => count($sessionRepository->getFirefoxUsers()),
            'seamonkey' => count($sessionRepository->getSeamonkeyUsers()),
            'chromium' => count($sessionRepository->getChromiumUsers()),
            'safari' => count($sessionRepository->getSafariUsers()),
            'opera' => count($sessionRepository->getOperaUsers()),
            'ie' => count($sessionRepository->getIeUsers()),
        ];
    }
}