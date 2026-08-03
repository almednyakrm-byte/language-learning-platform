<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ProfesseursController;
use App\Repository\ProfesseursRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestProfesseurs extends TestCase
{
    private $professeursController;
    private $professeursRepository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->professeursRepository = $this->createMock(ProfesseursRepository::class);
        $this->professeursController = new ProfesseursController($this->professeursRepository);
    }

    public function testGetProfesseurs()
    {
        $expectedData = [
            ['id' => 1, 'nom' => 'Professeur 1'],
            ['id' => 2, 'nom' => 'Professeur 2'],
        ];

        $this->professeursRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedData);

        $response = $this->professeursController->getProfesseurs();
        $this->assertEquals($expectedData, $response);
    }

    public function testCreateProfesseur()
    {
        $professeur = ['nom' => 'Professeur 3'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO professeurs (nom) VALUES (:nom)');
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with([':nom' => $professeur['nom']]);

        $response = $this->professeursController->createProfesseur($professeur);
        $this->assertEquals(['id' => 3, 'nom' => 'Professeur 3'], $response);
    }

    public function testUpdateProfesseur()
    {
        $professeur = ['id' => 1, 'nom' => 'Professeur 1'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE professeurs SET nom = :nom WHERE id = :id');
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with([':nom' => $professeur['nom'], ':id' => $professeur['id']]);

        $response = $this->professeursController->updateProfesseur($professeur);
        $this->assertEquals(['id' => 1, 'nom' => 'Professeur 1'], $response);
    }

    public function testDeleteProfesseur()
    {
        $professeur = ['id' => 1];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM professeurs WHERE id = :id');
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with([':id' => $professeur['id']]);

        $response = $this->professeursController->deleteProfesseur($professeur);
        $this->assertEquals(true, $response);
    }
}


Note: This code assumes that the `ProfesseursController` and `ProfesseursRepository` classes are already defined, and that the `PDO` class is being used to interact with the database. The `createMock` method is used to create mock objects for the `PDO` and `ProfesseursRepository` classes, allowing us to test the `ProfesseursController` class without actually interacting with the database.