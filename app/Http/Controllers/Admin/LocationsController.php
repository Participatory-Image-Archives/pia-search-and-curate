<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Location\BulkDestroyLocation;
use App\Http\Requests\Admin\Location\DestroyLocation;
use App\Http\Requests\Admin\Location\IndexLocation;
use App\Http\Requests\Admin\Location\StoreLocation;
use App\Http\Requests\Admin\Location\UpdateLocation;
use App\Models\Location;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LocationsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexLocation $request
     * @return array|Factory|View
     */
    public function index(IndexLocation $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Location::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'label', 'geonames_id', 'geonames_url', 'latitude', 'longitude', 'place_id'],

            // set columns to searchIn
            ['id', 'label', 'geonames_url']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.location.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.location.create');

        return view('admin.location.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreLocation $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreLocation $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the Location
        $location = Location::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/locations'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/locations');
    }

    /**
     * Display the specified resource.
     *
     * @param Location $location
     * @throws AuthorizationException
     * @return void
     */
    public function show(Location $location)
    {
        $this->authorize('admin.location.show', $location);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Location $location
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Location $location)
    {
        $this->authorize('admin.location.edit', $location);


        return view('admin.location.edit', [
            'location_id' => $location,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateLocation $request
     * @param Location $location
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateLocation $request, Location $location)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Location
        $location->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/locations'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/locations');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyLocation $request
     * @param Location $location
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyLocation $request, Location $location)
    {
        $location->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyLocation $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyLocation $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Location::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
