<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Client;

class ClientHateoasNormalizer implements NormalizerInterface
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

        $selfLink = $this->urlGenerator->generate('getClientDetails', ['id' => $object->getId()]);
        $deleteLink = $this->urlGenerator->generate('deleteClient', ['id' => $object->getId()]);

        $data['_links'] = [
            'self' => [
                'href' => $selfLink,
            ],
            'delete' => [
                'href' => $deleteLink,
            ],
        ];

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Client;
    }
}
