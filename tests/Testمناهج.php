<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ManahijController;
use App\Repository\ManahijRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class TestManahij extends TestCase
{
    private $controller;
    private $repository;
    private $router;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ManahijRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->controller = new ManahijController($this->repository, $this->router);
    }

    public function testGetManahij()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Manahij 1'],
                ['id' => 2, 'name' => 'Manahij 2'],
            ]);

        $request = new Request();
        $response = $this->controller->getManahij($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPostManahij()
    {
        $this->repository->expects($this->once())
            ->method('save')
            ->with(['id' => 3, 'name' => 'Manahij 3']);

        $request = new Request([], [], ['name' => 'Manahij 3']);
        $response = $this->controller->postManahij($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPutManahij()
    {
        $this->repository->expects($this->once())
            ->method('update')
            ->with(1, ['name' => 'Manahij 1 Updated']);

        $request = new Request([], [], ['name' => 'Manahij 1 Updated']);
        $response = $this->controller->putManahij(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteManahij()
    {
        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1);

        $request = new Request();
        $response = $this->controller->deleteManahij(1, $request);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// ManahijController.php

namespace App\Controller;

use App\Repository\ManahijRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class ManahijController
{
    private $repository;
    private $router;

    public function __construct(ManahijRepository $repository, RouterInterface $router)
    {
        $this->repository = $repository;
        $this->router = $router;
    }

    public function getManahij(Request $request)
    {
        $manahij = $this->repository->findAll();
        return new Response(json_encode($manahij), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function postManahij(Request $request)
    {
        $manahij = $request->request->all();
        $this->repository->save($manahij);
        return new Response('', Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    public function putManahij($id, Request $request)
    {
        $manahij = $request->request->all();
        $this->repository->update($id, $manahij);
        return new Response('', Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function deleteManahij($id, Request $request)
    {
        $this->repository->delete($id);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}



// ManahijRepository.php

namespace App\Repository;

class ManahijRepository
{
    public function findAll()
    {
        // Mocked data
        return [
            ['id' => 1, 'name' => 'Manahij 1'],
            ['id' => 2, 'name' => 'Manahij 2'],
        ];
    }

    public function save($manahij)
    {
        // Save logic
    }

    public function update($id, $manahij)
    {
        // Update logic
    }

    public function delete($id)
    {
        // Delete logic
    }
}