<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

trait JsonSerializerTrait {
    private function serializeData($data) {
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getUuid();
        });
        $serializer = new Serializer(array($normalizer), array($encoder));
        return new Response($serializer->serialize($data, 'json'), 200, ['Content-Type' => 'application/json']);
    }
}