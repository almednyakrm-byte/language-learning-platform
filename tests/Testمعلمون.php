<?php

namespace App\Tests\Controller;

use App\Controller\معلمونController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Testمعلمون extends TestCase
{
    private $controller;
    private $router;
    private $tokenStorage;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->controller = new معلمونController($this->pdo, $this->router, $this->tokenStorage);
    }

    public function testGetAll()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM معلمون')
            ->willReturn($this->createMock('PDOStatement'));

        $request = new Request();
        $request->setMethod('GET');

        $response = $this->controller->getAll($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreate()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO معلمون (name, email) VALUES (:name, :email)')
            ->willReturn($this->createMock('PDOStatement'));

        $request = new Request();
        $request->setMethod('POST');
        $request->request->set('name', 'John Doe');
        $request->request->set('email', 'john@example.com');

        $response = $this->controller->create($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE معلمون SET name = :name, email = :email WHERE id = :id')
            ->willReturn($this->createMock('PDOStatement'));

        $request = new Request();
        $request->setMethod('PUT');
        $request->request->set('id', 1);
        $request->request->set('name', 'John Doe');
        $request->request->set('email', 'john@example.com');

        $response = $this->controller->update($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM معلمون WHERE id = :id')
            ->willReturn($this->createMock('PDOStatement'));

        $request = new Request();
        $request->setMethod('DELETE');
        $request->request->set('id', 1);

        $response = $this->controller->delete($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the CRUD operations for the 'معلمون' module. It uses mocked PDO statements to simulate database interactions. The tests verify that the controller returns the correct HTTP status codes for each operation.