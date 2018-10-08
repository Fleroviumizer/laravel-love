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

namespace Cog\Contracts\Love\Reactant\ReactionSummary\Models;

use Cog\Contracts\Love\Reactant\Models\Reactant;

interface ReactionSummary
{
    public function getReactant(): Reactant;

    public function getTotalCount(): int;

    public function getTotalWeight(): int;
}
