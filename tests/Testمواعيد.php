<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\MoaaedController;
use App\Repository\MoaaedRepository;
use App\Entity\Moaaed;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class TestMoaaed extends TestCase
{
    private $moaaedController;
    private $moaaedRepository;
    private $router;
    private $pdo;

    protected function setUp(): void
    {
        $this->moaaedRepository = $this->createMock(MoaaedRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->pdo = $this->createMock(\PDO::class);

        $this->moaaedController = new MoaaedController($this->moaaedRepository, $this->router, $this->pdo);
    }

    public function testGetMoaaeds()
    {
        $moaaeds = [new Moaaed(), new Moaaed()];
        $this->moaaedRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($moaaeds);

        $response = $this->moaaedController->getMoaaeds();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($moaaeds), $response->getContent());
    }

    public function testGetMoaaed()
    {
        $moaaed = new Moaaed();
        $this->moaaedRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($moaaed);

        $response = $this->moaaedController->getMoaaed(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($moaaed), $response->getContent());
    }

    public function testGetMoaaedNotFound()
    {
        $this->moaaedRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->moaaedController->getMoaaed(1);
    }

    public function testCreateMoaaed()
    {
        $moaaed = new Moaaed();
        $moaaed->setId(1);
        $this->moaaedRepository->expects($this->once())
            ->method('save')
            ->with($moaaed);

        $request = new Request();
        $request->request->set('name', 'test');
        $response = $this->moaaedController->createMoaaed($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateMoaaed()
    {
        $moaaed = new Moaaed();
        $moaaed->setId(1);
        $this->moaaedRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($moaaed);
        $this->moaaedRepository->expects($this->once())
            ->method('save')
            ->with($moaaed);

        $request = new Request();
        $request->request->set('name', 'test');
        $response = $this->moaaedController->updateMoaaed(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdateMoaaedNotFound()
    {
        $this->moaaedRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->moaaedController->updateMoaaed(1, new Request());
    }

    public function testDeleteMoaaed()
    {
        $moaaed = new Moaaed();
        $moaaed->setId(1);
        $this->moaaedRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($moaaed);
        $this->moaaedRepository->expects($this->once())
            ->method('remove')
            ->with($moaaed);

        $response = $this->moaaedController->deleteMoaaed(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteMoaaedNotFound()
    {
        $this->moaaedRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->moaaedController->deleteMoaaed(1);
    }
}