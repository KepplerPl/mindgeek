<?php

namespace App\Controller;

use App\Repository\PornStarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SimpleDisplayController extends AbstractController
{
    public function __construct(
        private readonly PornStarRepository $pornStarRepository
    ){}

    #[Route('/simple/display', name: 'app_simple_display')]
    public function index(Request $request): JsonResponse
    {
        $offset = 0;
        $limit = 10;

        if($request->query->has('offset')) {
            $offset = $request->query->get('offset');
        }
        if($request->query->has('limit')) {
            $limit = $request->query->get('limit');
        }

        $result = $this->pornStarRepository->getAllWithOffsetAndLimit($offset, $limit);

        if(empty($result)) {
            return new JsonResponse(['no data']);
        }

        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $response = new JsonResponse();
        $response->setContent($serializer->serialize(
            $result,
            'json',
            [
                AbstractNormalizer::IGNORED_ATTRIBUTES => [
                    'id', 'createdAt',
                    'updatedAt', '__isCloning',
                    'pornStar', 'thumbnails'
                ]
            ]
        ));

        return $response;
    }
}
