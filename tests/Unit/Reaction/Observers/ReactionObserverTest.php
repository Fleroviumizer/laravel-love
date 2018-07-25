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

namespace Cog\Tests\Laravel\Love\Unit\Reaction\Observers;

use Cog\Laravel\Love\Reactant\Models\Reactant;
use Cog\Laravel\Love\Reactant\ReactionCounter\Models\ReactionCounter;
use Cog\Laravel\Love\Reactant\ReactionSummary\Models\ReactionSummary;
use Cog\Laravel\Love\Reaction\Models\Reaction;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;
use Cog\Tests\Laravel\Love\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReactionObserverTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_increment_reactions_count_on_reaction_created()
    {
        $reactant = factory(Reactant::class)->create();
        $reactionType = factory(ReactionType::class)->create();
        $counter = factory(ReactionCounter::class)->create([
            'reaction_type_id' => $reactionType->getKey(),
            'reactant_id' => $reactant->getKey(),
        ]);

        factory(Reaction::class)->create([
            'reaction_type_id' => $reactionType->getKey(),
            'reactant_id' => $reactant->getKey(),
        ]);

        $this->assertSame(1, $counter->fresh()->count);
    }

    /** @test */
    public function it_decrement_reactions_count_on_reaction_deleted()
    {
        $reactant = factory(Reactant::class)->create();
        $reactionType = factory(ReactionType::class)->create();
        $counter = factory(ReactionCounter::class)->create([
            'reaction_type_id' => $reactionType->getKey(),
            'reactant_id' => $reactant->getKey(),
        ]);
        $reactions = factory(Reaction::class, 2)->create([
            'reaction_type_id' => $reactionType->getKey(),
            'reactant_id' => $reactant->getKey(),
        ]);

        $reactions->get(0)->delete();

        $this->assertSame(1, $counter->fresh()->count);
    }

    /** @test */
    public function it_increment_reactions_total_count_on_reaction_created()
    {
        $reactant = factory(Reactant::class)->create();
        $summary = factory(ReactionSummary::class)->create([
            'reactant_id' => $reactant->getKey(),
        ]);

        factory(Reaction::class)->create([
            'reactant_id' => $reactant->getKey(),
        ]);

        $this->assertSame(1, $summary->fresh()->total_count);
    }

    /** @test */
    public function it_decrement_reactions_total_count_on_reaction_deleted()
    {
        $reactant = factory(Reactant::class)->create();
        $summary = factory(ReactionSummary::class)->create([
            'reactant_id' => $reactant->getKey(),
        ]);
        $reactions = factory(Reaction::class, 2)->create([
            'reactant_id' => $reactant->getKey(),
        ]);

        $reactions->get(0)->delete();

        $this->assertSame(1, $summary->fresh()->total_count);
    }

    /** @test */
    public function it_increment_reactions_total_weight_on_reaction_created()
    {
        $reactionType = factory(ReactionType::class)->create([
            'weight' => 4,
        ]);
        $reactant = factory(Reactant::class)->create();
        $summary = factory(ReactionSummary::class)->create([
            'reactant_id' => $reactant->getKey(),
        ]);

        factory(Reaction::class, 2)->create([
            'reaction_type_id' => $reactionType->getKey(),
            'reactant_id' => $reactant->getKey(),
        ]);

        $this->assertSame(8, $summary->fresh()->total_weight);
    }

    /** @test */
    public function it_decrement_reactions_total_weight_on_reaction_deleted()
    {
        $reactionType = factory(ReactionType::class)->create([
            'weight' => 4,
        ]);
        $reactant = factory(Reactant::class)->create();
        $summary = factory(ReactionSummary::class)->create([
            'reactant_id' => $reactant->getKey(),
        ]);
        $reactions = factory(Reaction::class, 3)->create([
            'reaction_type_id' => $reactionType->getKey(),
            'reactant_id' => $reactant->getKey(),
        ]);

        $reactions->get(0)->delete();

        $this->assertSame(8, $summary->fresh()->total_weight);
    }

    /** @test */
    public function it_increment_reactions_total_weight_on_reaction_with_negative_weight_created()
    {
        $reactionType = factory(ReactionType::class)->create([
            'weight' => -4,
        ]);
        $reactant = factory(Reactant::class)->create();
        $summary = factory(ReactionSummary::class)->create([
            'reactant_id' => $reactant->getKey(),
        ]);

        factory(Reaction::class, 2)->create([
            'reaction_type_id' => $reactionType->getKey(),
            'reactant_id' => $reactant->getKey(),
        ]);

        $this->assertSame(-8, $summary->fresh()->total_weight);
    }

    /** @test */
    public function it_decrement_reactions_total_weight_on_reaction_with_negative_weight_deleted()
    {
        $reactionType = factory(ReactionType::class)->create([
            'weight' => -4,
        ]);
        $reactant = factory(Reactant::class)->create();
        $summary = factory(ReactionSummary::class)->create([
            'reactant_id' => $reactant->getKey(),
        ]);
        $reactions = factory(Reaction::class, 3)->create([
            'reaction_type_id' => $reactionType->getKey(),
            'reactant_id' => $reactant->getKey(),
        ]);

        $reactions->get(0)->delete();

        $this->assertSame(-8, $summary->fresh()->total_weight);
    }
}
