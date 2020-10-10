<?php

declare(strict_types=1);

use Atomastic\Filesystem\Filesystem;

beforeEach(function (): void {
    $this->tempDir = __DIR__ . '/tmp';
    @mkdir($this->tempDir);
});

afterEach(function (): void {
    @rmdir($this->tempDir);
    unset($this->tempDir);
});

test('test put() method', function (): void {
    $filesytem = new Filesystem();
    $this->assertEquals(4, $filesytem->put($this->tempDir . '/1.txt', 'test'));
});

test('test isFile() method', function (): void {
    $filesytem = new Filesystem();
    $filesytem->put($this->tempDir . '/1.txt', 'test');
    $this->assertTrue($filesytem->isFile($this->tempDir . '/1.txt'));
});

test('test exists() method', function (): void {
    $filesytem = new Filesystem();
    $filesytem->put($this->tempDir . '/1.txt', 'test');
    $this->assertTrue($filesytem->exists($this->tempDir . '/1.txt'));

    $filesytem->put($this->tempDir . '/1.txt', 'test');
    $filesytem->put($this->tempDir . '/2.txt', 'test');
    $this->assertTrue($filesytem->exists([$this->tempDir . '/1.txt', $this->tempDir . '/2.txt']));
});

test('test delete() method', function (): void {
    $filesytem = new Filesystem();
    $filesytem->put($this->tempDir . '/1.txt', 'test');
    $this->assertTrue($filesytem->delete($this->tempDir . '/1.txt'));

    $filesytem->put($this->tempDir . '/1.txt', 'test');
    $filesytem->put($this->tempDir . '/2.txt', 'test');
    $this->assertTrue($filesytem->delete([$this->tempDir . '/1.txt', $this->tempDir . '/2.txt']));
});

test('test hash() method', function (): void {
    $filesytem = new Filesystem();
    $filesytem->put($this->tempDir . '/1.txt', 'test');
    $this->assertEquals('098f6bcd4621d373cade4e832627b4f6', $filesytem->hash($this->tempDir . '/1.txt'));
});
