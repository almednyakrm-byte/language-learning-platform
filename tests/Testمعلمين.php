<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;
use PDOStatement;

class Testمعلمين extends TestCase
{
    private MockObject $pdo;
    private MockObject $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
    }

    public function testGetRequest(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getMethod')->willReturn('GET');

        $response = $this->createMock(ResponseInterface::class);

        $this->pdo->method('query')->with('SELECT * FROM معلمين')->willReturn($this->stmt);
        $this->stmt->method('fetchAll')->willReturn([
            ['id' => 1, 'name' => 'Teacher 1'],
            ['id' => 2, 'name' => 'Teacher 2'],
        ]);

        $controller = new معلمينController($this->pdo);
        $result = $controller->handleRequest($request, $response);

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertJson($result->getBody()->getContents());
    }

    public function testPostRequest(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getMethod')->willReturn('POST');
        $request->method('getParsedBody')->willReturn(['name' => 'New Teacher']);

        $response = $this->createMock(ResponseInterface::class);

        $this->pdo->method('prepare')->with('INSERT INTO معلمين (name) VALUES (:name)')->willReturn($this->stmt);
        $this->stmt->method('execute')->with([':name' => 'New Teacher'])->willReturn(true);
        $this->stmt->method('rowCount')->willReturn(1);

        $controller = new معلمينController($this->pdo);
        $result = $controller->handleRequest($request, $response);

        $this->assertEquals(201, $result->getStatusCode());
        $this->assertJson($result->getBody()->getContents());
    }

    public function testPutRequest(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getMethod')->willReturn('PUT');
        $request->method('getParsedBody')->willReturn(['id' => 1, 'name' => 'Updated Teacher']);

        $response = $this->createMock(ResponseInterface::class);

        $this->pdo->method('prepare')->with('UPDATE معلمين SET name = :name WHERE id = :id')->willReturn($this->stmt);
        $this->stmt->method('execute')->with([':id' => 1, ':name' => 'Updated Teacher'])->willReturn(true);
        $this->stmt->method('rowCount')->willReturn(1);

        $controller = new معلمينController($this->pdo);
        $result = $controller->handleRequest($request, $response);

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertJson($result->getBody()->getContents());
    }

    public function testDeleteRequest(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getMethod')->willReturn('DELETE');
        $request->method('getParsedBody')->willReturn(['id' => 1]);

        $response = $this->createMock(ResponseInterface::class);

        $this->pdo->method('prepare')->with('DELETE FROM معلمين WHERE id = :id')->willReturn($this->stmt);
        $this->stmt->method('execute')->with([':id' => 1])->willReturn(true);
        $this->stmt->method('rowCount')->willReturn(1);

        $controller = new معلمينController($this->pdo);
        $result = $controller->handleRequest($request, $response);

        $this->assertEquals(204, $result->getStatusCode());
    }
}