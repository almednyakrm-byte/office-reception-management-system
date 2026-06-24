<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ServicesController;
use App\Repository\ServicesRepository;
use App\Entity\Services;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;

class Testخدمات extends TestCase
{
    private $servicesController;
    private $servicesRepository;
    private $entityManager;
    private $router;

    protected function setUp(): void
    {
        $this->servicesRepository = $this->createMock(ServicesRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(RouterInterface::class);

        $this->servicesController = new ServicesController(
            $this->servicesRepository,
            $this->entityManager,
            $this->router
        );
    }

    public function testGetServices(): void
    {
        $expectedResponse = new JsonResponse(['services' => ['service1', 'service2']]);

        $this->servicesRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn(['service1', 'service2']);

        $response = $this->servicesController->getServices();

        $this->assertEquals($expectedResponse, $response);
    }

    public function testPostService(): void
    {
        $service = new Services();
        $service->setName('Service 1');
        $service->setDescription('Description 1');

        $expectedResponse = new JsonResponse(['service' => $service]);

        $this->servicesRepository
            ->expects($this->once())
            ->method('save')
            ->with($service)
            ->willReturn($service);

        $request = new Request();
        $request->request->set('name', 'Service 1');
        $request->request->set('description', 'Description 1');

        $response = $this->servicesController->postService($request);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testPutService(): void
    {
        $service = new Services();
        $service->setId(1);
        $service->setName('Service 1');
        $service->setDescription('Description 1');

        $expectedResponse = new JsonResponse(['service' => $service]);

        $this->servicesRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($service);

        $this->servicesRepository
            ->expects($this->once())
            ->method('save')
            ->with($service)
            ->willReturn($service);

        $request = new Request();
        $request->request->set('name', 'Service 1');
        $request->request->set('description', 'Description 1');

        $response = $this->servicesController->putService(1, $request);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteService(): void
    {
        $service = new Services();
        $service->setId(1);

        $this->servicesRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($service);

        $this->servicesRepository
            ->expects($this->once())
            ->method('remove')
            ->with($service);

        $response = $this->servicesController->deleteService(1);

        $this->assertEquals(new Response('', 204), $response);
    }
}