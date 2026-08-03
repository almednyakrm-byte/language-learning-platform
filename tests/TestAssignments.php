<?php

declare(strict_types=1);

namespace App\Tests;

use App\Assignments;
use App\Database;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class TestAssignments extends TestCase
{
    private MockObject $pdo;
    private Assignments $assignments;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(\PDO::class);
        $this->assignments = new Assignments(new Database($this->pdo));
    }

    public function testGetAllAssignments(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM assignments')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $this->assignments->getAll($request, $response);
    }

    public function testGetAssignmentById(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM assignments WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $this->assignments->getById($request, $response);
    }

    public function testCreateAssignment(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO assignments (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn([
                'name' => 'Test Assignment',
                'description' => 'This is a test assignment',
            ]);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $this->assignments->create($request, $response);
    }

    public function testUpdateAssignment(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('UPDATE assignments SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn([
                'name' => 'Updated Assignment',
                'description' => 'This is an updated assignment',
            ]);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $this->assignments->update($request, $response);
    }

    public function testDeleteAssignment(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM assignments WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $this->assignments->delete($request, $response);
    }
}