<?php

namespace App\Tests\Controller;

use App\Controller\خدمات الدفعController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Testخدمات_الدفع extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->controller = new خدمات الدفعController($this->pdoMock);
    }

    public function testGetServices(): void
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM services')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $response = $this->controller->getServices($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPostService(): void
    {
        $data = [
            'name' => 'Service 1',
            'description' => 'This is a service',
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO services (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['data' => $data]);
        $response = $this->controller->postService($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testPutService(): void
    {
        $data = [
            'id' => 1,
            'name' => 'Service 1',
            'description' => 'This is a service',
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE services SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['data' => $data]);
        $response = $this->controller->putService($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteService(): void
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM services WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], ['id' => 1]);
        $response = $this->controller->deleteService($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}


This test file covers the CRUD operations for the 'خدمات الدفع' module using mocked PDO statements. It includes tests for GET, POST, PUT, and DELETE requests.