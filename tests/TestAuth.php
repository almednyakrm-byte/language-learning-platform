<?php

namespace App\Tests\Unit\Auth;

use App\Auth\AuthService;
use App\Auth\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class TestAuth extends TestCase
{
    private $authService;
    private $connection;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->authService = new AuthService($this->connection);
    }

    public function testLoginSuccess()
    {
        $username = 'test_user';
        $password = 'test_password';

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->with('SELECT * FROM users WHERE username = ?', [$username])
            ->willReturn(new \ArrayIterator([['id' => 1, 'username' => $username, 'password' => $password]]));

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->with('SELECT * FROM users WHERE username = ? AND password = ?', [$username, $password])
            ->willReturn(new \ArrayIterator([['id' => 1, 'username' => $username, 'password' => $password]]));

        $result = $this->authService->login($username, $password);
        $this->assertTrue($result);
    }

    public function testLoginFailure()
    {
        $username = 'test_user';
        $password = 'test_password';

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->with('SELECT * FROM users WHERE username = ?', [$username])
            ->willReturn(new \ArrayIterator([['id' => 1, 'username' => $username, 'password' => 'wrong_password']]));

        $result = $this->authService->login($username, $password);
        $this->assertFalse($result);
    }

    public function testRegisterSuccess()
    {
        $username = 'test_user';
        $password = 'test_password';

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->with('INSERT INTO users (username, password) VALUES (?, ?)', [$username, $password])
            ->willReturn(true);

        $result = $this->authService->register($username, $password);
        $this->assertTrue($result);
    }

    public function testRegisterFailure()
    {
        $username = 'test_user';
        $password = 'test_password';

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->with('INSERT INTO users (username, password) VALUES (?, ?)', [$username, $password])
            ->willReturn(false);

        $result = $this->authService->register($username, $password);
        $this->assertFalse($result);
    }
}


This test file covers the following scenarios:

- `testLoginSuccess`: Tests that the login method returns true when the username and password are correct.
- `testLoginFailure`: Tests that the login method returns false when the username and password are incorrect.
- `testRegisterSuccess`: Tests that the register method returns true when the user is successfully created.
- `testRegisterFailure`: Tests that the register method returns false when the user creation fails.

Note that this test file assumes that the `AuthService` class has methods `login` and `register` that interact with the database. The `login` method is expected to return a boolean indicating whether the login was successful, and the `register` method is expected to return a boolean indicating whether the user was successfully created.