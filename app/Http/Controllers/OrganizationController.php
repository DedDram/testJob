<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetNearbyOrganizationsRequest;
use App\Http\Requests\GetOrganizationIdRequest;
use App\Http\Requests\GetOrganizationsByActivityNameRequest;
use App\Http\Requests\GetOrganizationsByActivityRequest;
use App\Http\Requests\GetOrganizationsByBuildingRequest;
use App\Http\Requests\GetOrganizationsByNameRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\Organization;
/**
 * @OA\Info(
 *     title="API",
 *     version="1.0.0",
 *     description="This is the API documentation",
 *     @OA\Contact(
 *         email="support@example.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
* @OA\Schema(
 *     schema="PhoneNumber",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=466
    *     ),
 *     @OA\Property(
 *         property="phone_number",
 *         type="string",
 *         example="+10112852857"
    *     )
 * )
 * @OA\Schema(
 *     schema="Activity",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=3
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Свинина"
 *     ),
 *     @OA\Property(
 *         property="parent_id",
 *         type="integer",
 *         example=2
 *     )
 * )
 * @OA\Schema(
 *     schema="Organization",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=23
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Hahn Ltd"
 *     ),
 *     @OA\Property(
 *         property="building_id",
 *         type="integer",
 *         example=2
 *     ),
 *     @OA\Property(
 *         property="phone_numbers",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/PhoneNumber")
 *     ),
 *     @OA\Property(
 *         property="activities",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Activity")
 *     )
 * )
 * @OA\Schema(
 *      schema="OrganizationResponse",
 *      type="object",
 *      @OA\Property(property="id", type="integer"),
 *      @OA\Property(property="name", type="string"),
 *      @OA\Property(property="phoneNumbers", type="array", @OA\Items(type="string")),
 *      @OA\Property(property="activities", type="array", @OA\Items(type="string")),
 *  )
 *
 * @OA\Schema(
 *      schema="OrganizationResponse2",
 *      type="object",
 *      description="Response containing organization details",
 *      @OA\Property(
 *          property="data",
 *          type="array",
 *          description="List of organizations",
 *          @OA\Items(
 *              type="object",
 *              @OA\Property(
 *                  property="id",
 *                  type="integer",
 *                  description="Organization ID",
 *                  example=74
 *              ),
 *              @OA\Property(
 *                  property="name",
 *                  type="string",
 *                  description="Name of the organization",
 *                  example="Shanahan, Simonis and Wyman"
 *              ),
 *              @OA\Property(
 *                  property="building_id",
 *                  type="integer",
 *                  description="ID of the building associated with the organization",
 *                  example=28
 *              )
 *          )
 *      )
 *  )
 */


class OrganizationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/v1/buildings/{building_id}/organizations",
     *     tags={"Organizations"},
     *     summary="Get organizations in a specific building",
     *     @OA\Parameter(
     *         name="building_id",
     *         in="path",
     *         required=true,
     *         description="ID of the building",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of organizations",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Organization")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Organization not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={
     *         {"apiKey": {}}
     *     }
     * )
     */
    public function getOrganizationsByBuilding(GetOrganizationsByBuildingRequest $request): JsonResponse|AnonymousResourceCollection
    {
        $buildingId = $request->route('building_id');

        $organizations = Organization::getOrganizationsByBuildingId($buildingId);

        if ($organizations->isEmpty()) {
            return OrganizationResource::emptyResponse();
        }

        return OrganizationResource::collection($organizations);
    }

    /**
     * @OA\Get(
     *     path="/v1/activities/{activity_id}/organizations",
     *     tags={"Organizations"},
     *     summary="Get organizations by activity",
     *     description="Returns a list of organizations associated with a specific activity.",
     *     @OA\Parameter(
     *         name="activity_id",
     *         in="path",
     *         required=true,
     *         description="ID of the activity",
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of organizations",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Organization")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Organization not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={
     *         {"apiKey": {}}
     *     }
     * )
     */

    public function getOrganizationsByActivity(GetOrganizationsByActivityRequest $request): JsonResponse|AnonymousResourceCollection
    {
        $activityId = $request->route('activity_id');

        $organizations = Organization::getOrganizationsByActivityId($activityId);

        if ($organizations->isEmpty()) {
            return OrganizationResource::emptyResponse();
        }

        return OrganizationResource::collection($organizations);
    }

    /**
     * @OA\Get(
     *     path="/v1/organizations/nearby",
     *     tags={"Organizations"},
     *     summary="Get organizations within a specified radius from a point",
     *     @OA\Parameter(
     *         name="latitude",
     *         in="query",
     *         required=true,
     *         description="Latitude of the center point",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="longitude",
     *         in="query",
     *         required=true,
     *         description="Longitude of the center point",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="radius",
     *         in="query",
     *         required=true,
     *         description="Radius in kilometers",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of nearby organizations",
     *         @OA\JsonContent(ref="#/components/schemas/OrganizationResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Organization not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={
     *         {"apiKey": {}}
     *     }
     * )
     */
    public function getNearbyOrganizations(GetNearbyOrganizationsRequest $request): JsonResponse|AnonymousResourceCollection
    {
        $latitude = $request->validated('latitude');
        $longitude = $request->validated('longitude');
        $radius = $request->validated('radius');

        $organizations = Organization::getNearbyOrganizationsByGeo($latitude, $longitude, $radius);

        if ($organizations->isEmpty()) {
            return OrganizationResource::emptyResponse();
        }

        return OrganizationResource::collection($organizations);
    }

    /**
     * @OA\Get(
     *     path="/v1/organizations/{id}",
     *     tags={"Organizations"},
     *     summary="Get organization by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the organization",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Organization details",
     *         @OA\JsonContent(ref="#/components/schemas/OrganizationResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Organization not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={
     *         {"apiKey": {}}
     *     }
     * )
     */
    public function getOrganizationById(GetOrganizationIdRequest $request): JsonResponse|OrganizationResource
    {
        $id = $request->route('id');

        $organization = Organization::with('phoneNumbers', 'activities')->find($id);

        if (!$organization) {
            return OrganizationResource::emptyResponse();
        }

        return new OrganizationResource($organization);
    }

    /**
     * @OA\Get(
     *     path="/v1/organizations/by-activity-name",
     *     tags={"Organizations"},
     *     summary="Get organizations by activity name",
     *     description="Search for organizations by a specific activity name, including its nested activities.",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Name of the activity to search for",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of organizations",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/OrganizationResponse2")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Organization not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={
     *         {"apiKey": {}}
     *     }
     * )
     */
    public function getOrganizationsByActivityName(GetOrganizationsByActivityNameRequest $request): JsonResponse|AnonymousResourceCollection
    {
        $activityName = $request->validated('name');

        // Получаем все виды деятельности по названию и их дочерние
        $activities = Activity::getAllActivitiesByName($activityName);

        // Получаем все организации, связанные с найденными видами деятельности
        $organizations = Organization::getOrganizationsByActivity($activities);

        if ($organizations->isEmpty()) {
            return OrganizationResource::emptyResponse();
        }

        return OrganizationResource::collection($organizations);
    }

    /**
     * @OA\Get(
     *     path="/v1/organizations/by-name",
     *     tags={"Organizations"},
     *     summary="Search organization by name",
     *     description="Fetches an organization along with its phone numbers and activities by name.",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Name of the organization to search for.",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Organization found",
     *         @OA\JsonContent(ref="#/components/schemas/OrganizationResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Organization not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={
     *         {"apiKey": {}}
     *     }
     * )
     */
    public function getOrganizationsByName(GetOrganizationsByNameRequest $request): JsonResponse|OrganizationResource
    {
        $name = $request->validated('name');
        $organization = Organization::with('phoneNumbers', 'activities')
            ->where('name', $name)
            ->first();

        if (!$organization) {
            return OrganizationResource::emptyResponse();
        }

        return new OrganizationResource($organization);
    }
}
