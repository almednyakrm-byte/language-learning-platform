<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\طلابController;
use App\Repository\طلابRepository;
use App\Entity\طلاب;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testطلاب extends TestCase
{
    private $controller;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(طلابRepository::class);
        $this->controller = new طلابController($this->repository);
    }

    public function testGetAll()
    {
        $expectedResponse = ['طلاب' => ['id' => 1, 'name' => 'Student 1']];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse);

        $response = $this->controller->getAll();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetOne()
    {
        $expectedResponse = ['id' => 1, 'name' => 'Student 1'];
        $this->repository->expects($this->once())
            ->method('findOneById')
            ->with(1)
            ->willReturn($expectedResponse);

        $response = $this->controller->getOne(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetOneNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('findOneById')
            ->with(1)
            ->willReturn(null);

        $this->controller->getOne(1);
    }

    public function testCreate()
    {
        $student = new طلاب();
        $student->setId(1);
        $student->setName('Student 1');

        $expectedResponse = ['id' => 1, 'name' => 'Student 1'];
        $this->repository->expects($this->once())
            ->method('create')
            ->with($student)
            ->willReturn($expectedResponse);

        $request = new Request();
        $request->request->set('id', 1);
        $request->request->set('name', 'Student 1');

        $response = $this->controller->create($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testUpdate()
    {
        $student = new طلاب();
        $student->setId(1);
        $student->setName('Student 1');

        $expectedResponse = ['id' => 1, 'name' => 'Student 1'];
        $this->repository->expects($this->once())
            ->method('update')
            ->with($student)
            ->willReturn($expectedResponse);

        $request = new Request();
        $request->request->set('id', 1);
        $request->request->set('name', 'Student 1');

        $response = $this->controller->update(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testUpdateNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('findOneById')
            ->with(1)
            ->willReturn(null);

        $request = new Request();
        $request->request->set('id', 1);
        $request->request->set('name', 'Student 1');

        $this->controller->update(1, $request);
    }

    public function testDelete()
    {
        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1);

        $response = $this->controller->delete(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

1.  `testGetAll`: Verifies that the `getAll` method returns a list of students.
2.  `testGetOne`: Verifies that the `getOne` method returns a single student by ID.
3.  `testGetOneNotFound`: Verifies that a `NotFoundHttpException` is thrown when trying to retrieve a non-existent student.
4.  `testCreate`: Verifies that the `create` method creates a new student and returns it.
5.  `testUpdate`: Verifies that the `update` method updates an existing student and returns it.
6.  `testUpdateNotFound`: Verifies that a `NotFoundHttpException` is thrown when trying to update a non-existent student.
7.  `testDelete`: Verifies that the `delete` method deletes a student by ID.

Note that this test file assumes that the `طلابController` and `طلابRepository` classes are properly implemented and that the `طلاب` entity is defined.