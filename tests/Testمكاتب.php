<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpKernel\Client;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Repository\MkatbRepository;
use App\Entity\Mkatb;
use App\Service\MkatbService;

class TestMkatb extends TestCase
{
    private $client;
    private $router;
    private $tokenStorage;
    private $mkatbRepository;
    private $mkatbService;

    protected function setUp(): void
    {
        $this->client = new Client();
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->mkatbRepository = $this->createMock(MkatbRepository::class);
        $this->mkatbService = $this->createMock(MkatbService::class);

        $this->mkatbRepository->method('findAll')->willReturn([
            new Mkatb('id1', 'name1', 'address1'),
            new Mkatb('id2', 'name2', 'address2'),
        ]);

        $this->mkatbRepository->method('find')->willReturn(new Mkatb('id1', 'name1', 'address1'));

        $this->mkatbRepository->method('save')->willReturn(new Mkatb('id1', 'name1', 'address1'));

        $this->mkatbRepository->method('remove')->willReturn(null);

        $this->mkatbService->method('create')->willReturn(new Mkatb('id1', 'name1', 'address1'));

        $this->mkatbService->method('update')->willReturn(new Mkatb('id1', 'name1', 'address1'));

        $this->mkatbService->method('delete')->willReturn(null);
    }

    public function testGetAllMkatb()
    {
        $this->mkatbRepository->method('findAll')->willReturn([
            new Mkatb('id1', 'name1', 'address1'),
            new Mkatb('id2', 'name2', 'address2'),
        ]);

        $this->client->request('GET', '/api/mkatb');

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testGetMkatbById()
    {
        $this->mkatbRepository->method('find')->willReturn(new Mkatb('id1', 'name1', 'address1'));

        $this->client->request('GET', '/api/mkatb/1');

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testCreateMkatb()
    {
        $this->mkatbService->method('create')->willReturn(new Mkatb('id1', 'name1', 'address1'));

        $this->client->request('POST', '/api/mkatb', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode(['name' => 'name1', 'address' => 'address1']));

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testUpdateMkatb()
    {
        $this->mkatbRepository->method('find')->willReturn(new Mkatb('id1', 'name1', 'address1'));

        $this->mkatbService->method('update')->willReturn(new Mkatb('id1', 'name1', 'address1'));

        $this->client->request('PUT', '/api/mkatb/1', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode(['name' => 'name1', 'address' => 'address1']));

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testDeleteMkatb()
    {
        $this->mkatbRepository->method('find')->willReturn(new Mkatb('id1', 'name1', 'address1'));

        $this->mkatbService->method('delete')->willReturn(null);

        $this->client->request('DELETE', '/api/mkatb/1');

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }
}