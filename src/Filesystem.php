<?php

declare(strict_types=1);

namespace Atomastic\Filesystem;

use function file_exists;
use function file_put_contents;
use function is_file;
use function unlink;

use const LOCK_EX;

class Filesystem
{
    /**
     * Determine if the given path is a regular file.
     *
     * @param  string $path Path to the file.
     *
     * @return bool Returns TRUE if the filename exists and is a regular file, FALSE otherwise.
     */
    public function isFile(string $path): bool
    {
        return is_file($path);
    }

    /**
     * Checks the existence of files or directories and returns false if any of them is missing.
     *
     * @param string|string[] $paths A path, or an array of paths to check.
     *
     * @return bool Returns true or false if any of them is missing.
     */
    public function exists($paths): bool
    {
        foreach ((array) $paths as $path) {
            if (! file_exists($path)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Write the contents of a file.
     *
     * @param  string $path     Path to the file where to write the data.
     * @param  string $contents The data to write.
     * @param  bool   $lock     Acquire an exclusive lock on the file while proceeding to the writing.
     *
     * @return int|bool Returns the number of bytes that were written to the file, or FALSE on failure.
     */
    public function put(string $path, string $contents, bool $lock = false)
    {
        return file_put_contents($path, $contents, $lock ? LOCK_EX : 0);
    }

    /**
     * Delete the file at a given path.
     *
     * @param string|string[] $paths A path, or an array of paths to delete.
     *
     * @return bool Returns true or false if any of them is failure.
     */
    public function delete($paths): bool
    {
        $result = true;

        foreach ((array) $paths as $path) {
            try {
                if (! @unlink($path)) {
                    $result = false;
                }
            } catch (IOException $e) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * Get the MD5 hash of the file at the given path.
     *
     * @param  string $path The path.
     *
     * @return string Returns a string on success, FALSE otherwise.
     */
    public function hash(string $path, bool $rawOutput = false): string
    {
        return md5_file($path, $rawOutput);
    }
}
