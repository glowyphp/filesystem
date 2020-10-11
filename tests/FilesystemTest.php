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

test('test isDirectory() method', function (): void {
    $filesytem = new Filesystem();
    $this->assertTrue($filesytem->isDirectory($this->tempDir));
});

test('test isReadable() method', function (): void {
    $filesytem = new Filesystem();
    $filesytem->put($this->tempDir . '/1.txt', 'test');

    if (DIRECTORY_SEPARATOR === '\\') {
        $this->assertTrue($files->isReadable($this->tempDir . '/1.txt'));
    } else {
        @chmod($this->tempDir . '/1.txt', 0000);
        $this->assertFalse($filesytem->isReadable($this->tempDir . '/1.txt'));
        @chmod($this->tempDir . '/1.txt', 0777);
        $this->assertTrue($filesytem->isReadable($this->tempDir . '/1.txt'));
    }

    $this->assertFalse($filesytem->isReadable($this->tempDir . '/2.txt'));
});

test('test isWritable() method', function (): void {
    $filesytem = new Filesystem();
    $filesytem->put($this->tempDir . '/1.txt', 'test');

    @chmod($this->tempDir . '/1.txt', 0444);
    $this->assertFalse($filesytem->isWritable($this->tempDir . '/1.txt'));
    @chmod($this->tempDir . '/1.txt', 0777);
    $this->assertTrue($filesytem->isWritable($this->tempDir . '/1.txt'));

    $this->assertFalse($filesytem->isWritable($this->tempDir . '/2.txt'));
});

test('test isStream() method', function (): void {
    $filesytem = new Filesystem();
    $this->assertTrue($filesytem->isStream('file://1.txt'));
});

test('test isExecutable() method', function (): void {
    $filesytem = new Filesystem();
    $filesytem->put($this->tempDir . '/1.txt', 'test');

    $this->assertTrue($filesytem->isExecutable($this->tempDir . '/1.txt'));
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

test('test get() method', function (): void {
    $filesytem = new Filesystem();
    $filesytem->put($this->tempDir . '/1.txt', 'test');
    $this->assertEquals('test', $filesytem->get($this->tempDir . '/1.txt'));
});

test('test prepend() method', function (): void {
    $filesytem = new Filesystem();
    $filesytem->put($this->tempDir . '/1.txt', 'world');
    $this->assertEquals(11, $filesytem->prepend($this->tempDir . '/1.txt', 'hello '));
    $this->assertEquals('hello world', $filesytem->get($this->tempDir . '/1.txt'));
});

test('test append() method', function (): void {
    $filesytem = new Filesystem();
    $filesytem->put($this->tempDir . '/1.txt', 'hello');
    $this->assertEquals(6, $filesytem->append($this->tempDir . '/1.txt', ' world'));
    $this->assertEquals('hello world', $filesytem->get($this->tempDir . '/1.txt'));
});
