<?php

/*
 * This file is part of Laravel Love.
 *
 * (c) Anton Komarev <a.komarev@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Cog\Contracts\Love\Reactable\Exceptions;

use RuntimeException;

class ReactableInvalid extends RuntimeException
{
    public static function notExists(string $type): self
    {
        return new static("[$type] class or morph map not found.");
    }

    public static function notImplementInterface(string $type): self
    {
        return new static("[{$type}] must implement `\Cog\Contracts\Love\Reactable\Models\Reactable` contract.");
    }
}
