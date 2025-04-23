<?php

namespace Modules\NewsAdmin\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\News\App\Http\Controllers\NewsController;
use Modules\NewsAdmin\App\Models\NewsAdmin;

class NewsAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = NewsAdmin::join('users', 't_news.author_id', '=', 'users.id')
            ->select('t_news.*', 'users.name as author_name' , 'users.image as author_image')
            ->orderBy('created_at', 'desc')
            ->get();

            // dd($news);

            
            foreach ($news as $item) {
                $short_id = substr($item->id, -5);
                // lowercase the id
                $short_id = strtolower($short_id);
                $item->short_id = $short_id;
            }

        return view('newsadmin::index' , compact('news'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $regions = \Modules\Regions\App\Models\Region::where("parameter" , "m_area")->orWhere("parameter" , "m_region")->get();
        return view('newsadmin::create' , compact('regions')); ;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {

        //debug validation

        // dd($request->all());
        try {
            $news = new NewsAdmin();
            $news->title = $request->input('title');
            $news->content = $request->input('description');
            $news->is_published = $request->input('is_published', true);
            if($request->has('featured') && $request->featured == true){
                $news->featured = true;
            }else{
                $news->featured = false;
            }
            if($request->region_id == ""){
                $news->region_id = null;
            }else{
            $news->region_id = $request->input('region_id');
            }
            
            $news->author_id = auth()->id();
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('newsimage', 's3');
                $news->image = $path;
            
            } else {
                dd('no image');
                $news->image = null; // or set a default value if needed
            }
            
            $news->save();
        } catch (\Exception $e) {
        
            return redirect()->back()->withErrors(['error' => 'Failed to create news: ' . $e->getMessage()]);
        }

        return redirect()->route('news-admin.index')->with('success', 'News created successfully.');
        
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('newsadmin::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $news = NewsAdmin::findOrFail($id);
        $regions = \Modules\Regions\App\Models\Region::where("parameter" , "m_area")->orWhere("parameter" , "m_region")->get();
        // dd($news);
        if ($news->image) {
            $news->image = \Storage::disk('s3')->url($news->image);
        } else {
            // $news->image = null; // or set a default value if needed
        }
        // $news->image = \Storage::disk('s3')->url($news->image);
        return view('newsadmin::edit' , compact('news' , 'regions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // update news
        $news = NewsAdmin::findOrFail($id);
        $news->title = $request->input('title');
        $news->content = $request->input('content');
        $news->is_published = $request->input('is_published', true);
        if($request->has('featured') && $request->featured == true){
            $news->featured = true;
        }else{
            $news->featured = false;
        }
        if($request->region_id == ""){
            $news->region_id = null;
        }else{
        $news->region_id = $request->input('region_id');
        }
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('newsimage', 's3');
            $news->image = $path;
        } else {
            // dd('no image');
            // $news->image = null; // or set a default value if needed
        }
        $news->save();
        return redirect()->route('news-admin.index')->with('success', 'News updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $news = NewsAdmin::findOrFail($id);
        $news->delete();
        return redirect()->route('news-admin.index')->with('success', 'News deleted successfully.');
    }
}
