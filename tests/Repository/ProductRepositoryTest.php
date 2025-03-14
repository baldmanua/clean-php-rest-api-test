<?php

namespace Tests\Repository;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class ProductRepositoryTest extends TestCase
{
    private Connection $connection;
    private ProductRepository $repository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->repository = new ProductRepository($this->connection);
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function testFind(): void
    {
        $this->connection->expects($this->once())
            ->method('fetchAssociative')
            ->willReturn([
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'name' => 'Test Product',
                'price' => 100.00,
                'category' => 'Category'
            ]);

        $result = $this->repository->find('550e8400-e29b-41d4-a716-446655440000');

        $this->assertIsArray($result);
        $this->assertEquals('550e8400-e29b-41d4-a716-446655440000', $result['id']);
    }

    public function testDelete(): void
    {
        $this->connection->expects($this->once())
            ->method('delete')
            ->with('products', ['id' => '550e8400-e29b-41d4-a716-446655440000'])
            ->willReturn(1);

        $result = $this->repository->delete('550e8400-e29b-41d4-a716-446655440000');

        $this->assertTrue($result);
    }

    public function testFindAll(): void
    {
        $this->connection->expects($this->once())
            ->method('fetchAllAssociative')
            ->willReturn([
                ['id' => '550e8400-e29b-41d4-a716-446655440000', 'name' => 'Test Product', 'price' => 100.00, 'category' => 'Category']
            ]);

        $result = $this->repository->findAll();

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
    }
}