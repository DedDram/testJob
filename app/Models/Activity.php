<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Activity extends Model
{
    protected $fillable = ['name', 'parent_id'];

    /**
     * Get child activities (for nested structures).
     */
    public function children(): HasMany
    {
        return $this->hasMany(Activity::class, 'parent_id');
    }

    /**
     * Get parent activity.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'parent_id');
    }

    /**
     * Get the organizations associated with the activity.
     */
    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_activity', 'activity_id', 'organization_id');
    }

    /**
     * Scope a query to include only root activities (those without a parent).
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }


    public static function getAllActivitiesByName(string $name): Collection
    {
        $activity = self::where('name', $name)->first();

        if (!$activity) {
            return collect();
        }

        // Получить все дочерние активности
        return $activity->getAllChildren()->prepend($activity);
    }

    /**
     * Рекурсивно получить все дочерние активности.
     */
    public function getAllChildren(): Collection
    {
        $children = $this->children()->get();

        return $children->flatMap(function ($child) {
            return $child->getAllChildren()->prepend($child);
        });
    }
}
