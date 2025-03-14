<?php

namespace App\Controller;

use App\Dto\ProductDTO;
use App\Repository\ProductRepository;
use Doctrine\DBAL\Exception;
use Symfony\Component\Validator\Validation;

class ProductController extends BaseController
{

    public function __construct(
        private ProductRepository $repository
    )
    {
    }

    public function create(): void
    {
        $data = $this->getRequestData();

        $validator = Validation::createValidator();
        $dto = new ProductDTO(...$data);
        $errors = $validator->validate($dto);

        if (count($errors) > 0) {
            $this->jsonResponse(['errors' => (string)$errors], 400);
        }

        $product = $this->repository->create($dto);

        $this->jsonResponse(['product' => $product], 201);
    }

    /**
     * @throws Exception
     */
    public function show(string $id)
    {
        $product = $this->repository->find($id);
        if (!$product) {
            $this->jsonResponse(['error' => 'Product not found'], 404);
        }

        $this->jsonResponse(['product' => $product]);
    }

    public function update(string $id)
    {
        $data = $this->getRequestData();

        $validator = Validation::createValidator();
        $dto = new ProductDTO(...$data);
        $errors = $validator->validate($dto);

        if (count($errors) > 0) {
            $this->jsonResponse(['errors' => (string)$errors], 400);
        }

        $updated = $this->repository->update($id, $dto);

        if (!$updated) {
            $this->jsonResponse(['error' => 'Failed to update product'], 400);
        }

        $this->jsonResponse(['product' => $updated]);
    }

    public function delete(string $id)
    {
        $deleted = $this->repository->delete($id);

        if (!$deleted) {
            $this->jsonResponse(['error' => 'Product not found'], 404);
        }

        $this->jsonResponse(['success' => true]);
    }

    public function list()
    {
        $filters = $_GET;
        $products = $this->repository->findAll($filters);

        $this->jsonResponse($products);
    }
}