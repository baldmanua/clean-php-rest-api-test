<?php

namespace App\Controller;

use App\Dto\ProductDTO;
use App\Repository\ProductRepository;
use App\Response\Response;
use Doctrine\DBAL\Exception;
use Symfony\Component\Validator\Validation;

class ProductController extends BaseController
{

    public function __construct(
        private ProductRepository $repository
    )
    {
    }

    public function create(): Response
    {
        $data = $this->getRequestData();

        $validator = Validation::createValidator();
        $dto = new ProductDTO(...$data);
        $errors = $validator->validate($dto);

        if (count($errors) > 0) {
            return $this->response(['errors' => (string)$errors], 400);
        }

        $product = $this->repository->create($dto);

        return $this->response($product, 201);
    }

    /**
     * @throws Exception
     */
    public function show(string $id): Response
    {
        $product = $this->repository->find($id);
        if (!$product) {
            return $this->response(['error' => 'Product not found'], 404);
        }

        return $this->response($product);
    }

    public function update(string $id): Response
    {
        $data = $this->getRequestData();

        $validator = Validation::createValidator();
        $dto = new ProductDTO(...$data);
        $errors = $validator->validate($dto);

        if (count($errors) > 0) {
            return $this->response(['errors' => (string)$errors], 400);
        }

        $updated = $this->repository->update($id, $dto);

        if (!$updated) {
            return $this->response(['error' => 'Failed to update product'], 400);
        }

        return $this->response($updated);
    }

    public function delete(string $id): Response
    {
        $deleted = $this->repository->delete($id);

        if (!$deleted) {
            return $this->response(['error' => 'Product not found'], 404);
        }

        return $this->response(['success' => true]);
    }

    public function list()
    {
        $filters = $_GET;
        $products = $this->repository->findAll($filters);

        return $this->response($products);
    }
}