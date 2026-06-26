<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use QuizModule\Quizzes;

class TestQuizzes extends TestCase
{
    private $quizzes;
    private $request;
    private $response;
    private $pdo;

    protected function setUp(): void
    {
        $this->quizzes = new Quizzes();
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->pdo = $this->createMock(\PDO::class);
        $this->quizzes->setPdo($this->pdo);
    }

    public function testGetQuizzes()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM quizzes')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('GET');

        $response = $this->quizzes->handle($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testPostQuizzes()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO quizzes (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('POST');

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Test Quiz', 'description' => 'This is a test quiz']);

        $response = $this->quizzes->handle($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testPutQuizzes()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE quizzes SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('PUT');

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['id' => 1, 'name' => 'Updated Quiz', 'description' => 'This is an updated quiz']);

        $response = $this->quizzes->handle($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testDeleteQuizzes()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM quizzes WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('DELETE');

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->quizzes->handle($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}