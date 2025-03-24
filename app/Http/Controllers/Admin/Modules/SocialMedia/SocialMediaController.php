<?php

namespace App\Http\Controllers\Admin\Modules\SocialMedia;

use App\Exceptions\Handler;
use App\Http\Controllers\Controller;
use App\Services\Helpers\Helper;
use App\Services\WebRedirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\SocialMedia\App\Models\Post;
use Modules\SocialMedia\App\Models\ReportPost;

class SocialMediaController extends Controller
{
    public function __construct(
        protected Post $model,
        protected ReportPost $reportPost,
        protected Helper $helper,
        protected Handler $handler,
        protected WebRedirect $web
    )
    {}

    public function index(Request $request) {
        try{
            $page = $request->size ?? 10;
            $maxReport = 10;
            $postingan = $this->model->leftJoin('t_report_post as trp', 't_post.id', '=', 'trp.t_post_id')
                ->select('t_post.*', DB::raw('COUNT(trp.id) as reports_count'))
                ->groupBy('t_post.id', 't_post.title', 't_post.description', 't_post.url_cover_image', 't_post.id_event', 't_post.id_user', 't_post.created_at', 't_post.updated_at')
                ->havingRaw('COUNT(trp.id) >= ' . $maxReport)
                ->filter($request)->orderByDesc('id')->paginate($page)->appends($request->all());

            $data = [
                'content' => 'Admin/SocialMedia/Postingan/index',
                'title' => 'Data Report Postingan',
                'postingans' =>  $postingan,
                'columns' => $this->model->columnsWeb()
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try{
            $this->model->findOrfail($id)->delete();
            DB::commit();
            return $this->web->destroy('postingans.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }
}
