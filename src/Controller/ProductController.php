<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ProductService;
use App\Service\ProductServiceValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product", methods={"POST"})
     */
    public function upload(
        Request $request,
        ProductService $productService,
        LoggerInterface $logger
    ): JsonResponse {
        try {
            $productService->validate($request);
            $data = $productService->create($request)->toArray();
            $status = JsonResponse::HTTP_OK;
        } catch (ProductServiceValidationException $e) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = [
                'title' => $e->getMessage(),
                'status' => $status,
                'detail' => $e->getMessage(),
            ];
        } catch (\Throwable $e) {
            //TODO also send to sentry
            $logger->critical($e->getMessage());

            $status = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
            $data = [
                'title' => 'Internal Server Error',
                'status' => $status,
                'detail' => 'dev' === $_SERVER['APP_ENV'] ? $e->getMessage() : 'Internal Server Error',
            ];
        }

        $response = new JsonResponse($data, $status);
        $response->setEncodingOptions(\JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES);

        return $response;
    }
}
