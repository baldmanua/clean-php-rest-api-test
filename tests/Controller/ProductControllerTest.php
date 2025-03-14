<?php

namespace Tests\Controller;

use App\Controller\ProductController;
use App\Dto\ProductDTO;
use App\Repository\ProductRepository;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class ProductControllerTest extends TestCase
{
    private ProductController $controller;
    private ProductRepository $repository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->repository = $this->createMock(ProductRepository::class);
        $this->controller = new ProductController($this->repository);
    }

    public function testCreate(): void
    {
        $data = ['name' => 'Test Product', 'price' => 100.00, 'category' => 'Category', 'attributes' => ['color' => 'red']];
        $dto = new ProductDTO(...$data);

        $this->repository->expects($this->once())
            ->method('create')
            ->with($dto)
            ->willReturn(['id' => '550e8400-e29b-41d4-a716-446655440000'] + $data);

        $controllerMock = $this->getMockBuilder(ProductController::class)
            ->setConstructorArgs([$this->repository])
            ->onlyMethods(['getRequestData'])
            ->getMock();

        $controllerMock->method('getRequestData')
            ->willReturn($data);

        $response = $controllerMock->create();

        $this->assertEquals(201, $response->statusCode);

        $expectedJson = json_encode(['id' => '550e8400-e29b-41d4-a716-446655440000'] + $data);
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($response->data));
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function testShow(): void
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with('550e8400-e29b-41d4-a716-446655440000')
            ->willReturn(['id' => '550e8400-e29b-41d4-a716-446655440000', 'name' => 'Test Product']);


        $response = $this->controller->show('550e8400-e29b-41d4-a716-446655440000');


        $this->assertEquals(200, $response->statusCode);


        $expectedJson = json_encode(['id' => '550e8400-e29b-41d4-a716-446655440000', 'name' => 'Test Product']);
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($response->data));
    }

    public function testUpdate(): void
    {
        $data = ['name' => 'Updated Product', 'price' => 200.00, 'category' => 'New Category', 'attributes' => ['size' => 'M']];
        $dto = new ProductDTO(...$data);

        $this->repository->expects($this->once())
            ->method('update')
            ->with('550e8400-e29b-41d4-a716-446655440000', $dto)
            ->willReturn(['id' => '550e8400-e29b-41d4-a716-446655440000'] + $data);

        $controllerMock = $this->getMockBuilder(ProductController::class)
            ->setConstructorArgs([$this->repository])
            ->onlyMethods(['getRequestData'])
            ->getMock();

        $controllerMock->method('getRequestData')
            ->willReturn($data);

        $response = $controllerMock->update('550e8400-e29b-41d4-a716-446655440000');

        $this->assertEquals(200, $response->statusCode);

        $expectedJson = json_encode(['id' => '550e8400-e29b-41d4-a716-446655440000'] + $data);
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($response->data));
    }

    public function testDelete(): void
    {
        $this->repository->expects($this->once())
            ->method('delete')
            ->with('550e8400-e29b-41d4-a716-446655440000')
            ->willReturn(true);

        $response = $this->controller->delete('550e8400-e29b-41d4-a716-446655440000');

        $this->assertEquals(200, $response->statusCode);

        $this->assertStringContainsString('success', json_encode($response->data));
    }

    public function testList(): void
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => '550e8400-e29b-41d4-a716-446655440000', 'name' => 'Test Product', 'price' => 100.00, 'category' => 'Category']
            ]);

        $response = $this->controller->list();

        $this->assertEquals(200, $response->statusCode);

        $expectedJson = json_encode([['id' => '550e8400-e29b-41d4-a716-446655440000', 'name' => 'Test Product', 'price' => 100.00, 'category' => 'Category']]);
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($response->data));
    }
}