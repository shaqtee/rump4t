<?php

namespace Modules\MyGames\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\ApiResponse;
use Modules\MyGames\App\Models\LetsPlay;

class CanReadUpdateMembersMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $userId = auth()->user()->id;
        $id = $request->route('id');
        $letsPlay = LetsPlay::where('t_user_id', $userId)->findOrfail($id);
        if (!isset($letsPlay)) {
            return ApiResponse::error('You are not organize');
        }
        return $next($request);
    }
}
