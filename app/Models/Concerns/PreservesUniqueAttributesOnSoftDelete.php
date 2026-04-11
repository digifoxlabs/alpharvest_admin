<?php

namespace App\Models\Concerns;

use Illuminate\Validation\ValidationException;

trait PreservesUniqueAttributesOnSoftDelete
{
    abstract public function getUniqueSoftDeleteColumns(): array;

    public static function bootPreservesUniqueAttributesOnSoftDelete(): void
    {
        static::deleting(function ($model) {
            if (! method_exists($model, 'isForceDeleting') || $model->isForceDeleting()) {
                return;
            }

            $originals = [];
            $updates = [];

            foreach ($model->getUniqueSoftDeleteColumns() as $column) {
                $value = $model->getAttribute($column);

                if ($value === null || $value === '') {
                    continue;
                }

                $originals[$column] = $value;
                $updates[$column] = $model->softDeleteUniquePlaceholder($column, $value);
            }

            if ($originals === []) {
                return;
            }

            $updates['archived_unique_values'] = $originals;

            $model->forceFill($updates)->saveQuietly();
        });

        static::restoring(function ($model) {
            $originals = (array) $model->getAttribute('archived_unique_values');

            if ($originals === []) {
                return;
            }

            foreach ($originals as $column => $value) {
                $conflictExists = $model->newQuery()
                    ->where($column, $value)
                    ->whereKeyNot($model->getKey())
                    ->exists();

                if ($conflictExists) {
                    throw ValidationException::withMessages([
                        $column => "Cannot restore this record because {$column} is already in use.",
                    ]);
                }
            }

            $model->forceFill(array_merge($originals, [
                'archived_unique_values' => null,
            ]));
        });
    }

    protected function softDeleteUniquePlaceholder(string $column, mixed $value): string
    {
        $suffix = '__archived__' . $this->getKey() . '__' . now()->timestamp;

        return (string) $value . $suffix;
    }
}
