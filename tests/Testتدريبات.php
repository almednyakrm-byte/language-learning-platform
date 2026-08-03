<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testتدريبات extends TestCase
{
    private $pdo;
    private $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
    }

    public function testGetAllتدريبات()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM تدريبات')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'تدريب 1'],
                ['id' => 2, 'name' => 'تدريب 2'],
            ]);

        $تدريبات = new تدريبات($this->pdo);
        $result = $تدريبات->getAll();

        $this->assertEquals([
            ['id' => 1, 'name' => 'تدريب 1'],
            ['id' => 2, 'name' => 'تدريب 2'],
        ], $result);
    }

    public function testGetتدريبById()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM تدريبات WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'تدريب 1']);

        $تدريبات = new تدريبات($this->pdo);
        $result = $تدريبات->getById(1);

        $this->assertEquals(['id' => 1, 'name' => 'تدريب 1'], $result);
    }

    public function testCreateتدريب()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO تدريبات (name) VALUES (:name)')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'تدريب جديد');

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(3);

        $تدريبات = new تدريبات($this->pdo);
        $result = $تدريبات->create(['name' => 'تدريب جديد']);

        $this->assertEquals(3, $result);
    }

    public function testUpdateتدريب()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE تدريبات SET name = :name WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'تدريب محدث');

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $تدريبات = new تدريبات($this->pdo);
        $result = $تدريبات->update(1, ['name' => 'تدريب محدث']);

        $this->assertTrue($result);
    }

    public function testDeleteتدريب()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM تدريبات WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $تدريبات = new تدريبات($this->pdo);
        $result = $تدريبات->delete(1);

        $this->assertTrue($result);
    }
}

class تدريبات
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->query('SELECT * FROM تدريبات');
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM تدريبات WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO تدريبات (name) VALUES (:name)');
        $stmt->bindParam(':name', $data['name']);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare('UPDATE تدريبات SET name = :name WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM تدريبات WHERE id = :id');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}