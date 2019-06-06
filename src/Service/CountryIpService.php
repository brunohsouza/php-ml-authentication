<?php
/**
 * Created by PhpStorm.
 * User: brunosouza
 * Date: 02/06/19
 * Time: 17:29
 */

namespace App\Service;

use App\Entity\CountryIp;
use Doctrine\ORM\EntityManager;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Component\HttpClient\HttpClient;

class CountryIpService
{

    private $em;

    private $repository;

    private $httpClient;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $this->repository = $this->em->getRepository(CountryIp::class);
        $this->httpClient = HttpClient::create();
    }

    public function findCountryByIp(string $ip)
    {
        $country = $this->findCountryByIpOnDatabase($ip);
        if ($country === null) {
            return json_decode($this->findIpOnAPI($ip), true);
        }
        return [$country->getLatitude(), $country->getLongitude()];
    }

    public function findIpOnAPI($ip)
    {
        try {
            $response = $this->httpClient->request(
                'GET',
                'https://geo.ipify.org/api/v1',
                [
                    'query' => [
                        'apiKey' => 'at_JZdd8J0aJxEMJ1djHQ9E3HizgnvEv',
                        'ipAddress' => $ip
                    ]
                ]
            );
            return $response->getContent();
        } catch (TransportExceptionInterface $e) {
            return $e->getMessage();
        }
    }

    public function findCountryByIpOnDatabase($ip)
    {
        $ip = str_replace('.', '', $ip);
        return $this->repository->findOneBy(['ipFrom' => $ip]);
    }

}