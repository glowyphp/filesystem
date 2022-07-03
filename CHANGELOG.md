<a name="5.0.0"></a>
# [5.0.0](https://github.com/glowyphp/filesystem) (2022-07-03)
* All Helpers functions are placed into the Glowy/Filesystem namespace.
* Use union types.

<a name="4.0.0"></a>
# [4.0.0](https://github.com/glowyphp/filesystem) (2021-07-02)
* Moving to PHP 8.1

<a name="3.2.0"></a>
# [3.2.0](https://github.com/glowyphp/filesystem) (2022-06-06)
* Added new method `isEmpty` for files.
* Added new method `isEmpty` for directories.

<a name="3.1.0"></a>
# [3.1.0](https://github.com/glowyphp/filesystem) (2022-06-03)
* Added new method `directories`.
* Added new method `files`.

<a name="3.0.0"></a>
# [3.0.0](https://github.com/glowyphp/filesystem) (2021-12-23)
* Released under Glowy PHP Organization
* Add PHP 8.1 support
* Updated dependencies.

<a name="2.2.0"></a>
# [2.2.0](https://github.com/glowyphp/filesystem) (2021-09-28)
* add `replace` method for File.
* add `sharedGet` method for File.
* add ability to `get` method for File.

<a name="2.1.0"></a>
# [2.1.0](https://github.com/glowyphp/filesystem) (2021-08-06)
* add `ensureExists` method for Directory.

<a name="2.0.0"></a>
# [2.0.0](https://github.com/glowyphp/filesystem) (2021-02-19)
* Move to PHP 7.4
* Fix tests
* Code refactoring

<a name="1.1.0"></a>
# [1.1.0](https://github.com/glowyphp/filesystem) (2020-12-05)
* add ability to extend Filesystem class with Macros.

    ```php
    use Glowy\Filesystem\Filesystem;
    use Glowy\Macroable\Macroable;

    Filesystem::macro('countFiles', function($path) {
        return count(iterator_to_array($this->find()->in($path)->files(), false));
    });

    $filesytem = new Filesystem();

    echo $filesytem->countFiles('/directory');
    ```
* improve tests for directory `create()` method.

<a name="1.0.1"></a>
# [1.0.1](https://github.com/glowyphp/filesystem) (2020-10-17)
* fix Directory copy() method.

<a name="1.0.0"></a>
# [1.0.0](https://github.com/glowyphp/filesystem) (2020-10-15)
* Initial release
