<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpKernel\Attribute\Cache;


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
     * @OA\Response(
     *     response=204,
     *     description="Pagination trop élévé, pas de clients retourné.",
     * )
     * 
     * @OA\Response(
     *     response=401,
     *     description="Le Token JWT n'est pas valide",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="code", type="interger", example="401"),
     *        @OA\Property(property="message", type="string", example="Invalid JWT Token")
     *     )
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
     * @return JsonResponse
     */
    #[Route('/api/users/{id}/clients', name: 'getClients', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: "Vous n'avez pas les droits suffisants pour voir les clients !")]
    #[Cache(smaxage: "60")]
    public function getClients(User $user, Request $request, ClientRepository $clientRepository): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 3);

        $clients = $clientRepository->findAllWithPagination($page, $limit, $user);

        if (empty($clients)) {
            return $this->json(
                null,
                Response::HTTP_NO_CONTENT
            );
        }

        $response = $this->json(
            $clients,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json'],
            ['groups' => 'getClients']
        );

        $response->setEtag(md5($response->getContent()));
        $response->setPublic();

        return $response;
    }


    /**
     * Récupérer les détails d'un client
     *
     * @OA\Response(
     *      response=200,
     *      description="Retourne les données d'un client",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=Client::class, groups={"getClientDetails"}))
     *      )
     * )
     * 
     * @OA\Response(
     *     response=404,
     *     description="Erreur le client n'existe pas",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="status", type="interger", example="404"),
     *        @OA\Property(property="message", type="string", example="App\\Entity\\Client object not found by the @ParamConverter annotation.")
     *     )
     * )
     * 
     * @OA\Response(
     *     response=401,
     *     description="Le Token JWT n'est pas valide",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="code", type="interger", example="401"),
     *        @OA\Property(property="message", type="string", example="Invalid JWT Token")
     *     )
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
     * @return JsonResponse
     */
    #[Route('/api/clients/{id}', name: 'getClientDetails', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: "Vous n'avez pas les droits suffisants pour voir le client !")]
    #[Cache(smaxage: "60")]
    public function getClientDetails(Client $client): JsonResponse
    {
        $response = $this->json(
            $client,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json'],
            ['groups' => 'getClientDetails']
        );

        $response->setEtag(md5($response->getContent()));
        $response->setPublic();
        $response->setLastModified($client->getUpdatedAt());

        return $response;
    }


    /**
     * Supprimer un client
     *
     * @OA\Response(
     *     response=204,
     *     description="Supprime les données d'un client",
     * )
     * 
     * @OA\Response(
     *     response=403,
     *     description="Erreur le client n'appartient pas à l'utilisateur",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="status", type="interger", example="403"),
     *        @OA\Property(property="error", type="string", example="Forbidden"),
     *        @OA\Property(property="message", type="string", example="Le client n'appartient pas à l'utilisateur.")
     *     )
     * )
     * 
     * @OA\Response(
     *     response=401,
     *     description="Le Token JWT n'est pas valide",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="code", type="interger", example="401"),
     *        @OA\Property(property="message", type="string", example="Invalid JWT Token")
     *     )
     * )
     * 
     * @OA\Response(
     *     response=404,
     *     description="Erreur le client n'existe pas",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="status", type="interger", example="404"),
     *        @OA\Property(property="message", type="string", example="App\\Entity\\Client object not found by the @ParamConverter annotation.")
     *     )
     * )
     * 
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     description="L'id du client que l'on veut supprimer",
     *     @OA\Schema(type="int")
     * )
     *
     * @OA\Tag(name="Clients")
     * 
     * @param Client $client
     * @return JsonResponse
     */
    #[Route('/api/clients/{id}', name: 'deleteClient', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER', message: "Vous n'avez pas les droits suffisants pour supprimer le client !")]
    public function deleteClient(Client $client, ClientRepository $clientRepository): JsonResponse
    {
        if ($this->getUser() === $client->getUser()) {
            $clientRepository->remove($client, true);

            $response = $this->json(
                null,
                Response::HTTP_NO_CONTENT
            );
        } else {
            $response = $this->json(
                [
                    "status" => Response::HTTP_FORBIDDEN,
                    "error" => "Forbidden",
                    "message" => "Le client n'appartient pas à l'utilisateur."
                ],
                Response::HTTP_FORBIDDEN
            );
        }

        return $response;
    }


    /**
     * Ajouter un nouveau client lié à un utilisateur
     * 
     * @OA\RequestBody(
     *      description="Client data to be added",
     *      required=true,
     *      @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="firstname", type="string", example="John"),
     *        @OA\Property(property="lastname", type="string", example="Doe"),
     *        @OA\Property(property="email", type="string", example="john.doe@email.com"),
     *        @OA\Property(property="phone", type="string", example="+33086786303")
     *     )
     * )
     * 
     * @OA\Response(
     *     response=201,
     *     description="Ajouter les données d'un client",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="message", type="string", example="Nouveau client ajouté.")
     *     )
     * )
     * 
     * @OA\Response(
     *     response=401,
     *     description="Le Token JWT n'est pas valide",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="code", type="interger", example="401"),
     *        @OA\Property(property="message", type="string", example="Invalid JWT Token")
     *     )
     * )
     * 
     * @OA\Response(
     *     response=422,
     *     description="Les erreurs de validation de l'entité",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="message", type="string", example="This value should not be blank")
     *     )
     * )
     * 
     * @OA\Tag(name="Clients")
     * 
     * @param Request $request
     * @param ClientRepository $clientRepository
     * @param UrlGeneratorInterface $urlGenerator
     * @return JsonResponse
     */
    #[Route('/api/clients', name: 'addClient', methods: ['POST'])]
    #[IsGranted('ROLE_USER', message: "Vous n'avez pas les droits suffisants pour ajouter le client !")]
    public function addClient(Request $request, ClientRepository $clientRepository, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $client = new Client();

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $client->setUser($this->getUser());
                $clientRepository->save($client, true);

                $location = $urlGenerator->generate('getClientDetails', ['id' => $client->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
                $response = $this->json(
                    ["message" => "Nouveau client ajouté."],
                    Response::HTTP_CREATED,
                    ["Location" => $location]
                );

                return $response;

            } else {

                $formErrors = $form->getErrors(true, true);

                $errors = [];
                foreach ($formErrors as $error) {
                    $errors[] = $error->getMessage();
                }

                return $this->json(
                    ["errors" => $errors],
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    ['Content-Type' => 'application/json']
                );

            }
        }
    }
}
