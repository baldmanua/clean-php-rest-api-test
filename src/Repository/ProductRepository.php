<?php

namespace App\Repository;

use App\Dto\ProductDTO;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class ProductRepository
{
    private Connection $db;
    private string $table = 'products';

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function create(ProductDTO $dto): array
    {
        try {
            $query = "
            INSERT INTO {$this->table} (name, price, category, attributes, created_at)
            VALUES (:name, :price, :category, :attributes, :created_at)
            RETURNING *
        ";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue('name', $dto->name);
            $stmt->bindValue('price', $dto->price);
            $stmt->bindValue('category', $dto->category);
            $stmt->bindValue('attributes', json_encode($dto->attributes));
            $stmt->bindValue('created_at', (new \DateTimeImmutable())->format('Y-m-d H:i:s'));

            $result = $stmt->executeQuery();

            return $result->fetchAssociative();
        } catch (\Throwable $e) {
            throw new \RuntimeException('Error inserting product: ' . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function find(string $id): ?array
    {
        return $this->db->fetchAssociative(
            "SELECT * FROM {$this->table} WHERE id = :id",
            ['id' => $id]
        ) ?: null;
    }

    public function update(string $id, ProductDTO $dto): array
    {
        try {
            $query = "
            UPDATE {$this->table}
            SET name       = :name,
                price      = :price,
                category   = :category,
                attributes = :attributes
            WHERE id = :id
            RETURNING *";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue('id', $id);
            $stmt->bindValue('name', $dto->name);
            $stmt->bindValue('price', $dto->price);
            $stmt->bindValue('category', $dto->category);
            $stmt->bindValue('attributes', json_encode($dto->attributes));

            $result = $stmt->executeQuery();

            return $result->fetchAssociative();
        } catch (\Throwable $e) {
            throw new \RuntimeException('Error updating product: ' . $e->getMessage());
        }
    }

    public function delete(string $id): bool
    {
        try {
            return (bool) $this->db->delete($this->table, ['id' => $id]);
        } catch (Exception $e) {
            throw new \RuntimeException('Error deleting product: ' . $e->getMessage());
        }
    }

    public function findAll(array $filters = []): array
    {
        $query = "SELECT * FROM {$this->table}";
        $params = [];

        if (!empty($filters)) {
            $whereClauses = [];
            foreach ($filters as $key => $value) {
                $whereClauses[] = "$key = :$key";
                $params[$key] = $value;
            }
            $query .= ' WHERE ' . implode(' AND ', $whereClauses);
        }

        return $this->db->fetchAllAssociative($query, $params);
    }
}