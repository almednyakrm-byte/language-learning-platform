<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class TestInstructors extends TestCase
{
    private $instructorsController;
    private $mockPdo;

    protected function setUp(): void
    {
        $this->mockPdo = $this->createMock(\PDO::class);
        $this->instructorsController = new InstructorsController($this->mockPdo);
    }

    public function testGetInstructors()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $this->mockPdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM instructors')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->instructorsController->getInstructors($request, $response, $stream);
    }

    public function testGetInstructorById()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM instructors WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->instructorsController->getInstructorById($request, $response, $stream, ['id' => 1]);
    }

    public function testCreateInstructor()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'John Doe', 'email' => 'john@example.com']);

        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO instructors (name, email) VALUES (:name, :email)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->instructorsController->createInstructor($request, $response, $stream);
    }

    public function testUpdateInstructor()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Jane Doe', 'email' => 'jane@example.com']);

        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE instructors SET name = :name, email = :email WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->instructorsController->updateInstructor($request, $response, $stream, ['id' => 1]);
    }

    public function testDeleteInstructor()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM instructors WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->instructorsController->deleteInstructor($request, $response, $stream, ['id' => 1]);
    }
}