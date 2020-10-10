<?php

declare(strict_types=1);

use Atomastic\Filesystem\Filesystem;

beforeEach(static function (): void {
    $filesytem = new Filesystem();
});

test('test put() exists() delete() methods', function (): void {
    $filesytem = new Filesystem();

    $this->assertEquals(4, $filesytem->put(__DIR__ . '/filesystem/1.txt', 'test'));
    $this->assertEquals(4, $filesytem->put(__DIR__ . '/filesystem/3.txt', 'test'));
    $this->assertEquals(4, $filesytem->put(__DIR__ . '/filesystem/4.txt', 'test'));
    $this->assertTrue($filesytem->exists(__DIR__ . '/filesystem/1.txt'));
    $this->assertTrue($filesytem->exists([__DIR__ . '/filesystem/1.txt']));
    $this->assertFalse($filesytem->exists([
        __DIR__ . '/filesystem/1.txt',
        __DIR__ . '/test/2.txt',
    ]));
    $this->assertFalse($filesytem->exists(__DIR__ . '/filesystem/2.txt'));
    $this->assertTrue($filesytem->delete(__DIR__ . '/filesystem/1.txt'));
    $this->assertTrue($filesytem->delete([
        __DIR__ . '/filesystem/3.txt',
        __DIR__ . '/filesystem/4.txt',
    ]));
});
