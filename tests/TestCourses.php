<?php

declare(strict_types=1);

namespace App\Tests;

use App\Courses;
use App\Database;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Response;

class TestCourses extends TestCase
{
    private $db;
    private $courses;

    protected function setUp(): void
    {
        $this->db = $this->createMock(Database::class);
        $this->courses = new Courses($this->db);
    }

    public function testGetCourses(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);

        $this->db->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM courses')
            ->willReturn([
                ['id' => 1, 'name' => 'Course 1'],
                ['id' => 2, 'name' => 'Course 2'],
            ]);

        $response = $this->courses->getCourses($request, $handler);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetCourseById(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);

        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $this->db->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM courses WHERE id = :id', ['id' => 1])
            ->willReturn([
                ['id' => 1, 'name' => 'Course 1'],
            ]);

        $response = $this->courses->getCourseById($request, $handler);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateCourse(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);

        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'New Course']);

        $this->db->expects($this->once())
            ->method('execute')
            ->with('INSERT INTO courses (name) VALUES (:name)', ['name' => 'New Course']);

        $response = $this->courses->createCourse($request, $handler);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateCourse(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);

        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Updated Course']);

        $this->db->expects($this->once())
            ->method('execute')
            ->with('UPDATE courses SET name = :name WHERE id = :id', ['name' => 'Updated Course', 'id' => 1]);

        $response = $this->courses->updateCourse($request, $handler);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteCourse(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);

        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $this->db->expects($this->once())
            ->method('execute')
            ->with('DELETE FROM courses WHERE id = :id', ['id' => 1]);

        $response = $this->courses->deleteCourse($request, $handler);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(204, $response->getStatusCode());
    }
}