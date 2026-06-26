<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Controllers\LessonsController;
use App\Models\LessonsModel;

class TestLessons extends TestCase
{
    private $lessonsController;
    private $lessonsModel;
    private $request;
    private $response;

    protected function setUp(): void
    {
        $this->lessonsController = new LessonsController();
        $this->lessonsModel = $this->createMock(LessonsModel::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
    }

    public function testGetLessons()
    {
        $this->lessonsModel
            ->expects($this->once())
            ->method('getAllLessons')
            ->willReturn([
                ['id' => 1, 'name' => 'Lesson 1'],
                ['id' => 2, 'name' => 'Lesson 2'],
            ]);

        $this->request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn('GET');

        $response = $this->lessonsController->index($this->request, $this->response, $this->lessonsModel);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetLessonById()
    {
        $this->lessonsModel
            ->expects($this->once())
            ->method('getLessonById')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'Lesson 1']);

        $this->request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn('GET');

        $this->request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->lessonsController->show($this->request, $this->response, $this->lessonsModel);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateLesson()
    {
        $this->lessonsModel
            ->expects($this->once())
            ->method('createLesson')
            ->with(['name' => 'New Lesson'])
            ->willReturn(1);

        $this->request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn('POST');

        $this->request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'New Lesson']);

        $response = $this->lessonsController->store($this->request, $this->response, $this->lessonsModel);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateLesson()
    {
        $this->lessonsModel
            ->expects($this->once())
            ->method('updateLesson')
            ->with(1, ['name' => 'Updated Lesson'])
            ->willReturn(true);

        $this->request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn('PUT');

        $this->request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $this->request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Updated Lesson']);

        $response = $this->lessonsController->update($this->request, $this->response, $this->lessonsModel);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteLesson()
    {
        $this->lessonsModel
            ->expects($this->once())
            ->method('deleteLesson')
            ->with(1)
            ->willReturn(true);

        $this->request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn('DELETE');

        $this->request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->lessonsController->destroy($this->request, $this->response, $this->lessonsModel);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(204, $response->getStatusCode());
    }
}