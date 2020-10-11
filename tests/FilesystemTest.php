<?php

declare(strict_types=1);

use Atomastic\Filesystem\Filesystem;

beforeEach(function (): void {
    $this->tempDir = __DIR__ . '/tmp';
    @mkdir($this->tempDir);
});

afterEach(function (): void {
    $filesytem = new Filesystem();
    $filesytem->deleteDirectory($this->tempDir);
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

    if (PHP_OS_FAMILY === 'Windows') {
        $this->markTestSkipped('The operating system is Windows');
    }

    $filesytem->put($this->tempDir . '/1.txt', 'test');

    @chmod($this->tempDir . '/1.txt', 0000);
    $this->assertFalse($filesytem->isReadable($this->tempDir . '/1.txt'));
    @chmod($this->tempDir . '/1.txt', 0777);
    $this->assertTrue($filesytem->isReadable($this->tempDir . '/1.txt'));

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

test('test isAbsolute method', function (): void {
    $filesytem = new Filesystem();

    $this->assertFalse($filesytem->isAbsolute(''));
    $this->assertTrue($filesytem->isAbsolute('\\'));
    $this->assertTrue($filesytem->isAbsolute('//'));
    $this->assertFalse($filesytem->isAbsolute('file'));
    $this->assertFalse($filesytem->isAbsolute('dir:/file'));
    $this->assertFalse($filesytem->isAbsolute('dir:\file'));
    $this->assertTrue($filesytem->isAbsolute('c:/file'));
    $this->assertTrue($filesytem->isAbsolute('c:\file'));
    $this->assertTrue($filesytem->isAbsolute('C:\file'));
    $this->assertTrue($filesytem->isAbsolute('http://file'));
    $this->assertTrue($filesytem->isAbsolute('remote://file'));
});

test('test isLink method', function (): void {
    $filesytem = new Filesystem();

    if (PHP_OS_FAMILY === 'Windows') {
        $this->markTestSkipped('The operating system is Windows');
    }

    @symlink($this->tempDir . '/link.txt', 'link');
    $this->assertTrue($filesytem->isLink('link'));
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

test('test chmod() method', function (): void {
    $filesytem = new Filesystem();
    $filesytem->put($this->tempDir . '/1.txt', 'test');

    // Set
    $filesytem->chmod($this->tempDir . '/1.txt', 0755);
    $filePermission      = substr(sprintf('%o', fileperms($this->tempDir . '/1.txt')), -4);
    $expectedPermissions = DIRECTORY_SEPARATOR === '\\' ? '0666' : '0755';
    $this->assertEquals($expectedPermissions, $filePermission);

    // Get
    $filesytem->put($this->tempDir . '/2.txt', 'test');
    chmod($this->tempDir . '/2.txt', 0755);
    $filePermission      = $filesytem->chmod($this->tempDir . '/2.txt');
    $expectedPermissions = DIRECTORY_SEPARATOR === '\\' ? '0666' : '0755';
    $this->assertEquals($expectedPermissions, $filePermission);
});

test('test copy() method', function (): void {
    $filesytem = new Filesystem();
    $filesytem->put($this->tempDir . '/1.txt', 'hello');
    $this->assertTrue($filesytem->copy($this->tempDir . '/1.txt', $this->tempDir . '/2.txt'));
});

test('test deleteDirectory() method', function (): void {
    @mkdir($this->tempDir . '/1');
    @mkdir($this->tempDir . '/1/2');
    @mkdir($this->tempDir . '/1/2/3');

    $filesytem = new Filesystem();
    $this->assertTrue($filesytem->deleteDirectory($this->tempDir . '/1'));
});

test('test cleanDirectory() method', function (): void {
    @mkdir($this->tempDir . '/1');
    $filesytem = new Filesystem();
    $filesytem->put($this->tempDir . '/1/1.txt', 'hello');

    $filesytem = new Filesystem();
    $this->assertTrue($filesytem->cleanDirectory($this->tempDir . '/1'));
    $this->assertFalse($filesytem->exists($this->tempDir . '/1/1.txt'));
});

test('test glob() method', function (): void {
    $filesytem = new Filesystem();
    $filesytem->put($this->tempDir . '/1.txt', 'hello');
    $filesytem->put($this->tempDir . '/2.txt', 'world');

    $glob = $filesytem->glob($this->tempDir . '/*.txt');
    $this->assertContains($this->tempDir . '/1.txt', $glob);
    $this->assertContains($this->tempDir . '/2.txt', $glob);

    $glob = $filesytem->glob($this->tempDir . '/*.html');
    $this->assertEquals(0, count($glob));
});

test('test size() method', function (): void {
    $filesytem = new Filesystem();
    $filesytem->put($this->tempDir . '/1.txt', 'hello world');

    $this->assertEquals(11, $filesytem->size($this->tempDir . '/1.txt'));
});

test('test directorySize() method', function (): void {
    $filesytem = new Filesystem();
    @mkdir($this->tempDir . '/1');
    @mkdir($this->tempDir . '/1/2');
    $filesytem->put($this->tempDir . '/1/1.txt', 'hello world');
    $filesytem->put($this->tempDir . '/1/2.txt', 'hello world');
    $filesytem->put($this->tempDir . '/1/2/1.txt', 'hello world');
    $filesytem->put($this->tempDir . '/1/2/2.txt', 'hello world');

    $this->assertEquals(44, $filesytem->directorySize($this->tempDir . '/1'));
});

test('test lastModified() method', function (): void {
    $filesytem = new Filesystem();

    $filesytem->put($this->tempDir . '/1.txt', 'hello world');
    $time = filemtime($this->tempDir . '/1.txt');

    $this->assertEquals($time, $filesytem->lastModified($this->tempDir . '/1.txt'));
});

test('test lastAccess() method', function (): void {
    $filesytem = new Filesystem();

    $filesytem->put($this->tempDir . '/1.txt', 'hello world');
    $time = fileatime($this->tempDir . '/1.txt');

    $this->assertEquals($time, $filesytem->lastAccess($this->tempDir . '/1.txt'));
});
