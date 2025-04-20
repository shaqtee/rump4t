<?php

namespace Modules\Regions\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Regions\App\Models\Region;

class RegionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $regions = Region::paginate(10);   
        return view('regions::index' , compact('regions'));  
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('regions::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $region = new Region();
            $region->name = $request->name;
            $region->save();

            return redirect()->route('regions.index')->with('success', 'Region created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create region: ' . $e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('regions::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $region = Region::findOrFail($id);
        if (!$region) {
            return redirect()->route('regions.index')->with('error', 'Region not found.');
        }
        return view('regions::edit', compact('region'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $region = Region::findOrFail($id);
            if (!$region) {
                return redirect()->route('regions.index')->with('error', 'Region not found.');
            }
            $region->name = $request->name;
            $region->save();

            return redirect()->route('regions.index')->with('success', 'Region updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update region: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $region = Region::findOrFail($id);
            if (!$region) {
                return redirect()->route('regions.index')->with('error', 'Region not found.');
            }
            $region->delete();

            return redirect()->route('regions.index')->with('success', 'Region deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete region: ' . $e->getMessage());
        }
        //
    }
}
