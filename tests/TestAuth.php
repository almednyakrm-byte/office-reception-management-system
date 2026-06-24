<?php

namespace App\Tests;

use App\Auth;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

class TestAuth extends TestCase
{
    private $auth;
    private $session;
    private $logger;

    protected function setUp(): void
    {
        $this->session = new Session(new MockFileSessionStorage());
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->auth = new Auth($this->session, $this->logger);
    }

    public function testLoginSuccess()
    {
        // Mock database connection
        $dbMock = $this->createMock(\PDO::class);
        $dbMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM users WHERE username = ? AND password = ?')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->auth->setDatabase($dbMock);

        // Test login
        $result = $this->auth->login('testUser', 'testPassword');
        $this->assertTrue($result);
        $this->assertEquals('testUser', $this->session->get('username'));
    }

    public function testLoginFailure()
    {
        // Mock database connection
        $dbMock = $this->createMock(\PDO::class);
        $dbMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM users WHERE username = ? AND password = ?')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->auth->setDatabase($dbMock);

        // Test login
        $result = $this->auth->login('wrongUser', 'wrongPassword');
        $this->assertFalse($result);
        $this->assertNull($this->session->get('username'));
    }

    public function testRegisterSuccess()
    {
        // Mock database connection
        $dbMock = $this->createMock(\PDO::class);
        $dbMock->expects($this->once())
            ->method('exec')
            ->with('INSERT INTO users (username, password) VALUES (?, ?)')
            ->willReturn(1);

        $this->auth->setDatabase($dbMock);

        // Test register
        $result = $this->auth->register('newUser', 'newPassword');
        $this->assertTrue($result);
        $this->assertEquals('newUser', $this->session->get('username'));
    }

    public function testRegisterFailure()
    {
        // Mock database connection
        $dbMock = $this->createMock(\PDO::class);
        $dbMock->expects($this->once())
            ->method('exec')
            ->with('INSERT INTO users (username, password) VALUES (?, ?)')
            ->willReturn(0);

        $this->auth->setDatabase($dbMock);

        // Test register
        $result = $this->auth->register('existingUser', 'existingPassword');
        $this->assertFalse($result);
        $this->assertNull($this->session->get('username'));
    }
}