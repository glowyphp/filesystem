<h1 align="center">Filesystem Component</h1>
<p align="center">
Filesystem Component provide a fluent, object-oriented interface for working with filesystem.
</p>
<p align="center">
<a href="https://github.com/atomastic/filesystem/releases"><img alt="Version" src="https://img.shields.io/github/release/atomastic/filesystem.svg?label=version&color=green"></a> <a href="https://github.com/atomastic/filesystem"><img src="https://img.shields.io/badge/license-MIT-blue.svg?color=green" alt="License"></a> <a href="https://github.com/atomastic/filesystem"><img src="https://img.shields.io/github/downloads/atomastic/filesystem/total.svg?color=green" alt="Total downloads"></a> <img src="https://github.com/atomastic/filesystem/workflows/Static%20Analysis/badge.svg?branch=dev"> <img src="https://github.com/atomastic/filesystem/workflows/Tests/badge.svg">
  <a href="https://app.codacy.com/gh/atomastic/filesystem?utm_source=github.com&utm_medium=referral&utm_content=atomastic/filesystem&utm_campaign=Badge_Grade"><img src="https://api.codacy.com/project/badge/Grade/990baa96ada542f9ae21a41c2a25ddf9"></a> <a href="https://codeclimate.com/github/atomastic/filesystem/maintainability"><img src="https://api.codeclimate.com/v1/badges/ecbddff212c0e3a61216/maintainability" /></a> <a href="https://app.fossa.com/projects/git%2Bgithub.com%2Fatomastic%2Ffilesystem?ref=badge_shield" alt="FOSSA Status"><img src="https://app.fossa.com/api/projects/git%2Bgithub.com%2Fatomastic%2Ffilesystem.svg?type=shield"/></a>
</p>

<br>

* [Installation](#installation)
* [Usage](#usage)
* [Methods](#methods)
  - [Filesystem](#filesystem)
  - [File](#file)
  - [Directory](#directory)
* [Tests](#tests)
* [License](#license)

### Installation

#### With [Composer](https://getcomposer.org)

```
composer require atomastic/filesystem
```

### Usage

```php
use Atomastic\Filesystem\Filesystem;

// Create a Filesystem instance.
$filesystem = new Filesystem();

// Using global helper function filesystem()
$filesytem = filesystem();
```

### Methods

#### Filesystem

| Method | Description |
|---|---|
| <a href="#filesytem_find">`find()`</a> | Create a Finder instance. |
| <a href="#filesytem_file">`file()`</a> | Create a File instance. |
| <a href="#filesytem_directory">`directory()`</a> | Create a Directory instance. |
| <a href="#filesytem_isStream">`isStream()`</a> | Determine if the given path is a stream path. |
| <a href="#filesytem_isAbsolute">`isAbsolute()`</a> | Determine if the given path is absolute path. |
| <a href="#filesytem_isWindowsPath">`isWindowsPath()`</a> | Determine if the given path is a Windows path. |
| <a href="#filesytem_chmod">`chmod()`</a> | Get/Set UNIX mode of a file or directory. |
| <a href="#filesytem_glob">`glob()`</a> | Find path names matching a given pattern. |

#### Methods Details

#### File

| Method | Description |
|---|---|
| <a href="#file_put">`put()`</a> | Write the contents of a file. |
| <a href="#file_get">`get()`</a> | Get the contents of a file. |
| <a href="#file_prepend">`prepend()`</a> | Prepend to a file. |
| <a href="#file_append">`append()`</a> | Append to a file. |
| <a href="#file_delete">`delete()`</a> | Delete the file at a given path. |
| <a href="#file_exists">`exists()`</a> | Checks the existence of file and returns false if any of them is missing. |
| <a href="#file_lastModified">`lastModified()`</a> | Get the file's last modification time. |
| <a href="#file_lastAccess">`lastAccess()`</a> | Get the file's last access time. |
| <a href="#file_mimeType">`mimeType()`</a> | Get the mime-type of a given file. |
| <a href="#file_type">`type()`</a> | Get the file type of a given file. |
| <a href="#file_extension">`extension()`</a> | Get the file extension from a file path. |
| <a href="#file_basename">`basename()`</a> | Get the trailing name component from a file path. |
| <a href="#file_name">`name()`</a> | Get the file name from a file path. |
| <a href="#file_path">`path()`</a> | Return current path. |
| <a href="#file_copy">`copy()`</a> | Copy a file to a new location. |
| <a href="#file_size">`size()`</a> | Gets file size in bytes. |
| <a href="#file_hash">`hash()`</a> | Get the MD5 hash of the file at the given path. |
| <a href="#file_isReadable">`isReadable()`</a> | Determine if the given path is readable. |
| <a href="#file_isWritable">`isWritable()`</a> | Determine if the given path is writable. |
| <a href="#file_isFile">`isFile()`</a> | Determine if the given path is a regular file. |

#### Methods Details

#### Directory

| Method | Description |
|---|---|
| <a href="#filesytem_directory">`find()`</a> | Create a Finder instance. |

#### Methods Details

### Tests

Run tests

```
./vendor/bin/pest
```

### License
[The MIT License (MIT)](https://github.com/atomastic/filesytem/blob/master/LICENSE.txt)
Copyright (c) 2020 [Sergey Romanenko](https://github.com/Awilum)
