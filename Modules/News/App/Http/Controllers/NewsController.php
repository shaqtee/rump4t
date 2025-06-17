<?php

namespace Modules\News\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ApiResponse;
use App\Services\Helpers\Helper;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\News\App\Models\Newsfeed;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $apiResponse;
    protected $helper;
    protected $newsfeed;
    
     
    public function __construct(ApiResponse $apiResponse , Helper $helper , Newsfeed $newsfeed)
    {
        $this->apiResponse = $apiResponse;
        $this->helper = $helper;
        $this->newsfeed = $newsfeed;

    }
    public function index(Request $request) : JsonResponse
    {
        try {
            $news = Newsfeed::query(); ; 
            if(isset($request->region ) && $request->region != null){ 
                $regionId = Auth::user()->region ;
                $news = $news->where('region_id', $regionId)->orderBy('created_at', 'desc');

            }
            if($request->has('featured') && $request->featured == true){
                $news = $news->where('featured', true);
            }
            $news = $news->orderBy('created_at', 'desc')->get();

            $news->map(function($item){
                if ($item->image) {
                    $item->image = \Storage::disk('s3')->url($item->image);
                } else {
                    $item->image = null;
                }
            
            });
            
             return $this->apiResponse->success($news);


        }catch(\Exception $e){
            return $this->apiResponse->error($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('news::create');
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
        return view('news::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('news::edit');
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
