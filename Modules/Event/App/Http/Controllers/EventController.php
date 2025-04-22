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
        $regions = \Modules\Regions\App\Models\Region::where('parameter' , 'm_region')->orWhere("parameter" , "m_area")->get();
        return view('event::create' , compact('regions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $event = new \Modules\Event\App\Models\Events();
            // $request->selected_fields = ["nomor_anggota","nama","no_hp","hadir","pembayaran","bukti_transfer","ukuran_kaos","type_lengan","ukuran_kaos_pendamping","type_lengan_pendamping","nik"];
           $store =  $request->all();
        //    remove $store->_token ; 
              unset($store['_token']);

            $event->fill($store);
            //if image exist, process to s3 and store the url
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->store('events', 's3');
                $event->image = \Storage::disk('s3')->url($path);
            }
            $event->save();
            return redirect()->route('event.index')->with('success', 'Event created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create event: ' . $e->getMessage());
        }
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
        $event = \Modules\Event\App\Models\Events::find($id);
        return view('event::edit' , compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $event = \Modules\Event\App\Models\Events::find($id);
        if (!$event) {
            return redirect()->route('event.index')->with('error', 'Event not found');
        }
        $event->update($request->all());
        return redirect()->route('event.index')->with('success', 'Event updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
