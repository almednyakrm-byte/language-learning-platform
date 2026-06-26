<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Auth;

class TestAuth extends TestCase
{
    private $auth;
    private $request;
    private $response;

    protected function setUp(): void
    {
        $this->auth = new Auth();
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
    }

    public function testLoginSuccess()
    {
        // Mock database connection
        $dbMock = $this->createMock(\PDO::class);
        $dbMock->method('prepare')
            ->willReturn($this->createMock(\PDOStatement::class));
        $dbMock->method('query')
            ->willReturn($this->createMock(\PDOStatement::class));

        // Mock user data
        $userData = [
            'username' => 'testuser',
            'password' => 'testpassword',
        ];

        // Set up request with user data
        $this->request->method('getParsedBody')
            ->willReturn($userData);

        // Call login method
        $result = $this->auth->login($this->request, $this->response, $dbMock);

        // Assert login success
        $this->assertTrue($result);
    }

    public function testLoginFailure()
    {
        // Mock database connection
        $dbMock = $this->createMock(\PDO::class);
        $dbMock->method('prepare')
            ->willReturn($this->createMock(\PDOStatement::class));
        $dbMock->method('query')
            ->willReturn($this->createMock(\PDOStatement::class));

        // Mock user data
        $userData = [
            'username' => 'wronguser',
            'password' => 'wrongpassword',
        ];

        // Set up request with user data
        $this->request->method('getParsedBody')
            ->willReturn($userData);

        // Call login method
        $result = $this->auth->login($this->request, $this->response, $dbMock);

        // Assert login failure
        $this->assertFalse($result);
    }

    public function testRegisterSuccess()
    {
        // Mock database connection
        $dbMock = $this->createMock(\PDO::class);
        $dbMock->method('prepare')
            ->willReturn($this->createMock(\PDOStatement::class));
        $dbMock->method('query')
            ->willReturn($this->createMock(\PDOStatement::class));

        // Mock user data
        $userData = [
            'username' => 'newuser',
            'password' => 'newpassword',
            'confirm_password' => 'newpassword',
        ];

        // Set up request with user data
        $this->request->method('getParsedBody')
            ->willReturn($userData);

        // Call register method
        $result = $this->auth->register($this->request, $this->response, $dbMock);

        // Assert register success
        $this->assertTrue($result);
    }

    public function testRegisterFailure()
    {
        // Mock database connection
        $dbMock = $this->createMock(\PDO::class);
        $dbMock->method('prepare')
            ->willReturn($this->createMock(\PDOStatement::class));
        $dbMock->method('query')
            ->willReturn($this->createMock(\PDOStatement::class));

        // Mock user data
        $userData = [
            'username' => 'existinguser',
            'password' => 'newpassword',
            'confirm_password' => 'newpassword',
        ];

        // Set up request with user data
        $this->request->method('getParsedBody')
            ->willReturn($userData);

        // Call register method
        $result = $this->auth->register($this->request, $this->response, $dbMock);

        // Assert register failure
        $this->assertFalse($result);
    }

    public function testSessionLogin()
    {
        // Mock session data
        $_SESSION['username'] = 'testuser';

        // Call session login method
        $result = $this->auth->sessionLogin();

        // Assert session login success
        $this->assertTrue($result);
    }

    public function testSessionLoginFailure()
    {
        // Mock session data
        unset($_SESSION['username']);

        // Call session login method
        $result = $this->auth->sessionLogin();

        // Assert session login failure
        $this->assertFalse($result);
    }
}