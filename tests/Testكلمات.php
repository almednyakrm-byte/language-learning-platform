<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\كلماتController;
use App\Repository\كلماتRepository;
use App\Service\كلماتService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testكلمات extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(كلماتRepository::class);
        $this->service = $this->createMock(كلماتService::class);
        $this->controller = new كلماتController($this->repository, $this->service);
    }

    public function testGetAll(): void
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'كلمة 1'],
                ['id' => 2, 'name' => 'كلمة 2'],
            ]);

        $response = $this->controller->getAll();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(['id' => 1, 'name' => 'كلمة 1'], $response->getContent()[0]);
    }

    public function testGetById(): void
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'كلمة 1']);

        $response = $this->controller->getById(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(['id' => 1, 'name' => 'كلمة 1'], $response->getContent());
    }

    public function testGetByIdNotFound(): void
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->getById(1);
    }

    public function testCreate(): void
    {
        $request = new Request([], [], ['name' => 'كلمة جديدة']);
        $this->service->expects($this->once())
            ->method('create')
            ->with(['name' => 'كلمة جديدة'])
            ->willReturn(['id' => 1, 'name' => 'كلمة جديدة']);

        $response = $this->controller->create($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(['id' => 1, 'name' => 'كلمة جديدة'], $response->getContent());
    }

    public function testUpdate(): void
    {
        $request = new Request([], [], ['name' => 'كلمة مُحديثة']);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'كلمة قديمة']);
        $this->service->expects($this->once())
            ->method('update')
            ->with(1, ['name' => 'كلمة مُحديثة'])
            ->willReturn(['id' => 1, 'name' => 'كلمة مُحديثة']);

        $response = $this->controller->update(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(['id' => 1, 'name' => 'كلمة مُحديثة'], $response->getContent());
    }

    public function testUpdateNotFound(): void
    {
        $request = new Request([], [], ['name' => 'كلمة مُحديثة']);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->update(1, $request);
    }

    public function testDelete(): void
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'كلمة قديمة']);
        $this->service->expects($this->once())
            ->method('delete')
            ->with(1);

        $response = $this->controller->delete(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteNotFound(): void
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->delete(1);
    }
}


This test file covers the following scenarios:

1. `testGetAll()`: Verifies that the `getAll()` method returns a list of all كلمات.
2. `testGetById()`: Verifies that the `getById()` method returns a كلمة by its ID.
3. `testGetByIdNotFound()`: Verifies that the `getById()` method throws a `NotFoundHttpException` when the كلمة is not found.
4. `testCreate()`: Verifies that the `create()` method creates a new كلمة.
5. `testUpdate()`: Verifies that the `update()` method updates an existing كلمة.
6. `testUpdateNotFound()`: Verifies that the `update()` method throws a `NotFoundHttpException` when the كلمة is not found.
7. `testDelete()`: Verifies that the `delete()` method deletes a كلمة.
8. `testDeleteNotFound()`: Verifies that the `delete()` method throws a `NotFoundHttpException` when the كلمة is not found.

Note that this test file assumes that the `كلماتController` class has the following methods:

* `getAll()`: Returns a list of all كلمات.
* `getById($id)`: Returns a كلمة by its ID.
* `create(Request $request)`: Creates a new كلمة.
* `update($id, Request $request)`: Updates an existing كلمة.
* `delete($id)`: Deletes a كلمة.

Also, this test file assumes that the `كلماتRepository` class has the following methods:

* `findAll()`: Returns a list of all كلمات.
* `find($id)`: Returns a كلمة by its ID.

Finally, this test file assumes that the `كلماتService` class has the following methods:

* `create($data)`: Creates a new كلمة.
* `update($id, $data)`: Updates an existing كلمة.
* `delete($id)`: Deletes a كلمة.