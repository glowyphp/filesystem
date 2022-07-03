<?php

declare(strict_types=1);

use Glowy\Filesystem\Filesystem;
use Glowy\Filesystem\File;
use Glowy\Filesystem\Directory;

use function Glowy\Filesystem\filesystem;

beforeEach(function (): void {
    $this->tempDir = __DIR__ . '/tmp';
    @mkdir($this->tempDir);
});

afterEach(function (): void {
    $filesystem = new Filesystem();
    $filesystem->directory($this->tempDir)->delete();
    unset($this->tempDir);
});

test('instances', function (): void {
    $this->assertInstanceOf(Filesystem::class, new Filesystem);
    $this->assertInstanceOf(File::class, new File('/1/1.txt'));
    $this->assertInstanceOf(Directory::class, new Directory('/1'));
});

test('filesystem helper', function (): void {
    $this->assertInstanceOf(Filesystem::class, filesystem());
});

test('directory delete method', function (): void {
    @mkdir($this->tempDir . '/1');
    @mkdir($this->tempDir . '/1/2');
    @mkdir($this->tempDir . '/1/2/3');

    $filesystem = new Filesystem();
    $this->assertTrue($filesystem->directory($this->tempDir . '/1')->delete());
});

test('file replace method', function (): void {
    file_put_contents($this->tempDir . '/replace.txt', 'foo');
    $filesystem = new Filesystem();
    $filesystem->file($this->tempDir . '/replace.txt')->replace('foo', 'boo');
    $this->assertEquals('boo', file_get_contents($this->tempDir . '/replace.txt'));
});

test('file put method', function (): void {
    $filesystem = new Filesystem();
    $this->assertEquals(4, $filesystem->file($this->tempDir . '/2.txt')->put('test'));
});

test('file isFile method', function (): void {
    $filesystem = new Filesystem();
    $filesystem->file($this->tempDir . '/1.txt')->put('test');
    $this->assertTrue($filesystem->file($this->tempDir . '/1.txt')->isFile());
});

test('filesystem isWindowsPath method', function (): void {
    $filesystem = new Filesystem();
    $this->assertTrue($filesystem->isWindowsPath('C:\file\1.txt'));
});

test('directory isDirectory method', function (): void {
    $filesystem = new Filesystem();
    $this->assertTrue($filesystem->directory($this->tempDir)->isDirectory());
});

test('file isReadable method', function (): void {
    if (PHP_OS_FAMILY === 'Windows') {
        $this->markTestSkipped('The operating system is Windows');
    }

    $filesystem = new Filesystem();
    $filesystem->file($this->tempDir . '/1.txt')->put('test');

    @chmod($this->tempDir . '/1.txt', 0000);
    $this->assertFalse($filesystem->file($this->tempDir . '/1.txt')->isReadable());
    @chmod($this->tempDir . '/1.txt', 0777);
    $this->assertTrue($filesystem->file($this->tempDir . '/1.txt')->isReadable());

    $this->assertFalse($filesystem->file($this->tempDir . '/2.txt')->isReadable());
});


test('file isWritable method', function (): void {

    if (PHP_OS_FAMILY === 'Windows') {
        $this->markTestSkipped('The operating system is Windows');
    }

    $filesystem = new Filesystem();
    $filesystem->file($this->tempDir . '/1.txt')->put('test');

    @chmod($this->tempDir . '/1.txt', 0444);
    $this->assertFalse($filesystem->file($this->tempDir . '/1.txt')->isWritable());
    @chmod($this->tempDir . '/1.txt', 0777);
    $this->assertTrue($filesystem->file($this->tempDir . '/1.txt')->isWritable());

    $this->assertFalse($filesystem->file($this->tempDir . '/2.txt')->isWritable());
});

test('test isStream() method', function (): void {
    $filesystem = new Filesystem();
    $this->assertTrue($filesystem->isStream('file://1.txt'));
});

test('filesystem isAbsolute method', function (): void {
    $filesystem = new Filesystem();

    $this->assertFalse($filesystem->isAbsolute(''));
    $this->assertTrue($filesystem->isAbsolute('\\'));
    $this->assertTrue($filesystem->isAbsolute('//'));
    $this->assertFalse($filesystem->isAbsolute('file'));
    $this->assertFalse($filesystem->isAbsolute('dir:/file'));
    $this->assertFalse($filesystem->isAbsolute('dir:\file'));
    $this->assertTrue($filesystem->isAbsolute('c:/file'));
    $this->assertTrue($filesystem->isAbsolute('c:/file/file.txt'));
    $this->assertTrue($filesystem->isAbsolute('c:\file'));
    $this->assertTrue($filesystem->isAbsolute('C:\file'));
    $this->assertTrue($filesystem->isAbsolute('http://file'));
    $this->assertTrue($filesystem->isAbsolute('remote://file'));
});

test('file exists method', function (): void {
    $filesystem = new Filesystem();
    $filesystem->file($this->tempDir . '/1.txt')->put('test');
    $this->assertTrue($filesystem->file($this->tempDir . '/1.txt')->exists());

    $filesystem->file($this->tempDir . '/1.txt')->put('test');
    $filesystem->file($this->tempDir . '/2.txt')->put('test');
    $this->assertTrue($filesystem->file($this->tempDir . '/1.txt')->exists());
    $this->assertTrue($filesystem->file($this->tempDir . '/2.txt')->exists());
});

test('file delete method', function (): void {
    $filesystem = new Filesystem();
    $filesystem->file($this->tempDir . '/1.txt')->put('test');
    $this->assertTrue($filesystem->file($this->tempDir . '/1.txt')->delete());

    $filesystem->file($this->tempDir . '/1.txt')->put('test');
    $filesystem->file($this->tempDir . '/2.txt')->put('test');
    $this->assertTrue($filesystem->file($this->tempDir . '/1.txt')->delete());
    $this->assertTrue($filesystem->file($this->tempDir . '/2.txt')->delete());
});

test('file hash method', function (): void {
    $filesystem = new Filesystem();
    $filesystem->file($this->tempDir . '/1.txt')->put('test');
    $this->assertEquals('098f6bcd4621d373cade4e832627b4f6', $filesystem->file($this->tempDir . '/1.txt')->hash());
});

test('file get method', function (): void {
    $filesystem = new Filesystem();
    $filesystem->file($this->tempDir . '/1.txt')->put('test');
    $this->assertEquals('test', $filesystem->file($this->tempDir . '/1.txt')->get());
    $this->assertEquals('test', $filesystem->file($this->tempDir . '/1.txt')->get(true));
});

test('file sharedGet method', function (): void {
    $filesystem = new Filesystem();
    $filesystem->file($this->tempDir . '/shared.txt')->put('test');
    $this->assertEquals('test', $filesystem->file($this->tempDir . '/shared.txt')->sharedGet());
});

test('file prepend method', function (): void {
    $filesystem = new Filesystem();
    $filesystem->file($this->tempDir . '/1.txt')->put('world');
    $this->assertEquals(11, $filesystem->file($this->tempDir . '/1.txt')->prepend('hello '));
    $this->assertEquals('hello world', $filesystem->file($this->tempDir . '/1.txt')->get());
});

test('file append method', function (): void {
    $filesystem = new Filesystem();
    $filesystem->file($this->tempDir . '/1.txt')->put('hello');
    $this->assertEquals(6, $filesystem->file($this->tempDir . '/1.txt')->append(' world'));
    $this->assertEquals('hello world', $filesystem->file($this->tempDir . '/1.txt')->get());
});

test('file isEqual method', function (): void {
    $filesystem = new Filesystem();
    $filesystem->file($this->tempDir . '/foo.txt')->put('Foo');
    $filesystem->file($this->tempDir . '/foo2.txt')->put('Foo');
    $filesystem->file($this->tempDir . '/bar.txt')->put('Bar');
    $filesystem->file($this->tempDir . '/bar2.txt')->put('Bar2');
    
    $result  = $filesystem->file($this->tempDir . '/foo.txt')->isEqual($this->tempDir . '/foo2.txt');
    $result2 = $filesystem->file($this->tempDir . '/bar.txt')->isEqual($this->tempDir . '/bar2.txt');

    $this->assertEquals(true, $result);
    $this->assertEquals(false, $result2);
});

test('file chmod method', function (): void {
    if (PHP_OS_FAMILY === 'Windows') {
        $this->markTestSkipped('The operating system is Windows');
    }

    $filesystem = new Filesystem();
    $filesystem->file($this->tempDir . '/1.txt')->put('test');

    // Set
    $filesystem->file($this->tempDir . '/1.txt')->chmod(0755);
    $filePermission      = substr(sprintf('%o', fileperms($this->tempDir . '/1.txt')), -4);
    $expectedPermissions = DIRECTORY_SEPARATOR === '\\' ? '0666' : '0755';
    $this->assertEquals($expectedPermissions, $filePermission);

    // Get
    $filesystem->file($this->tempDir . '/2.txt')->put('test');
    chmod($this->tempDir . '/2.txt', 0755);
    $filePermission      = $filesystem->file($this->tempDir . '/1.txt')->chmod();
    $expectedPermissions = DIRECTORY_SEPARATOR === '\\' ? '0666' : '0755';
    $this->assertEquals($expectedPermissions, $filePermission);
});

test('directory chmod method', function (): void {
    if (PHP_OS_FAMILY === 'Windows') {
        $this->markTestSkipped('The operating system is Windows');
    }

    $filesystem = new Filesystem();

    // Set
    $filesystem->directory($this->tempDir)->chmod(0755);
    $filePermission      = substr(sprintf('%o', fileperms($this->tempDir)), -4);
    $expectedPermissions = DIRECTORY_SEPARATOR === '\\' ? '0666' : '0755';
    $this->assertEquals($expectedPermissions, $filePermission);

    // Get
    chmod($this->tempDir, 0755);
    $filePermission      = $filesystem->directory($this->tempDir)->chmod();
    $expectedPermissions = DIRECTORY_SEPARATOR === '\\' ? '0666' : '0755';
    $this->assertEquals($expectedPermissions, $filePermission);
});


test('file copy method', function (): void {
    $filesystem = new Filesystem();
    $filesystem->file($this->tempDir . '/1.txt')->put('hello');
    $this->assertTrue($filesystem->file($this->tempDir . '/1.txt')->copy($this->tempDir . '/2.txt'));
});


test('file move method', function (): void {
    $filesystem = new Filesystem();
    $filesystem->file($this->tempDir . '/1.txt')->put('hello');
    $filesystem->file($this->tempDir . '/1.txt')->move($this->tempDir . '/2.txt');
    $this->assertTrue($filesystem->file($this->tempDir . '/2.txt')->exists());
});

test('directory create method', function (): void {
    $filesystem = new Filesystem();
    $this->assertTrue($filesystem->directory($this->tempDir . '/1')->create());
    $this->assertTrue($filesystem->directory($this->tempDir . '/1/2/3/4/')->create(0755, true));
    $this->assertTrue($filesystem->directory($this->tempDir . '/2/3/4/')->create(0755, true));
});

test('directory ensureExists method', function (): void {
    $filesystem = new Filesystem();
    $this->assertTrue($filesystem->directory($this->tempDir . '/1')->ensureExists());
    $this->assertTrue($filesystem->directory($this->tempDir . '/1/2/3/4/')->ensureExists(0755, true));
    $this->assertTrue($filesystem->directory($this->tempDir . '/2/3/4/')->ensureExists(0755, true));
});

test('directory move method', function (): void {
    @mkdir($this->tempDir . '/1');
    @mkdir($this->tempDir . '/3');

    $filesystem = new Filesystem();
    $this->assertTrue($filesystem->directory($this->tempDir . '/1')->move($this->tempDir . '/2'));
    $this->assertTrue($filesystem->directory($this->tempDir . '/3')->move($this->tempDir . '/4'));
});


test('directory copy method', function (): void {
    @mkdir($this->tempDir . '/1');
    @mkdir($this->tempDir . '/3');
    @mkdir($this->tempDir . '/4');

    $filesystem = new Filesystem();
    $this->assertTrue($filesystem->directory($this->tempDir . '/1')->copy($this->tempDir . '/2'));
    $this->assertTrue($filesystem->directory($this->tempDir . '/3')->copy($this->tempDir . '/4'));
});

test('directory directories method', function (): void {
    if (PHP_OS_FAMILY === 'Windows') {
        $this->markTestSkipped('The operating system is Windows');
    }

    @mkdir($this->tempDir . '/foo');
    @mkdir($this->tempDir . '/foo/foo-2');
    @mkdir($this->tempDir . '/bar');
    @mkdir($this->tempDir . '/bar/bar-2');
    @mkdir($this->tempDir . '/zed');
    @mkdir($this->tempDir . '/zed/zed-2');

    $filesystem = new Filesystem();
    $dirs = array_map(function (string $dir): string {
        return str_replace($this->tempDir . '/', '', $dir);
    }, $filesystem->directory($this->tempDir)->directories());
    $this->assertTrue($dirs[0] == 'bar');
    $this->assertTrue($dirs[1] == 'foo');
    $this->assertTrue($dirs[2] == 'zed');

    $filesystem2 = new Filesystem();
    $dirs2 = array_map(function (string $dir): string {
        return str_replace($this->tempDir . '/', '', $dir);
    }, $filesystem2->directory($this->tempDir)->directories(true));
    $this->assertTrue($dirs2[0] == 'bar');
    $this->assertTrue($dirs2[1] == 'bar/bar-2');
    $this->assertTrue($dirs2[2] == 'foo');
    $this->assertTrue($dirs2[3] == 'foo/foo-2');
    $this->assertTrue($dirs2[4] == 'zed');
    $this->assertTrue($dirs2[5] == 'zed/zed-2');
});

test('directory files method', function (): void {
    if (PHP_OS_FAMILY === 'Windows') {
        $this->markTestSkipped('The operating system is Windows');
    }
    
    $filesystem = new Filesystem();

    $filesystem->file($this->tempDir . '/1.txt')->put('test');
    @mkdir($this->tempDir . '/foo');
    $filesystem->file($this->tempDir . '/foo/1.txt')->put('test');
    @mkdir($this->tempDir . '/foo/foo-2');
    $filesystem->file($this->tempDir . '/foo/foo-2/1.txt')->put('test');
    @mkdir($this->tempDir . '/bar');
    $filesystem->file($this->tempDir . '/bar/1.txt')->put('test');
    @mkdir($this->tempDir . '/bar/bar-2');
    $filesystem->file($this->tempDir . '/bar/bar-2/1.txt')->put('test');
    @mkdir($this->tempDir . '/zed');
    $filesystem->file($this->tempDir . '/zed/1.txt')->put('test');
    @mkdir($this->tempDir . '/zed/zed-2');
    $filesystem->file($this->tempDir . '/zed/zed-2/1.txt')->put('test');

    $filesystem = new Filesystem();
    $this->assertTrue(count($filesystem->directory($this->tempDir)->files(true)) == 7);
    
    $filesystem = new Filesystem();
    $this->assertTrue(count($filesystem->directory($this->tempDir)->files()) == 1);

    $filesystem = new Filesystem();
    $this->assertTrue(count($filesystem->directory($this->tempDir)->files(false)) == 1);
});

test('directory clean method', function (): void {
    @mkdir($this->tempDir . '/1');
    $filesystem = new Filesystem();
    $filesystem->file($this->tempDir . '/1/1.txt')->put('hello');

    $filesystem = new Filesystem();
    $this->assertTrue($filesystem->directory($this->tempDir . '/1')->clean());
    $this->assertFalse($filesystem->file($this->tempDir . '/1/1.txt')->exists());
});

test('filesystem glob method', function (): void {
    $filesystem = new Filesystem();
    $filesystem->file($this->tempDir . '/1.txt')->put('hello');
    $filesystem->file($this->tempDir . '/2.txt')->put('world');

    $glob = $filesystem->glob($this->tempDir . '/*.txt');
    $this->assertContains($this->tempDir . '/1.txt', $glob);
    $this->assertContains($this->tempDir . '/2.txt', $glob);

    $glob = $filesystem->glob($this->tempDir . '/*.html');
    $this->assertEquals(0, count($glob));
});

test('filesystem size method', function (): void {
    $filesystem = new Filesystem();
    $filesystem->file($this->tempDir . '/1.txt')->put('hello world');

    $this->assertEquals(11, $filesystem->file($this->tempDir . '/1.txt')->size());
});


test('direcotory size method', function (): void {
    $filesystem = new Filesystem();
    @mkdir($this->tempDir . '/1');
    @mkdir($this->tempDir . '/1/2');
    $filesystem->file($this->tempDir . '/1/1.txt')->put('hello world');
    $filesystem->file($this->tempDir . '/1/2.txt')->put('hello world');
    $filesystem->file($this->tempDir . '/1/2/1.txt')->put('hello world');
    $filesystem->file($this->tempDir . '/1/2/2.txt')->put('hello world');

    $this->assertEquals(44, $filesystem->directory($this->tempDir . '/1')->size());
});

test('file lastModified method', function (): void {
    $filesystem = new Filesystem();

    $filesystem->file($this->tempDir . '/1.txt')->put('hello world');
    $time = filemtime($this->tempDir . '/1.txt');

    $this->assertEquals($time, $filesystem->file($this->tempDir . '/1.txt')->lastModified());
});

test('file lastAccess method', function (): void {
    $filesystem = new Filesystem();

    $filesystem->file($this->tempDir . '/1.txt')->put('hello world');
    $time = fileatime($this->tempDir . '/1.txt');

    $this->assertEquals($time, $filesystem->file($this->tempDir . '/1.txt')->lastAccess());
});

test('file mimeType method', function (): void {
    $filesystem = new Filesystem();

    $filesystem->file($this->tempDir . '/1.txt')->put('hello world');

    $this->assertEquals('text/plain', $filesystem->file($this->tempDir . '/1.txt')->mimeType());
});

test('file type method', function (): void {
    $filesystem = new Filesystem();

    $filesystem->file($this->tempDir . '/1.txt')->put('hello world');

    $this->assertEquals('file', $filesystem->file($this->tempDir . '/1.txt')->type());
    $this->assertEquals('dir', $filesystem->file($this->tempDir)->type());
});

test('file extension method', function (): void {
    $filesystem = new Filesystem();

    $filesystem->file($this->tempDir . '/1.txt')->put('hello world');

    $this->assertEquals('txt', $filesystem->file($this->tempDir . '/1.txt')->extension());
});

test('file isEmpty method', function (): void {
    $filesystem = new Filesystem();

    $filesystem->file($this->tempDir . '/1.txt')->put('hello world');
    expect($filesystem->file($this->tempDir . '/1.txt')->isEmpty())->toBe(false);
    
    $filesystem->file($this->tempDir . '/2.txt')->put('');
    expect($filesystem->file($this->tempDir . '/2.txt')->isEmpty())->toBe(true);
});

test('directory isEmpty method', function (): void {
    $filesystem = new Filesystem();

    $filesystem->file($this->tempDir . '/1.txt')->put('hello world');    
    $filesystem->file($this->tempDir . '/2.txt')->put('');

    expect($filesystem->directory($this->tempDir)->isEmpty())->toBe(false);
});

test('file basename method', function (): void {
    $filesystem = new Filesystem();

    $filesystem->file($this->tempDir . '/1.txt')->put('hello world');

    $this->assertEquals('1.txt', $filesystem->file($this->tempDir . '/1.txt')->basename());
});


test('file name method', function (): void {
    $filesystem = new Filesystem();

    $filesystem->file($this->tempDir . '/1.txt')->put('hello world');

    $this->assertEquals('1', $filesystem->file($this->tempDir . '/1.txt')->name());
});

test('filesystem find method', function (): void {
    @mkdir($this->tempDir . '/1');

    $filesystem = new Filesystem();
    $filesystem->file($this->tempDir . '/1/1.txt')->put('hello world');

    $this->assertEquals(1, count(iterator_to_array($filesystem->find()->in($this->tempDir)->files()->name('*.txt'), false)));

    // alternative
    $this->assertEquals(1, count(iterator_to_array((new Filesystem)->find()->in($this->tempDir)->files()->name('*.txt'), false)));
});

test('directory path method', function (): void {
    $filesystem = new Filesystem();

    $this->assertEquals($this->tempDir . '/1.txt', $filesystem->file($this->tempDir . '/1.txt')->path());
    $this->assertEquals($this->tempDir, $filesystem->directory($this->tempDir)->path());
});

test('filesystem macro() method', function (): void {
    @mkdir($this->tempDir . '/1');
    $filesystem = new Filesystem();
    $filesystem->file($this->tempDir . '/1/1.txt')->put('hello world');
    $filesystem->file($this->tempDir . '/1/2.txt')->put('hello world');

    Filesystem::macro('countFiles', function($path) {
        return count(iterator_to_array($this->find()->in($path)->files(), false));
    });

    $filesystem = new Filesystem();
    $this->assertEquals(2, $filesystem->countFiles($this->tempDir . '/1'));
});
