<?php

namespace Modules\Groups\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\User;
use App\Models\Group;
use App\Models\SmallGroupUser;
use App\Exceptions\Handler;
use App\Services\Helpers\Helper;
use Modules\Masters\App\Models\MasterCity;
use Modules\Groups\App\Models\Group as SmallGroup;
use Modules\Community\App\Models\MembersCommonity;

class PostingController extends Controller
{
    public function __construct(
        protected SmallGroup $model,
        protected SmallGroupUser $smallGroupUser, 
        protected Helper $helper, 
        protected MasterCity $city, 
        protected Handler $handler, 
        protected User $users, 
        protected MembersCommonity $members, 
        protected Group $groups
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index_posting(Request $request)
    {
        return response()->json([
            "status" => "success",
            "data" => "index posting"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('groups::create');
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
        return view('groups::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('groups::edit');
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
