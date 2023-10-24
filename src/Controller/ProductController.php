<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Attribute\Cache;

class ProductController extends AbstractController
{

    /**
     * Récupérer la liste des produits
     * 
     * @OA\Response(
     *      response=200,
     *      description="Retourne la listes des produits",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(
     *              @OA\Property(property="id", type="integer"),
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(
     *                  property="_links",
     *                  type="object",
     *                  @OA\Property(
     *                      property="self",
     *                      type="object",
     *                      @OA\Property(property="href", type="string")
     *                  )
     *              )
     *          )
     *      )
     * )
     * 
     * @OA\Response(
     *     response=204,
     *     description="Pagination trop élévé, pas de produit retourné.",
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
     * @OA\Tag(name="Products")
     *
     * @param Request $request
     * @param ProductRepository $productRepository
     * @return JsonResponse
     */
    #[Route('/api/products', name: 'getProducts', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: "Vous n'avez pas les droits suffisants pour voir les produits !")]
    #[Cache(smaxage: "60")]
    public function getProducts(Request $request, ProductRepository $productRepository): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 3);

        $products = $productRepository->findAllWithPagination($page, $limit);

        if (empty($products)) {
            return $this->json(
                null,
                Response::HTTP_NO_CONTENT
            );
        }

        $response = $this->json(
            $products,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json'],
            ['groups' => 'getProducts', 'json_encode_options' => JSON_UNESCAPED_SLASHES]
        );

        $response->setEtag(md5($response->getContent()));
        $response->setPublic();

        return $response;
    }


    /**
     * Récupérer les détails d'un produit
     *
     * @OA\Response(
     *      response=200,
     *      description="Retourne les données d'un produit",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="id", type="integer"),
     *          @OA\Property(property="name", type="string"),
     *          @OA\Property(property="description", type="string"),
     *          @OA\Property(property="price", type="number", format="float"),
     *          @OA\Property(property="createdAt", type="string", format="date-time"),
     *          @OA\Property(property="updatedAt", type="string", format="date-time", nullable=true),
     *          @OA\Property(
     *              property="_links",
     *              type="object",
     *              @OA\Property(
     *                  property="self",
     *                  type="object",
     *                  @OA\Property(property="href", type="string")
     *              )
     *          )
     *      )
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
     *     description="Erreur le produit n'existe pas",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="status", type="interger", example="404"),
     *        @OA\Property(property="message", type="string", example="App\Entity\Product object not found by the ParamConverter annotation.")
     *     )
     * )
     * 
     * 
     * @OA\Tag(name="Products")
     * 
     * @param Product $product
     * @return JsonResponse
     */
    #[Route('/api/products/{id}', name: 'getProduct', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: "Vous n'avez pas les droits suffisants pour voir le produit !")]
    #[Cache(smaxage: "60")]
    public function getProductDetails(Product $product): JsonResponse
    {
        $response = $this->json(
            $product,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json'],
            ['groups' => 'getProductDetails', 'json_encode_options' => JSON_UNESCAPED_SLASHES]
        );

        $response->setEtag(md5($response->getContent()));
        $response->setPublic();
        $response->setLastModified($product->getUpdatedAt());

        return $response;
    }
}
