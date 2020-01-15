<?php

namespace Tests\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait RelationshipAssertions
{
    public function assertBelongsTo(Model $model, Model $related, string $relationship)
    {
        $this->assertEquals(BelongsTo::class, get_class($model->$relationship()));

        $model->$relationship()
            ->associate($related->id)
            ->save();

        $this->assertEquals($related->id, $model->fresh()->$relationship->id);
    }

    public function assertBelongsToMany(Model $model, Model $related, string $relationship)
    {
        $this->assertEquals(BelongsToMany::class, get_class($model->$relationship()));

        $this->assertCount(1, $model->fresh()->$relationship);
    }

    public function assertHasMany(Model $model, Model $related, string $relationship)
    {
        $this->assertEquals(HasMany::class, get_class($model->$relationship()));

        $this->assertCount(1, $model->fresh()->$relationship);
    }

    public function assertHasOne(Model $model, Model $related, string $relationship)
    {
        $this->assertEquals(HasOne::class, get_class($model->$relationship()));
        $this->assertEquals($related->fresh(), $model->fresh()->$relationship);
    }

    public function assertMorphMany(Model $model, string $relatedClass, string $relationship, int $startCount = 0)
    {
        $this->assertEquals(MorphMany::class, get_class($model->$relationship()));

        $this->assertCount($startCount, $model->fresh()->$relationship);

        $model->$relationship()
            ->create(factory($relatedClass)->make()->toArray())
            ->save();

        $this->assertCount(($startCount + 1), $model->fresh()->$relationship);
    }
}

?>