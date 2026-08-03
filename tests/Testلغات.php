<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\LanguagesController;
use App\Repository\LanguagesRepository;
use App\Entity\Language;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TestLanguages extends TestCase
{
    private $languagesController;
    private $languagesRepository;
    private $language;

    protected function setUp(): void
    {
        $this->language = new Language();
        $this->language->setId(1);
        $this->language->setName('English');
        $this->language->setCode('en');

        $this->languagesRepository = $this->createMock(LanguagesRepository::class);
        $this->languagesRepository
            ->method('findAll')
            ->willReturn([$this->language]);

        $this->languagesController = new LanguagesController($this->languagesRepository);
    }

    public function testGetLanguages()
    {
        $request = new Request();
        $response = $this->languagesController->getLanguages($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetLanguage()
    {
        $request = new Request();
        $response = $this->languagesController->getLanguage($request, 1);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreateLanguage()
    {
        $request = new Request();
        $request->request->set('name', 'French');
        $request->request->set('code', 'fr');
        $response = $this->languagesController->createLanguage($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateLanguage()
    {
        $request = new Request();
        $request->request->set('name', 'Spanish');
        $request->request->set('code', 'es');
        $response = $this->languagesController->updateLanguage($request, 1);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteLanguage()
    {
        $request = new Request();
        $response = $this->languagesController->deleteLanguage($request, 1);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testGetLanguageNotFound()
    {
        $this->languagesRepository
            ->method('find')
            ->willReturn(null);

        $request = new Request();
        $this->expectException(NotFoundHttpException::class);
        $this->languagesController->getLanguage($request, 1);
    }
}



// LanguagesController.php

namespace App\Controller;

use App\Repository\LanguagesRepository;
use App\Entity\Language;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LanguagesController
{
    private $languagesRepository;

    public function __construct(LanguagesRepository $languagesRepository)
    {
        $this->languagesRepository = $languagesRepository;
    }

    /**
     * @Route("/languages", name="get_languages", methods={"GET"})
     */
    public function getLanguages(Request $request)
    {
        return new Response(json_encode($this->languagesRepository->findAll()));
    }

    /**
     * @Route("/languages/{id}", name="get_language", methods={"GET"})
     */
    public function getLanguage(Request $request, $id)
    {
        $language = $this->languagesRepository->find($id);
        if (!$language) {
            throw new NotFoundHttpException('Language not found');
        }
        return new Response(json_encode($language));
    }

    /**
     * @Route("/languages", name="create_language", methods={"POST"})
     */
    public function createLanguage(Request $request)
    {
        $language = new Language();
        $language->setName($request->request->get('name'));
        $language->setCode($request->request->get('code'));
        $this->languagesRepository->save($language);
        return new Response('', Response::HTTP_CREATED);
    }

    /**
     * @Route("/languages/{id}", name="update_language", methods={"PUT"})
     */
    public function updateLanguage(Request $request, $id)
    {
        $language = $this->languagesRepository->find($id);
        if (!$language) {
            throw new NotFoundHttpException('Language not found');
        }
        $language->setName($request->request->get('name'));
        $language->setCode($request->request->get('code'));
        $this->languagesRepository->save($language);
        return new Response('', Response::HTTP_OK);
    }

    /**
     * @Route("/languages/{id}", name="delete_language", methods={"DELETE"})
     */
    public function deleteLanguage(Request $request, $id)
    {
        $language = $this->languagesRepository->find($id);
        if (!$language) {
            throw new NotFoundHttpException('Language not found');
        }
        $this->languagesRepository->remove($language);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}