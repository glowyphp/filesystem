<?php

declare(strict_types=1);

namespace Atomastic\Filesystem;

use ErrorException as IOException;
use FilesystemIterator;

use function chmod;
use function copy;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function fileperms;
use function is_dir;
use function is_executable;
use function is_file;
use function is_link;
use function is_readable;
use function is_writable;
use function md5_file;
use function preg_match;
use function rmdir;
use function sprintf;
use function strpos;
use function substr;
use function unlink;

use const FILE_APPEND;
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
     * Determine if the given path is a directory.
     *
     * @param  string $path Path to check.
     *
     * @return bool Returns TRUE if the given path exists and is a directory, FALSE otherwise.
     */
    public function isDirectory(string $path): bool
    {
        return is_dir($path);
    }

    /**
     * Determine if the given path is readable.
     *
     * @param  string $path Path to check.
     *
     * @return bool Returns TRUE if the given path exists and is readable, FALSE otherwise.
     */
    public function isReadable(string $path): bool
    {
        return is_readable($path);
    }

    /**
     * Determine if the given path is writable.
     *
     * @param  string $path Path to check.
     *
     * @return bool Returns TRUE if the given path exists and is writable, FALSE otherwise.
     */
    public function isWritable(string $path): bool
    {
        return is_writable($path);
    }

    /**
     * Determine if the given path is a stream path.
     *
     * @param  string $path Path to check.
     *
     * @return bool Returns TRUE if the given path is stream path, FALSE otherwise.
     */
    public function isStream(string $path): bool
    {
        return strpos($path, '://') !== false;
    }

   /**
    * Determine if the given path is absolute path.
    *
    * @param  string $path Path to check.
    *
    * @return bool Returns TRUE if the given path is absolute path, FALSE otherwise.
    */
    public function isAbsolute(string $path): bool
    {
        return (bool) preg_match('#([a-z]:)?[/\\\\]|[a-z][a-z0-9+.-]*://#Ai', $path);
    }

    /**
     * Returns true if the File is symbolic link.
     *
     * @param  string $path Path to check.
     *
     * @return bool Returns TRUE if the given path is symbolic link, FALSE otherwise.
     */
    public function isLink(string $path): bool
    {
        return is_link($path);
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
     * @param  string $path Path to the file where to write the data.
     * @param  string $data The data to write.
     * @param  bool   $lock Acquire an exclusive lock on the file while proceeding to the writing.
     *
     * @return int|bool Returns the number of bytes that were written to the file, or FALSE on failure.
     */
    public function put(string $path, string $data, bool $lock = false)
    {
        return file_put_contents($path, $data, $lock ? LOCK_EX : 0);
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

    /**
     * Get the contents of a file.
     *
     * @param string $path The path to the file.
     *
     * @return string|false The file contents or false on failure.
     */
    public static function get(string $path)
    {
        $contents = file_get_contents($path);

        if ($contents === false) {
            return false;
        }

        return $contents;
    }

    /**
     * Prepend to a file.
     *
     * @param  string $path Path to the file where to write the data.
     * @param  string $data The data to write.
     *
     * @return int|bool Returns the number of bytes that were written to the file, or FALSE on failure.
     */
    public function prepend(string $path, string $data)
    {
        if ($this->exists($path)) {
            return $this->put($path, $data . $this->get($path));
        }

        return $this->put($path, $data);
    }

    /**
     * Append to a file.
     *
     * @param  string $path Path to the file where to write the data.
     * @param  string $data The data to write.
     *
     * @return int|bool Returns the number of bytes that were written to the file, or FALSE on failure.
     */
    public function append(string $path, string $data)
    {
        return file_put_contents($path, $data, FILE_APPEND);
    }

    /**
     * Get/Set UNIX mode of a file or directory.
     *
     * @param  string   $path The path to the file or directory.
     * @param  int|null $mode The mode parameter consists of three octal number components
     *                        specifying access restrictions for the owner, the user group
     *                        in which the owner is in, and to everybody else in this order.
     *
     * @return mixed
     */
    public function chmod(string $path, ?int $mode = null)
    {
        if ($mode) {
            return chmod($path, $mode);
        }

        return substr(sprintf('%o', fileperms($path)), -4);
    }

    /**
     * Find path names matching a given pattern.
     *
     * @param  string  $pattern The pattern.
     * @param  int     $flags   Valid flags.
     *
     * @return array Returns an array containing the matched files/directories, an empty array if no file matched.
     */
    public function glob(string $pattern, int $flags = 0): array
    {
        return glob($pattern, $flags);
    }

    /**
     * Copy a file to a new location.
     *
     * @param  string $path        Path to the source file.
     * @param  string $destination The destination path.
     *                             If the destination file already exists, it will be overwritten.
     *
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function copy(string $path, string $destination): bool
    {
        return copy($path, $destination);
    }

    /**
     * Delete a directory.
     *
     * @param  string $directory Directory to delete.
     * @param  bool   $preserve  The directory itself may be optionally preserved.
     *
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function deleteDirectory(string $directory, bool $preserve = false): bool
    {
        if (! $this->isDirectory($directory)) {
            return false;
        }

        foreach (new FilesystemIterator($directory) as $item) {
            if ($item->isDir() && ! $item->isLink()) {
                $this->deleteDirectory($item->getPathname());
            } else {
                $this->delete($item->getPathname());
            }
        }

        if ($preserve === false) {
            @rmdir($directory);
        }

        return true;
    }

    /**
     * Empty the specified directory of all files and directories.
     *
     * @param  string $directory Directory to cleanup.
     *
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function cleanDirectory($directory): bool
    {
        return $this->deleteDirectory($directory, true);
    }
}
