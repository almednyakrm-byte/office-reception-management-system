<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\حجزمواعيدController;
use App\Repository\حجزمواعيدRepository;
use App\Entity\حجزمواعيد;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Testحجزمواعيد extends TestCase
{
    private $controller;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(حجزمواعيدRepository::class);
        $this->controller = new حجزمواعيدController($this->repository);
    }

    public function testGetAll(): void
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->getAll();
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    public function testGetOne(): void
    {
        $id = 1;
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->getOne($id);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    public function testCreate(): void
    {
        $data = ['name' => 'test'];
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($expectedResponse['data']);

        $request = new Request([], [], [], [], [], json_encode($data));
        $response = $this->controller->create($request);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    public function testUpdate(): void
    {
        $id = 1;
        $data = ['name' => 'test'];
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn($expectedResponse['data']);

        $request = new Request([], [], [], [], [], json_encode($data));
        $response = $this->controller->update($id, $request);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    public function testDelete(): void
    {
        $id = 1;
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->delete($id);
        $this->assertEquals($expectedResponse, $response->getContent());
    }
}



// حجزمواعيدController.php
namespace App\Controller;

use App\Repository\حجزمواعيدRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class حجزمواعيدController
{
    private $repository;

    public function __construct(حجزمواعيدRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): Response
    {
        return new Response(json_encode($this->repository->findAll()));
    }

    public function getOne($id): Response
    {
        return new Response(json_encode($this->repository->find($id)));
    }

    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        return new Response(json_encode($this->repository->create($data)));
    }

    public function update($id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        return new Response(json_encode($this->repository->update($id, $data)));
    }

    public function delete($id): Response
    {
        return new Response(json_encode($this->repository->delete($id)));
    }
}



// حجزمواعيدRepository.php
namespace App\Repository;

use App\Entity\حجزمواعيد;

class حجزمواعيدRepository
{
    public function findAll(): array
    {
        // implement logic to fetch all records from database
    }

    public function find($id): ?حجزمواعيد
    {
        // implement logic to fetch a record by id from database
    }

    public function create(array $data): حجزمواعيد
    {
        // implement logic to create a new record in database
    }

    public function update($id, array $data): حجزمواعيد
    {
        // implement logic to update a record in database
    }

    public function delete($id): void
    {
        // implement logic to delete a record from database
    }
}