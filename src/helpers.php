<?php

declare(strict_types=1);

namespace Glowy\Filesystem;

use Glowy\Filesystem\Filesystem;

if (! function_exists('filesystem')) {
    /**
     * Create a Filesystem instance.
     */
    function filesystem(): \Glowy\Filesystem\Filesystem
    {
        return new Filesystem();
    }
}
