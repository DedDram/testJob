<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as CollectionSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    protected $fillable = ['name', 'building_id'];

    /**
     * @return HasMany
     */
    public function phoneNumbers(): HasMany
    {
        return $this->hasMany(PhoneNumber::class);
    }

    /**
     * @return BelongsToMany
     */
    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'organization_activity');
    }

    /**
     * @return BelongsTo
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Найти организации находящихся в конкретном здании
     * @param int $buildingId
     * @return Collection
     */
    public static function getOrganizationsByBuildingId(int $buildingId): Collection
    {
        return self::with('phoneNumbers', 'activities')
            ->where('building_id', $buildingId)
            ->get();
    }

    /**
     *  Найти организации по координатам по виду деятельности ID
     * @param int $activityId
     * @return Collection
     */
    public static function getOrganizationsByActivityId(int $activityId): Collection
    {
        return self::with('phoneNumbers', 'activities')
            ->whereHas('activities', function ($query) use ($activityId) {
                $query->where('activities.id', $activityId);
            })
            ->get();
    }

    /**
     *  Найти организации по координатам по виду деятельности
     * @param CollectionSupport $activities
     * @return Collection
     */
    public static function getOrganizationsByActivity(CollectionSupport $activities): Collection
    {
        return self::whereHas('activities', function ($query) use ($activities) {
            $query->whereIn('activities.id', $activities->pluck('id'));
        })->get();
    }

    /**
     *  Найти организации по координатам с учетом радиуса
     * @param float $latitude
     * @param float $longitude
     * @param float $radius
     * @return \Illuminate\Support\Collection|Collection
     */
    public static function getNearbyOrganizationsByGeo(float $latitude, float $longitude, float $radius): \Illuminate\Support\Collection|Collection
    {
        $buildings = Building::selectRaw("*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance", [$latitude, $longitude, $latitude])
            ->get();

        $nearbyBuildings = $buildings->filter(function ($building) use ($radius) {
            return $building->distance < $radius;
        });

        if ($nearbyBuildings->isEmpty()) {
            return collect();
        }

        return self::whereIn('building_id', $nearbyBuildings->pluck('id'))
            ->with('phoneNumbers', 'activities')
            ->get();
    }

}
