<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;

class ClientController extends AbstractController
{

    /**
     * Récupérer la liste des clients associés a un utilisateur
     * 
     * @OA\Response(
     *      response=200,
     *      description="Retourne la listes des clients associés à un utilisateur",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=Client::class, groups={"getClients"}))
     *      )
     * )
     * 
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="La page que l'on veut récupérer",
     *     @OA\Schema(type="int")
     * )
     *
     * @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     description="Le nombre d'éléments que l'on veut récupérer",
     *     @OA\Schema(type="int")
     * )
     * 
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     description="L'id de l'utilisateur associés aux clients",
     *     @OA\Schema(type="int")
     * )
     * 
     * @OA\Tag(name="Clients")
     *
     * @param User $user
     * @param Request $request
     * @param ClientRepository $clientRepository
     * @param SerializerInterface $serializer
     * @param TagAwareCacheInterface $cache
     * @return JsonResponse
     */
    #[Route('/api/users/{id}/clients', name: 'getClients', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: "Vous n'avez pas les droits suffisants pour voir les clients !")]
    public function getClients(User $user, Request $request, ClientRepository $clientRepository, SerializerInterface $serializer, TagAwareCacheInterface $cache): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 3);

        $idCache = "getClients-" . $page . "-" . $limit . "-" . $user->getId();

        $jsonClients = $cache->get($idCache, function (ItemInterface $item) use ($clientRepository, $page, $limit, $serializer, $user) {
            $item->tag("clientsCache");
            $item->expiresAfter(60);
            $clients = $clientRepository->findAllWithPagination($page, $limit, $user);
            return $serializer->serialize($clients, 'json', ['groups' => 'getClients']);
        });

        return new JsonResponse($jsonClients, Response::HTTP_OK, [], true);
    }

    
    /**
     * Récupérer les détails d'un client
     *
     * @OA\Response(
     *      response=200,
     *      description="Retourne les données d'un client",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=Client::class, groups={"getClients"}))
     *      )
     * )
     * 
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     description="L'id du client qu'on souhaite récupérer",
     *     @OA\Schema(type="int")
     * )
     * 
     * @OA\Tag(name="Clients")
     * 
     * @param Client $client
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/api/clients/{id}', name: 'getClient', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: "Vous n'avez pas les droits suffisants pour voir le client !")]
    public function getClientDetails(Client $client ,SerializerInterface $serializer): JsonResponse
    {
        $jsonClient = $serializer->serialize($client, 'json', ['groups' => 'getClients']);
        return new JsonResponse($jsonClient, Response::HTTP_OK, [], true);
    }
}
