<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\طلابController;
use App\Repository\طلابRepository;
use App\Entity\طلاب;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class Testطلاب extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->repository = $this->createMock(طلابRepository::class);
        $this->controller = new طلابController($this->repository);
    }

    public function testGetAll()
    {
        $expectedResponse = ['طلاب' => ['طلاب1', 'طلاب2']];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse);
        $response = $this->controller->getAll();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetById()
    {
        $expectedResponse = ['طلاب' => 'طلاب1'];
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($expectedResponse);
        $response = $this->controller->getById(1);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreate()
    {
        $expectedResponse = ['message' => 'طلاب created successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO طلاب (name) VALUES (:name)')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['name' => 'طلاب1']);
        $response = $this->controller->create('طلاب1');
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdate()
    {
        $expectedResponse = ['message' => 'طلاب updated successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE طلاب SET name = :name WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['name' => 'طلاب1', 'id' => 1]);
        $response = $this->controller->update(1, 'طلاب1');
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDelete()
    {
        $expectedResponse = ['message' => 'طلاب deleted successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM طلاب WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['id' => 1]);
        $response = $this->controller->delete(1);
        $this->assertEquals($expectedResponse, $response);
    }
}


This test file covers the following scenarios:

1. `testGetAll`: Tests the `getAll` method of the `طلابController` class, which retrieves all students from the database.
2. `testGetById`: Tests the `getById` method of the `طلابController` class, which retrieves a student by their ID.
3. `testCreate`: Tests the `create` method of the `طلابController` class, which creates a new student in the database.
4. `testUpdate`: Tests the `update` method of the `طلابController` class, which updates an existing student in the database.
5. `testDelete`: Tests the `delete` method of the `طلابController` class, which deletes a student from the database.

Each test method uses the `createMock` method to create a mock object for the `PDO` and `طلابRepository` classes. The `expects` method is used to specify the expected behavior of the mock objects, and the `willReturn` method is used to specify the expected response from the mock objects.