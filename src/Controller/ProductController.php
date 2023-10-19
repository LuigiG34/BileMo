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
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
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
     *          @OA\Items(ref=@Model(type=Product::class, groups={"getProducts"}))
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
    
        $response = $this->json(
            $products,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json'],
            ['groups' => 'getProducts']
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
     *          type="array",
     *          @OA\Items(ref=@Model(type=Product::class, groups={"getProductDetails"}))
     *      )
     * )
     * 
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     description="L'id du produit qu'on souhaite récupérer",
     *     @OA\Schema(type="int")
     * )
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
            ['groups' => 'getProductDetails']
        );

        $response->setEtag(md5($response->getContent()));
        $response->setPublic();
        $response->setLastModified($product->getUpdatedAt());

        return $response;
    }
}
