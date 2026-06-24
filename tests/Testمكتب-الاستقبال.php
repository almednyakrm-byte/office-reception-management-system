<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testمكتب_الاستقبال extends TestCase
{
    private $pdo;
    private $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
    }

    public function testGetAllمكتب_الاستقبال()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM مكتب_الاستقبال')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'مكتب الاستقبال 1'],
                ['id' => 2, 'name' => 'مكتب الاستقبال 2'],
            ]);

        $result = $this->getAllمكتب_الاستقبال();
        $this->assertEquals([
            ['id' => 1, 'name' => 'مكتب الاستقبال 1'],
            ['id' => 2, 'name' => 'مكتب الاستقبال 2'],
        ], $result);
    }

    public function testGetمكتب_الاستقبالById()
    {
        $id = 1;
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM مكتب_الاستقبال WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'مكتب الاستقبال 1']);

        $result = $this->getمكتب_الاستقبالById($id);
        $this->assertEquals(['id' => 1, 'name' => 'مكتب الاستقبال 1'], $result);
    }

    public function testCreateمكتب_الاستقبال()
    {
        $data = ['name' => 'مكتب الاستقبال 3'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO مكتب_الاستقبال (name) VALUES (:name)')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->createمكتب_الاستقبال($data);
        $this->assertTrue($result);
    }

    public function testUpdateمكتب_الاستقبال()
    {
        $id = 1;
        $data = ['name' => 'مكتب الاستقبال 1 updated'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE مكتب_الاستقبال SET name = :name WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->updateمكتب_الاستقبال($id, $data);
        $this->assertTrue($result);
    }

    public function testDeleteمكتب_الاستقبال()
    {
        $id = 1;
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM مكتب_الاستقبال WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->deleteمكتب_الاستقبال($id);
        $this->assertTrue($result);
    }

    private function getAllمكتب_الاستقبال()
    {
        $stmt = $this->pdo->query('SELECT * FROM مكتب_الاستقبال');
        return $stmt->fetchAll();
    }

    private function getمكتب_الاستقبالById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM مكتب_الاستقبال WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    private function createمكتب_الاستقبال($data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO مكتب_الاستقبال (name) VALUES (:name)');
        $stmt->bindParam(':name', $data['name']);
        return $stmt->execute();
    }

    private function updateمكتب_الاستقبال($id, $data)
    {
        $stmt = $this->pdo->prepare('UPDATE مكتب_الاستقبال SET name = :name WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        return $stmt->execute();
    }

    private function deleteمكتب_الاستقبال($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM مكتب_الاستقبال WHERE id = :id');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}