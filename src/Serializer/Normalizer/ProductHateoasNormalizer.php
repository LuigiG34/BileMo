<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Product;

class ProductHateoasNormalizer implements NormalizerInterface
{
    private $objectNormalizer;
    private $urlGenerator;

    public function __construct(ObjectNormalizer $objectNormalizer, UrlGeneratorInterface $urlGenerator)
    {
        $this->objectNormalizer = $objectNormalizer;
        $this->urlGenerator = $urlGenerator;
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = $this->objectNormalizer->normalize($object, $format, $context);

        $selfLink = $this->urlGenerator->generate('getProduct', ['id' => $object->getId()]);

        $data['_links'] = [
            'self' => [
                'href' => $selfLink,
            ],
        ];

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Product;
    }
}
