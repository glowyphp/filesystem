<h1 align="center">Filesytem Component</h1>
<p align="center">
Filesystem Component provide a fluent, object-oriented interface for working with filesystem.
</p>
<p align="center">
<a href="https://github.com/atomastic/filesytem/releases"><img alt="Version" src="https://img.shields.io/github/release/atomastic/filesytem.svg?label=version&color=green"></a> <a href="https://github.com/atomastic/filesytem"><img src="https://img.shields.io/badge/license-MIT-blue.svg?color=green" alt="License"></a> <a href="https://github.com/atomastic/filesytem"><img src="https://img.shields.io/github/downloads/atomastic/filesytem/total.svg?color=green" alt="Total downloads"></a> <img src="https://github.com/atomastic/filesytem/workflows/Static%20Analysis/badge.svg?branch=dev"> <img src="https://github.com/atomastic/filesytem/workflows/Tests/badge.svg">
  <a href="https://app.codacy.com/gh/atomastic/filesytem?utm_source=github.com&utm_medium=referral&utm_content=atomastic/filesytem&utm_campaign=Badge_Grade_Dashboard"><img src="https://api.codacy.com/project/badge/Grade/72b4dc84c20145e1b77dc0004a3c8e3d"></a> <a href="https://codeclimate.com/github/atomastic/filesytem/maintainability"><img src="https://api.codeclimate.com/v1/badges/4aff5282f051b4aebe22/maintainability" /></a> <a href="https://app.fossa.com/projects/git%2Bgithub.com%2Fatomastic%2Ffilesytem?ref=badge_shield" alt="FOSSA Status"><img src="https://app.fossa.com/api/projects/git%2Bgithub.com%2Fatomastic%2Ffilesytem.svg?type=shield"/></a>
</p>

<br>

* [Installation](#installation)
* [Usage](#usage)
* [Methods](#methods)
* [Tests](#tests)
* [License](#license)

### Installation

#### With [Composer](https://getcomposer.org)

```
composer require atomastic/filesytem
```

### Usage

```php
use Atomastic\Filesytem\Filesytem;

// Using public static method getInstance()
$filesytem = new Filesytem();

// Using global helper function filesytem() thats returns Filesytem::getInstance()
$filesytem = filesytem();
```

### Methods

| Method | Description |
|---|---|
| <a href="#filesytem_">`getInstance()`</a> | Gets the instance via lazy initialization (created on first usage) |

#### Methods Details

### Tests

Run tests

```
./vendor/bin/pest
```

### License
[The MIT License (MIT)](https://github.com/atomastic/filesytem/blob/master/LICENSE.txt)
Copyright (c) 2020 [Sergey Romanenko](https://github.com/Awilum)
