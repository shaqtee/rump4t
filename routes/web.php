<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthWebController;
use App\Http\Controllers\Admin\Modules\CommunityController;
use App\Http\Controllers\ManageEvent\ManageEventController;
use App\Http\Controllers\Admin\Modules\UserManageController;
use App\Http\Controllers\Admin\Modules\AlbumCommunityController;
use App\Http\Controllers\Admin\Modules\EventCommunityController;
use App\Http\Controllers\ManageEvent\ManageEventAlbumController;
use App\Http\Controllers\Admin\Modules\PostingCommunityController;
use App\Http\Controllers\Admin\Modules\SponsorCommunityController;
use App\Http\Controllers\ManageEvent\ManageEventSponsorController;
use App\Http\Controllers\Admin\Modules\Events\AlbumEventController;
use App\Http\Controllers\Admin\Modules\LetsPlay\LetsPlayController;
use App\Http\Controllers\Admin\Modules\Events\SponsorEventController;
use App\Http\Controllers\Admin\Modules\Events\WinnerCategoryController;
use App\Http\Controllers\ManagePeople\Modules\ManageCommunityController;
use App\Http\Controllers\ManageEvent\ManageEventWinnerCategoryController;
use App\Http\Controllers\ManagePeople\Modules\ManageUserManageController;
use App\Http\Controllers\Admin\Modules\Masters\MasterGolfCourseController;
use App\Http\Controllers\Admin\Modules\Masters\MasterBannerSlideController;
use App\Http\Controllers\Admin\Modules\Masters\MasterRulesScoreController;
use App\Http\Controllers\ManagePeople\Modules\ManageAlbumCommunityController;
use App\Http\Controllers\ManagePeople\Modules\ManageEventCommunityController;
use App\Http\Controllers\Admin\Modules\Masters\MasterWinnerCategoryController;
use App\Http\Controllers\Admin\Modules\SocialMedia\ElectionsController;
use App\Http\Controllers\Admin\Modules\SocialMedia\InformationController;
use App\Http\Controllers\Admin\Modules\SocialMedia\SocialMediaController;
use App\Http\Controllers\ManagePeople\Modules\ManagePostingCommunityController;
use App\Http\Controllers\ManagePeople\Modules\ManageSponsorCommunityController;
use App\Http\Controllers\ManagePeople\Modules\Events\ManageAlbumEventController;
use App\Http\Controllers\ManagePeople\Modules\Events\ManageSponsorEventController;
use App\Http\Controllers\ManagePeople\Modules\Events\ManageWinnerCategoryController;
use Modules\NewsAdmin\App\Http\Controllers\NewsAdminController;
use Modules\SocialMedia\App\Http\Controllers\ModeratorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::redirect('', 'login-web');
Route::get('dgolf/profile-user/{id}', [AuthWebController::class, 'webViewProfile'])->name('dgolf.profile-user');
Route::middleware(['guest'])->group(function(){
    Route::get('login-web', [AuthWebController::class, 'view_login'])->name('login');
    Route::post('login-web', [AuthWebController::class, 'login'])->name('login-web');
});
Route::get('logout', [AuthWebController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function(){
    Route::prefix('admin')->middleware('userAkses:1')->group(function() {
        Route::get('home', [AuthWebController::class, 'home'])->name('admin.home');

        Route::prefix("news-admin")->group(function(){
            Route::get('', [NewsAdminController::class, 'index'])->name('news-admin.index');
            Route::get('tambah', [NewsAdminController::class, 'create'])->name('news-admin.tambah');
            Route::post('tambah', [NewsAdminController::class, 'store'])->name('news-admin.tambah');
            Route::get('{id}/ubah', [NewsAdminController::class, 'edit'])->name('news-admin.ubah');
            Route::put('{id}/ubah', [NewsAdminController::class, 'update'])->name('news-admin.ubah');
            Route::delete('{id}/hapus', [NewsAdminController::class, 'destroy'])->name('news-admin.hapus');
        });

        Route::prefix('users')->group(function(){
            Route::get('index', [UserManageController::class, 'index'])->name('users.semua');
            Route::get('tambah', [UserManageController::class, 'create'])->name('users.tambah');
            Route::post('tambah', [UserManageController::class, 'store'])->name('users.tambah');
            Route::get('{id}/lihat', [UserManageController::class, 'show'])->name('users.lihat');
            Route::get('{id}/ubah', [UserManageController::class, 'edit'])->name('users.ubah');
            Route::patch('{id}/ubah', [UserManageController::class, 'update'])->name('users.ubah');
            Route::get('{id}/game-score', [UserManageController::class, 'game_score'])->name('users.gamescore');
            Route::get('{id}/handicap-index', [UserManageController::class, 'hcp_index'])->name('users.hcpindex');
            Route::get('index-admin', [UserManageController::class, 'index_admin'])->name('users.admin.semua');
            Route::patch('{id}/index-admin', [UserManageController::class, 'update_admin'])->name('users.admin.edit');
            Route::post('index-admin', [UserManageController::class, 'store_admin'])->name('users.admin.tambah');
        });

        Route::prefix('community')->group(function(){
            Route::get('index', [CommunityController::class, 'index'])->name('community.semua');
            Route::get('tambah', [CommunityController::class, 'create'])->name('community.tambah');
            Route::post('tambah', [CommunityController::class, 'store'])->name('community.tambah');
            Route::get('{id}/ubah', [CommunityController::class, 'edit'])->name('community.ubah');
            Route::patch('{id}/ubah', [CommunityController::class, 'update'])->name('community.ubah');
            Route::delete('{id}/hapus', [CommunityController::class, 'destroy'])->name('community.hapus');
            Route::get('{community_id}/add-manage-people', [CommunityController::class, 'user_index'])->name('community.addmanagepeopleview');
            Route::post('add-manage-people', [CommunityController::class, 'add_member'])->name('community.addmanagepeople');
            Route::get('{community_id}/member', [CommunityController::class, 'user_member'])->name('community.member');
            Route::get('{community_id}/leaderboard', [CommunityController::class, 'leaderboard'])->name('community.leaderboard');
            // Route::get('event/index', [EventCommunityController::class, 'index_community'])->name('community.event.semua');
            // Route::get('event/{event_id}/lihat', [EventCommunityController::class, 'show_community'])->name('community.event.lihat');

            Route::prefix('posting')->group(function(){
                Route::get('index', [PostingCommunityController::class, 'index'])->name('community.posting.semua');
                Route::get('tambah', [PostingCommunityController::class, 'create'])->name('community.posting.tambah');
                Route::post('tambah', [PostingCommunityController::class, 'store'])->name('community.posting.tambah');
                Route::get('{id}/ubah', [PostingCommunityController::class, 'edit'])->name('community.posting.ubah');
                Route::patch('{id}/ubah', [PostingCommunityController::class, 'update'])->name('community.posting.ubah');
                Route::delete('{id}/hapus', [PostingCommunityController::class, 'destroy'])->name('community.posting.hapus');
            });

            Route::prefix('album')->group(function(){
                Route::get('index', [AlbumCommunityController::class, 'index'])->name('community.album.semua');
                Route::get('tambah', [AlbumCommunityController::class, 'create'])->name('community.album.tambah');
                Route::post('tambah', [AlbumCommunityController::class, 'store'])->name('community.album.tambah');
                Route::get('{id}/ubah', [AlbumCommunityController::class, 'edit'])->name('community.album.ubah');
                Route::patch('{id}/ubah', [AlbumCommunityController::class, 'update'])->name('community.album.ubah');
                Route::delete('{id}/hapus', [AlbumCommunityController::class, 'destroy'])->name('community.album.hapus');

                Route::prefix('photo')->group(function(){
                    Route::get('{album_id}/index', [AlbumCommunityController::class, 'index_photo'])->name('community.album.photo.semua');
                    Route::get('{album_id}/tambah', [AlbumCommunityController::class, 'create_photo'])->name('community.album.photo.viewtambah');
                    Route::post('tambah', [AlbumCommunityController::class, 'store_photo'])->name('community.album.photo.tambah');
                    Route::get('{id}/ubah', [AlbumCommunityController::class, 'edit_photo'])->name('community.album.photo.ubah');
                    Route::patch('{id}/ubah', [AlbumCommunityController::class, 'update_photo'])->name('community.album.photo.ubah');
                    Route::delete('{id}/hapus', [AlbumCommunityController::class, 'destroy_photo'])->name('community.album.photo.hapus');
                });
            });

            Route::prefix('supporting-partner')->group(function(){
                Route::get('index', [SponsorCommunityController::class, 'index'])->name('community.sponsor.semua');
                Route::get('tambah', [SponsorCommunityController::class, 'create'])->name('community.sponsor.tambah');
                Route::post('tambah', [SponsorCommunityController::class, 'store'])->name('community.sponsor.tambah');
                Route::get('{id}/ubah', [SponsorCommunityController::class, 'edit'])->name('community.sponsor.ubah');
                Route::patch('{id}/ubah', [SponsorCommunityController::class, 'update'])->name('community.sponsor.ubah');
                Route::delete('{id}/hapus', [SponsorCommunityController::class, 'destroy'])->name('community.sponsor.hapus');
            });
        });

        Route::prefix('event')->group(function(){
            Route::get('index', [EventCommunityController::class, 'index'])->name('event.semua');
            Route::get('tambah', [EventCommunityController::class, 'create'])->name('event.tambah');
            Route::post('tambah', [EventCommunityController::class, 'store'])->name('event.tambah');
            Route::get('{id}/ubah', [EventCommunityController::class, 'edit'])->name('event.ubah');
            Route::patch('{id}/ubah', [EventCommunityController::class, 'update'])->name('event.ubah');
            Route::delete('{id}/hapus', [EventCommunityController::class, 'destroy'])->name('event.hapus');
            Route::get('{id}/leaderboard', [EventCommunityController::class, 'leaderboard'])->name('event.leaderboard');
            Route::get('{id}/winner-category', [EventCommunityController::class, 'create_winner_category'])->name('event.winner-category');
            Route::post('tambah-winner-category', [EventCommunityController::class, 'store_winner_category'])->name('event.tambah.winner-category');
            Route::get('{id}/view-input-score', [EventCommunityController::class, 'create_input_score'])->name('event.viewInputScore');
            Route::post('input-score', [EventCommunityController::class, 'store_input_score'])->name('event.inputScore');
            Route::get('/get-user-scores', [EventCommunityController::class, 'getUserScores'])->name('event.getUserScore');


            Route::prefix('registrant')->group(function(){
                Route::get('{event_id}/index', [EventCommunityController::class, 'index_registrant'])->name('event.registrant.semua');
                Route::patch('{id}/ubah', [EventCommunityController::class, 'update_registrant'])->name('event.registrant.ubah');
                Route::post('tambah', [EventCommunityController::class, 'store_user_join'])->name('event.registrant.tambah');
            });

            Route::prefix('supporting-partner')->group(function(){
                Route::get('index', [SponsorEventController::class, 'index'])->name('event.sponsor.semua');
                Route::get('tambah', [SponsorEventController::class, 'create'])->name('event.sponsor.tambah');
                Route::post('tambah', [SponsorEventController::class, 'store'])->name('event.sponsor.tambah');
                Route::get('{id}/ubah', [SponsorEventController::class, 'edit'])->name('event.sponsor.ubah');
                Route::patch('{id}/ubah', [SponsorEventController::class, 'update'])->name('event.sponsor.ubah');
                Route::delete('{id}/hapus', [SponsorEventController::class, 'destroy'])->name('event.sponsor.hapus');
            });

            Route::prefix('winner-category')->group(function(){
                Route::get('{id}/index', [WinnerCategoryController::class, 'index'])->name('event.winners.semua');
                Route::get('tambah', [WinnerCategoryController::class, 'create'])->name('event.winners.tambah');
                Route::post('tambah', [WinnerCategoryController::class, 'store'])->name('event.winners.tambah');
                Route::get('{id}/ubah', [WinnerCategoryController::class, 'edit'])->name('event.winners.ubah');
                Route::patch('{id}/ubah', [WinnerCategoryController::class, 'update'])->name('event.winners.ubah');
                Route::delete('{id}/hapus', [WinnerCategoryController::class, 'destroy'])->name('event.winners.hapus');
            });

            Route::prefix('album')->group(function(){
                Route::get('index', [AlbumEventController::class, 'index'])->name('event.album.semua');
                Route::get('tambah', [AlbumEventController::class, 'create'])->name('event.album.tambah');
                Route::post('tambah', [AlbumEventController::class, 'store'])->name('event.album.tambah');
                Route::get('{id}/ubah', [AlbumEventController::class, 'edit'])->name('event.album.ubah');
                Route::patch('{id}/ubah', [AlbumEventController::class, 'update'])->name('event.album.ubah');
                Route::delete('{id}/hapus', [AlbumEventController::class, 'destroy'])->name('event.album.hapus');

                Route::prefix('photo')->group(function(){
                    Route::get('{album_id}/index', [AlbumEventController::class, 'photo_index'])->name('event.album.photo.semua');
                    Route::get('{album_id}/tambah', [AlbumEventController::class, 'photo_create'])->name('event.album.photo.viewtambah');
                    Route::post('tambah', [AlbumEventController::class, 'photo_store'])->name('event.album.photo.tambah');
                    Route::get('{id}/ubah', [AlbumEventController::class, 'photo_edit'])->name('event.album.photo.ubah');
                    Route::patch('{id}/ubah', [AlbumEventController::class, 'photo_update'])->name('event.album.photo.ubah');
                    Route::delete('{id}/hapus', [AlbumEventController::class, 'photo_destroy'])->name('event.album.photo.hapus');
                });
            });

        });

        Route::prefix('lets-play')->group(function(){
            Route::get('', [LetsPlayController::class, 'index'])->name('letsplay.semua');
            Route::patch('{id}', [LetsPlayController::class, 'update'])->name('letsplay.edit');
            Route::get('{id}/member', [LetsPlayController::class, 'member'])->name('letsplay.member');
            Route::get('{id}/view-input-score', [LetsPlayController::class, 'create_input_score'])->name('letsplay.viewInputScore');
            Route::get('input-score', [LetsPlayController::class, 'store_input_score'])->name('letsplay.inputScore');
            Route::post('tambah-pemain', [LetsPlayController::class, 'store_user_join'])->name('letsplay.tambahPemain');
        });

        Route::prefix('social-media')->group(function(){
            Route::resources(['informations' => InformationController::class]);
            Route::resources(['elections' => ElectionsController::class]);
            Route::prefix("mods")->group(function(){
                Route::get('', [ModeratorController::class, 'index'])->name('socialmedia.moderation.index');
                Route::get('tambah', [ModeratorController::class, 'create'])->name('socialmedia.moderation.tambah');
                Route::post('tambah', [ModeratorController::class, 'store'])->name('socialmedia.moderation.tambah');
                Route::get('{id}/ubah', [ModeratorController::class, 'edit'])->name('socialmedia.moderation.ubah');
                Route::put('{id}/ubah', [ModeratorController::class, 'update'])->name('socialmedia.moderation.ubah');
                Route::delete('{id}/hapus', [ModeratorController::class, 'destroy'])->name('socialmedia.moderation.hapus');
                // add mods route and coments route
                Route::get("{id}/moderate" , [ModeratorController::class, 'moderate'])->name('socialmedia.moderation.moderate');
                Route::put("{id}/moderate" , [ModeratorController::class, 'moderateStore'])->name('socialmedia.moderation.moderate');
                Route::get('{id}/comments', [ModeratorController::class, 'comments'])->name('socialmedia.moderation.comments');
                Route::post('{id}/comments', [ModeratorController::class, 'commentStore'])->name('socialmedia.moderation.comments');
                //
                // editing and deleting comments 
                Route::get('{id}/comments/{comment_id}/edit', [ModeratorController::class, 'editComment'])->name('socialmedia.moderation.comments.edit');
                Route::put('{id}/comments/{comment_id}/edit', [ModeratorController::class, 'commentUpdate'])->name('socialmedia.moderation.comments.edit');
                Route::delete('{id}/comments/{comment_id}/hapus', [ModeratorController::class, 'commentDestroy'])->name('socialmedia.moderation.comments.hapus');

            });
            Route::prefix('elections')->group(function(){
                Route::get('{id}/results', [ElectionsController::class, 'resultsCandidate'])->name('socialmedia.elections.resultsCandidate');
                Route::patch('{id}/update-candidate', [ElectionsController::class, 'updateCandidate'])->name('socialmedia.elections.updateCandidate');
                Route::delete('{id}/destroy-candidate', [ElectionsController::class, 'destroyCandidate'])->name('socialmedia.elections.destroyCandidate');
                Route::delete('{id}/destroy-person-responsible', [ElectionsController::class, 'destroyPersonResponsible'])->name('socialmedia.elections.destroyPersonResponsible');
            });
            Route::resources(['postingans' => SocialMediaController::class]);
        });

        Route::prefix('masters')->group(function(){
            Route::get('index/{golf_course_id}/tee-box',[MasterGolfCourseController::class, 'index_tee'])->name('golf-course.teebox.index');
            Route::get('create/{golf_course_id}/tee-box',[MasterGolfCourseController::class, 'create_tee'])->name('golf-course.teebox.create');
            Route::post('store/tee-box',[MasterGolfCourseController::class, 'store_tee'])->name('golf-course.teebox.store');
            Route::get('edit/{golf_course_id}/tee-box',[MasterGolfCourseController::class, 'edit_tee'])->name('golf-course.teebox.edit');
            Route::patch('update/{golf_course_id}/tee-box',[MasterGolfCourseController::class, 'update_tee'])->name('golf-course.teebox.update');
            Route::delete('delete/{golf_course_id}/tee-box',[MasterGolfCourseController::class, 'destroy_tee'])->name('golf-course.teebox.delete');
            Route::get('index/{golf_course_id}/hole',[MasterGolfCourseController::class, 'index_hole'])->name('golf-course.hole.index');
            Route::get('create/{golf_course_id}/hole',[MasterGolfCourseController::class, 'create_hole'])->name('golf-course.hole.create');
            Route::post('store/hole',[MasterGolfCourseController::class, 'store_hole'])->name('golf-course.hole.store');
            Route::get('edit/{golf_course_id}/hole',[MasterGolfCourseController::class, 'edit_hole'])->name('golf-course.hole.edit');
            Route::patch('update/{golf_course_id}/hole',[MasterGolfCourseController::class, 'update_hole'])->name('golf-course.hole.update');
            Route::delete('delete/{golf_course_id}/hole',[MasterGolfCourseController::class, 'delete_hole'])->name('golf-course.hole.delete');
            Route::resources(['golf-course' => MasterGolfCourseController::class]);
            Route::resources(['banner-slide' => MasterBannerSlideController::class]);
            Route::resources(['winner-category' => MasterWinnerCategoryController::class]);
            Route::resources(['rules-score' => MasterRulesScoreController::class]);

        });
    });

    Route::prefix('manage-people')->middleware('userAkses:2')->group(function() {
        Route::get('home', [AuthWebController::class, 'home_manage'])->name('manage-people.home');

        Route::prefix('users')->group(function(){
            Route::get('index', [ManageUserManageController::class, 'index'])->name('users.manage.semua');
            Route::get('tambah', [ManageUserManageController::class, 'create'])->name('users.manage.tambah');
            Route::post('tambah', [ManageUserManageController::class, 'store'])->name('users.manage.tambah');
            Route::get('{id}/lihat', [ManageUserManageController::class, 'show'])->name('users.manage.lihat');
            Route::get('{id}/ubah', [ManageUserManageController::class, 'edit'])->name('users.manage.ubah');
            Route::patch('{id}/ubah', [ManageUserManageController::class, 'update'])->name('users.manage.ubah');
            Route::get('{id}/game-score', [ManageUserManageController::class, 'game_score'])->name('users.manage.gamescore');
            Route::get('{id}/handicap-index', [ManageUserManageController::class, 'hcp_index'])->name('users.manage.hcpindex');
        });

        Route::prefix('community')->group(function(){
            Route::get('index', [ManageCommunityController::class, 'index'])->name('community.manage.semua');
            // Route::get('tambah', [ManageCommunityController::class, 'create'])->name('community.manage.tambah');
            // Route::post('tambah', [ManageCommunityController::class, 'store'])->name('community.manage.tambah');
            Route::get('{id}/ubah', [ManageCommunityController::class, 'edit'])->name('community.manage.ubah');
            Route::patch('{id}/ubah', [ManageCommunityController::class, 'update'])->name('community.manage.ubah');
            // Route::delete('{id}/hapus', [ManageCommunityController::class, 'destroy'])->name('community.manage.hapus');
            Route::get('{community_id}/add-manage-people', [ManageCommunityController::class, 'user_index'])->name('community.manage.addmanagepeopleview');
            Route::post('add-manage-people', [ManageCommunityController::class, 'add_member'])->name('community.manage.addmanagepeople');
            Route::get('{community_id}/member', [ManageCommunityController::class, 'user_member'])->name('community.manage.member');
            Route::get('{community_id}/leaderboard', [ManageCommunityController::class, 'leaderboard'])->name('community.manage.leaderboard');
            // Route::get('event/index', [ManageEventCommunityController::class, 'index_community'])->name('community.manage.event.semua');
            // Route::get('event/{event_id}/lihat', [ManageEventCommunityController::class, 'show_community'])->name('community.manage.event.lihat');

            Route::prefix('posting')->group(function(){
                Route::get('index', [ManagePostingCommunityController::class, 'index'])->name('community.manage.posting.semua');
                Route::get('tambah', [ManagePostingCommunityController::class, 'create'])->name('community.manage.posting.tambah');
                Route::post('tambah', [ManagePostingCommunityController::class, 'store'])->name('community.manage.posting.tambah');
                Route::get('{id}/ubah', [ManagePostingCommunityController::class, 'edit'])->name('community.manage.posting.ubah');
                Route::patch('{id}/ubah', [ManagePostingCommunityController::class, 'update'])->name('community.manage.posting.ubah');
                Route::delete('{id}/hapus', [ManagePostingCommunityController::class, 'destroy'])->name('community.manage.posting.hapus');
            });

            Route::prefix('album')->group(function(){
                Route::get('index', [ManageAlbumCommunityController::class, 'index'])->name('community.manage.album.semua');
                Route::get('tambah', [ManageAlbumCommunityController::class, 'create'])->name('community.manage.album.tambah');
                Route::post('tambah', [ManageAlbumCommunityController::class, 'store'])->name('community.manage.album.tambah');
                Route::get('{id}/ubah', [ManageAlbumCommunityController::class, 'edit'])->name('community.manage.album.ubah');
                Route::patch('{id}/ubah', [ManageAlbumCommunityController::class, 'update'])->name('community.manage.album.ubah');
                Route::delete('{id}/hapus', [ManageAlbumCommunityController::class, 'destroy'])->name('community.manage.album.hapus');

                Route::prefix('photo')->group(function(){
                    Route::get('{album_id}/index', [ManageAlbumCommunityController::class, 'index_photo'])->name('community.manage.album.photo.semua');
                    Route::get('{album_id}/tambah', [ManageAlbumCommunityController::class, 'create_photo'])->name('community.manage.album.photo.viewtambah');
                    Route::post('tambah', [ManageAlbumCommunityController::class, 'store_photo'])->name('community.manage.album.photo.tambah');
                    Route::get('{id}/ubah', [ManageAlbumCommunityController::class, 'edit_photo'])->name('community.manage.album.photo.ubah');
                    Route::patch('{id}/ubah', [ManageAlbumCommunityController::class, 'update_photo'])->name('community.manage.album.photo.ubah');
                    Route::delete('{id}/hapus', [ManageAlbumCommunityController::class, 'destroy_photo'])->name('community.manage.album.photo.hapus');
                });
            });

            Route::prefix('supporting-partner')->group(function(){
                Route::get('index', [ManageSponsorCommunityController::class, 'index'])->name('community.manage.sponsor.semua');
                Route::get('tambah', [ManageSponsorCommunityController::class, 'create'])->name('community.manage.sponsor.tambah');
                Route::post('tambah', [ManageSponsorCommunityController::class, 'store'])->name('community.manage.sponsor.tambah');
                Route::get('{id}/ubah', [ManageSponsorCommunityController::class, 'edit'])->name('community.manage.sponsor.ubah');
                Route::patch('{id}/ubah', [ManageSponsorCommunityController::class, 'update'])->name('community.manage.sponsor.ubah');
                Route::delete('{id}/hapus', [ManageSponsorCommunityController::class, 'destroy'])->name('community.manage.sponsor.hapus');
            });
        });

        Route::prefix('event')->group(function(){
            Route::get('index', [ManageEventCommunityController::class, 'index'])->name('event.manage.semua');
            Route::get('tambah', [ManageEventCommunityController::class, 'create'])->name('event.manage.tambah');
            Route::post('tambah', [ManageEventCommunityController::class, 'store'])->name('event.manage.tambah');
            Route::get('{id}/ubah', [ManageEventCommunityController::class, 'edit'])->name('event.manage.ubah');
            Route::patch('{id}/ubah', [ManageEventCommunityController::class, 'update'])->name('event.manage.ubah');
            Route::delete('{id}/hapus', [ManageEventCommunityController::class, 'destroy'])->name('event.manage.hapus');
            Route::get('{id}/leaderboard', [ManageEventCommunityController::class, 'leaderboard'])->name('event.manage.leaderboard');

            Route::prefix('registrant')->group(function(){
                Route::get('{event_id}/index', [ManageEventCommunityController::class, 'index_registrant'])->name('event.manage.registrant.semua');
                Route::patch('{id}/ubah', [ManageEventCommunityController::class, 'update_registrant'])->name('event.manage.registrant.ubah');
            });

            Route::prefix('supporting-partner')->group(function(){
                Route::get('{event_id}/index', [ManageSponsorEventController::class, 'index'])->name('event.manage.sponsor.semua');
                Route::get('{event_id}/tambah', [ManageSponsorEventController::class, 'create'])->name('event.manage.sponsor.viewtambah');
                Route::post('tambah', [ManageSponsorEventController::class, 'store'])->name('event.manage.sponsor.tambah');
                Route::get('{id}/ubah', [ManageSponsorEventController::class, 'edit'])->name('event.manage.sponsor.ubah');
                Route::patch('{id}/ubah', [ManageSponsorEventController::class, 'update'])->name('event.manage.sponsor.ubah');
                Route::delete('{id}/hapus', [ManageSponsorEventController::class, 'destroy'])->name('event.manage.sponsor.hapus');
            });

            Route::prefix('winner-category')->group(function(){
                Route::get('{id}/index', [ManageWinnerCategoryController::class, 'index'])->name('event.manage.winners.semua');
                Route::get('tambah', [ManageWinnerCategoryController::class, 'create'])->name('event.manage.winners.tambah');
                Route::post('tambah', [ManageWinnerCategoryController::class, 'store'])->name('event.manage.winners.tambah');
                Route::get('{id}/ubah', [ManageWinnerCategoryController::class, 'edit'])->name('event.manage.winners.ubah');
                Route::patch('{id}/ubah', [ManageWinnerCategoryController::class, 'update'])->name('event.manage.winners.ubah');
                Route::delete('{id}/hapus', [ManageWinnerCategoryController::class, 'destroy'])->name('event.manage.winners.hapus');
            });

            Route::prefix('album')->group(function(){
                Route::get('{event_id}/index', [ManageAlbumEventController::class, 'index'])->name('event.manage.album.semua');
                Route::get('{event_id}/tambah', [ManageAlbumEventController::class, 'create'])->name('event.manage.album.viewtambah');
                Route::post('tambah', [ManageAlbumEventController::class, 'store'])->name('event.manage.album.tambah');
                Route::get('{id}/ubah', [ManageAlbumEventController::class, 'edit'])->name('event.manage.album.ubah');
                Route::patch('{id}/ubah', [ManageAlbumEventController::class, 'update'])->name('event.manage.album.ubah');
                Route::delete('{id}/hapus', [ManageAlbumEventController::class, 'destroy'])->name('event.manage.album.hapus');

                Route::prefix('photo')->group(function(){
                    Route::get('{album_id}/index', [ManageAlbumEventController::class, 'photo_index'])->name('event.manage.album.photo.semua');
                    Route::get('{album_id}/tambah', [ManageAlbumEventController::class, 'photo_create'])->name('event.manage.album.photo.viewtambah');
                    Route::post('tambah', [ManageAlbumEventController::class, 'photo_store'])->name('event.manage.album.photo.tambah');
                    Route::get('{id}/ubah', [ManageAlbumEventController::class, 'photo_edit'])->name('event.manage.album.photo.ubah');
                    Route::patch('{id}/ubah', [ManageAlbumEventController::class, 'photo_update'])->name('event.manage.album.photo.ubah');
                    Route::delete('{id}/hapus', [ManageAlbumEventController::class, 'photo_destroy'])->name('event.manage.album.photo.hapus');
                });
            });

        });
    });

    Route::prefix('manage-event')->middleware('userAkses:3')->group(function() {
        Route::get('home', [AuthWebController::class, 'home_manage_event'])->name('manage-event.home');

        Route::prefix('event')->group(function(){
            Route::get('index', [ManageEventController::class, 'index'])->name('event.manage-event.semua');
            Route::get('tambah', [ManageEventController::class, 'create'])->name('event.manage-event.tambah');
            Route::post('tambah', [ManageEventController::class, 'store'])->name('event.manage-event.tambah');
            Route::get('{id}/ubah', [ManageEventController::class, 'edit'])->name('event.manage-event.ubah');
            Route::patch('{id}/ubah', [ManageEventController::class, 'update'])->name('event.manage-event.ubah');
            Route::delete('{id}/hapus', [ManageEventController::class, 'destroy'])->name('event.manage-event.hapus');
            Route::get('{id}/leaderboard', [ManageEventController::class, 'leaderboard'])->name('event.manage-event.leaderboard');

            Route::prefix('registrant')->group(function(){
                Route::get('{event_id}/index', [ManageEventController::class, 'index_registrant'])->name('event.manage-event.registrant.semua');
                Route::patch('{id}/ubah', [ManageEventController::class, 'update_registrant'])->name('event.manage-event.registrant.ubah');
            });

            Route::prefix('supporting-partner')->group(function(){
                Route::get('{event_id}/index', [ManageEventSponsorController::class, 'index'])->name('event.manage-event.sponsor.semua');
                Route::get('{event_id}/tambah', [ManageEventSponsorController::class, 'create'])->name('event.manage-event.sponsor.viewtambah');
                Route::post('tambah', [ManageEventSponsorController::class, 'store'])->name('event.manage-event.sponsor.tambah');
                Route::get('{id}/ubah', [ManageEventSponsorController::class, 'edit'])->name('event.manage-event.sponsor.ubah');
                Route::patch('{id}/ubah', [ManageEventSponsorController::class, 'update'])->name('event.manage-event.sponsor.ubah');
                Route::delete('{id}/hapus', [ManageEventSponsorController::class, 'destroy'])->name('event.manage-event.sponsor.hapus');
            });

            Route::prefix('winner-category')->group(function(){
                Route::get('{id}/index', [ManageEventWinnerCategoryController::class, 'index'])->name('event.manage-event.winners.semua');
                Route::get('tambah', [ManageEventWinnerCategoryController::class, 'create'])->name('event.manage-event.winners.tambah');
                Route::post('tambah', [ManageEventWinnerCategoryController::class, 'store'])->name('event.manage-event.winners.tambah');
                Route::get('{id}/ubah', [ManageEventWinnerCategoryController::class, 'edit'])->name('event.manage-event.winners.ubah');
                Route::patch('{id}/ubah', [ManageEventWinnerCategoryController::class, 'update'])->name('event.manage-event.winners.ubah');
                Route::delete('{id}/hapus', [ManageEventWinnerCategoryController::class, 'destroy'])->name('event.manage-event.winners.hapus');
            });

            Route::prefix('album')->group(function(){
                Route::get('{event_id}/index', [ManageEventAlbumController::class, 'index'])->name('event.manage-event.album.semua');
                Route::get('{event_id}/tambah', [ManageEventAlbumController::class, 'create'])->name('event.manage-event.album.viewtambah');
                Route::post('tambah', [ManageEventAlbumController::class, 'store'])->name('event.manage-event.album.tambah');
                Route::get('{id}/ubah', [ManageEventAlbumController::class, 'edit'])->name('event.manage-event.album.ubah');
                Route::patch('{id}/ubah', [ManageEventAlbumController::class, 'update'])->name('event.manage-event.album.ubah');
                Route::delete('{id}/hapus', [ManageEventAlbumController::class, 'destroy'])->name('event.manage-event.album.hapus');

                Route::prefix('photo')->group(function(){
                    Route::get('{album_id}/index', [ManageEventAlbumController::class, 'photo_index'])->name('event.manage-event.album.photo.semua');
                    Route::get('{album_id}/tambah', [ManageEventAlbumController::class, 'photo_create'])->name('event.manage-event.album.photo.viewtambah');
                    Route::post('tambah', [ManageEventAlbumController::class, 'photo_store'])->name('event.manage-event.album.photo.tambah');
                    Route::get('{id}/ubah', [ManageEventAlbumController::class, 'photo_edit'])->name('event.manage-event.album.photo.ubah');
                    Route::patch('{id}/ubah', [ManageEventAlbumController::class, 'photo_update'])->name('event.manage-event.album.photo.ubah');
                    Route::delete('{id}/hapus', [ManageEventAlbumController::class, 'photo_destroy'])->name('event.manage-event.album.photo.hapus');
                });
            });

        });
    });
});

Route::get('privacy-and-policy', function() {
    return view('PrivacyAndPolicy');
});
Route::get('term-condition', function() {
    return view('TermCondition');
});
