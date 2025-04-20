<?php

namespace Modules\Event\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $events = \Modules\Event\App\Models\Events::all();
        $events = \Modules\Event\App\Models\Events::paginate(10);   
        return view('event::index' , compact('events'));    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('event::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $event = \Modules\Event\App\Models\Events::find($id);
        if (!$event) {
            return redirect()->route('event.index')->with('error', 'Event not found');
        }
        return view('event::show' , compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('event::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
