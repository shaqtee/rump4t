<?php

namespace Modules\Groups\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Modules\SocialMedia\App\Models\Post;
use Modules\SocialMedia\App\Models\DetailPost;

class PostingModerationController extends Controller
{
    public function moderate(Request $request, $group_id, $post_id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $post = Post::where('t_small_groups_id', $group_id)->findOrFail($post_id);
            $post->moderation = [
                'moderator' => $request->user(),
                'reason'    => $request->input('reason'),
            ];
            $post->save();
            $post->delete();
            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Post moderated successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'failed',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }

    public function listComments($group_id, $post_id): JsonResponse
    {
        $comments = DetailPost::where('id_post', $post_id)
            ->whereNull('parent_id')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $comments,
        ]);
    }

    public function storeComment(Request $request, $group_id, $post_id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $comment = DetailPost::create([
                'id_post'  => $post_id,
                'id_user'  => $request->user()->id,
                'komentar' => $request->input('content'),
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data'   => $comment,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'failed',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }

    public function updateComment(Request $request, $group_id, $post_id, $comment_id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $comment = DetailPost::where('id_post', $post_id)
                ->where('id', $comment_id)
                ->firstOrFail();
            $comment->komentar = $request->input('content');
            $comment->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data'   => $comment,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'failed',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }

    public function destroyComment($group_id, $post_id, $comment_id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $comment = DetailPost::where('id_post', $post_id)
                ->where('id', $comment_id)
                ->firstOrFail();
            $comment->delete();
            DB::commit();
            return response()->json([
                'status'  => 'success',
                'message' => 'Comment deleted successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'failed',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }

    public function listSubcomments($group_id, $post_id, $comment_id): JsonResponse
    {
        $subcomments = DetailPost::where('parent_id', $comment_id)->get();

        return response()->json([
            'status' => 'success',
            'data'   => $subcomments,
        ]);
    }

    public function storeSubcomment(Request $request, $group_id, $post_id, $comment_id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $subcomment = DetailPost::create([
                'id_post'  => $post_id,
                'id_user'  => $request->user()->id,
                'komentar' => $request->input('content'),
                'parent_id' => $comment_id,
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data'   => $subcomment,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'failed',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }

    public function updateSubcomment(Request $request, $group_id, $post_id, $comment_id, $subcomment_id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $subcomment = DetailPost::where('parent_id', $comment_id)
                ->where('id', $subcomment_id)
                ->firstOrFail();
            $subcomment->komentar = $request->input('content');
            $subcomment->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data'   => $subcomment,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'failed',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }

    public function destroySubcomment($group_id, $post_id, $comment_id, $subcomment_id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $subcomment = DetailPost::where('parent_id', $comment_id)
                ->where('id', $subcomment_id)
                ->firstOrFail();
            $subcomment->delete();
            DB::commit();
            return response()->json([
                'status'  => 'success',
                'message' => 'Subcomment deleted successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'failed',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }
}

