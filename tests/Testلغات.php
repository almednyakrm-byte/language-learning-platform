<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\LanguagesController;
use App\Repository\LanguagesRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestLanguages extends TestCase
{
    private $languagesController;
    private $languagesRepository;
    private $pdo;

    protected function setUp(): void
    {
        $this->languagesRepository = $this->createMock(LanguagesRepository::class);
        $this->pdo = $this->createMock(PDO::class);
        $this->languagesController = new LanguagesController($this->languagesRepository, $this->pdo);
    }

    public function testGetLanguages()
    {
        $languages = [
            ['id' => 1, 'name' => 'English'],
            ['id' => 2, 'name' => 'French'],
        ];

        $this->languagesRepository->expects($this->once())
            ->method('getAll')
            ->willReturn($languages);

        $response = $this->languagesController->getLanguages();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($languages), $response->getBody()->getContents());
    }

    public function testCreateLanguage()
    {
        $language = ['id' => 3, 'name' => 'Spanish'];

        $this->languagesRepository->expects($this->once())
            ->method('create')
            ->with($language)
            ->willReturn($language);

        $response = $this->languagesController->createLanguage($language);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(json_encode($language), $response->getBody()->getContents());
    }

    public function testUpdateLanguage()
    {
        $language = ['id' => 1, 'name' => 'English'];

        $this->languagesRepository->expects($this->once())
            ->method('update')
            ->with($language)
            ->willReturn($language);

        $response = $this->languagesController->updateLanguage($language);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($language), $response->getBody()->getContents());
    }

    public function testDeleteLanguage()
    {
        $languageId = 1;

        $this->languagesRepository->expects($this->once())
            ->method('delete')
            ->with($languageId)
            ->willReturn(true);

        $response = $this->languagesController->deleteLanguage($languageId);
        $this->assertEquals(200, $response->getStatusCode());
    }
}