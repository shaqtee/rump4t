<?php

namespace App\Http\Controllers\Admin\Modules\Masters;

use App\Exceptions\Handler;
use Illuminate\Http\Request;
use App\Services\WebRedirect;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Helpers\Helper;
use Illuminate\Validation\ValidationException;
use Modules\Community\App\Models\CourseArea;
use Modules\Community\App\Models\TeeBoxCourse;
use Modules\Community\App\Models\Hole;
use Modules\Masters\App\Models\MasterGolfCourse;
use Modules\Masters\App\Models\MasterConfiguration;

class MasterGolfCourseController extends Controller
{
    protected $web;
    protected $handler;
    protected $model;
    protected $teeBox;
    protected $hole;
    protected $course_area;
    protected $config;
    protected $helper;

    public function __construct(WebRedirect $web, Handler $handler, MasterGolfCourse $model, TeeBoxCourse $teeBox, Hole $hole, CourseArea $courseArea, MasterConfiguration $config, Helper $helper)
    {
        $this->web = $web;
        $this->handler = $handler;
        $this->model = $model;
        $this->teeBox = $teeBox;
        $this->hole = $hole;
        $this->course_area = $courseArea;
        $this->config = $config;
        $this->helper = $helper;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Masters/GolfCourse/index',
                'title' => 'Data Golf Course',
                'golfCourse' => $this->model->with(['teeCourse'])->filter($request)->paginate($page)->appends($request->all()),
                'columns' => $this->model->columnsWeb(),
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try{
            $data = [
                'content' => 'Admin/Masters/GolfCourse/addEdit',
                'title' => 'Add Golf Course',
                'golfCourse' => null,
                'teeBox' => $this->config->where('parameter', 'm_tee')->get(),
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                'name' => 'required',
                'address' => 'required',
                'contact' => 'required',
                'contact_person_name' => 'required',
                'contact_person_Phone' => 'required',
                'number_par' => 'required',
                'is_staging' => 'required',
            ]);
            
            // convert address to longitude & latitude
            $latlng = $this->helper->gMaps($datas['address']);
            // dd($latlng);

            if($latlng == false){
                // return $this->web->error('Location Not Found');
                $datas['longitude'] = NULL;
                $datas['latitude'] = NULL;
            }else{
                $datas['longitude'] = $latlng['longitude'];
                $datas['latitude'] = $latlng['latitude'];
            }

            $this->model->create($datas);

            DB::commit();
            return $this->web->store('golf-course.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try{
            $data = [
                'content' => 'Admin/Masters/GolfCourse/addEdit',
                'title' => 'Edit Golf Course',
                'golfCourse' => $this->model->with(['teeCourse'])->findOrfail($id),
                'teeBox' => $this->config->where('parameter', 'm_tee')->get(),
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                'name' => 'nullable',
                'address' => 'nullable',
                'contact' => 'nullable',
                'contact_person_name' => 'nullable',
                'contact_person_Phone' => 'nullable',
                'number_par' => 'nullable',
                'is_staging' => 'nullable',
            ]);

            // convert address to longitude & latitude
            $latlng = $this->helper->gMaps($datas['address']);

            if($latlng == false){
                // return $this->web->error('Location Not Found');
                $datas['longitude'] = NULL;
                $datas['latitude'] = NULL;
            }else{
                $datas['longitude'] = $latlng['longitude'];
                $datas['latitude'] = $latlng['latitude'];
            }

            $model = $this->model->findOrfail($id)->update($datas);

            DB::commit();
            return $this->web->update('golf-course.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try{
            $this->model->findOrfail($id)->delete();
            DB::commit();
            return $this->web->destroy('golf-course.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function index_tee(Request $request, $golf_course_id)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Masters/GolfCourse/TeeBox/index',
                'title' => 'Data Tee Box',
                'teeBox' => $this->teeBox->where('t_golf_course_id', $golf_course_id)->get(),
                'golfCourse' => $this->model->findOrfail($golf_course_id),
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function create_tee($id)
    {
        try{
            $data = [
                'content' => 'Admin/Masters/GolfCourse/TeeBox/addEdit',
                'title' => 'Add Tee Box',
                'golfCourse' => $this->model->findOrfail($id),
                'teeBox' => null,
                'MasterTeeBox' => $this->config->where('parameter', 'm_tee')->get(),
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function store_tee(Request $request)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                't_golf_course_id' => 'required',
                'tee_type' => 'required',
                'description' => 'nullable',
                'course_rating' => 'required',
                'slope_rating' => 'required',
            ]);
            $this->teeBox->create($datas);

            DB::commit();
            return redirect()->route('golf-course.teebox.index', ['golf_course_id' => $datas['t_golf_course_id']])->with('success', 'Data successfully added');;
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function edit_tee(string $id)
    {
        try{
            $data = [
                'content' => 'Admin/Masters/GolfCourse/TeeBox/addEdit',
                'title' => 'Edit Tee Box',
                'golfCourse'=> $this->teeBox->findOrfail($id),
                'teeBox'=> $this->teeBox->findOrfail($id),
                'MasterTeeBox' => $this->config->where('parameter', 'm_tee')->get(),
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function update_tee(Request $request, string $id)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                'tee_type' => 'required',
                'description' => 'nullable',
                'course_rating' => 'required',
                'slope_rating' => 'required',
            ]);
            $model = $this->teeBox->findOrfail($id)->update($datas);
            $t_golf_course_id =$this->teeBox->findOrfail($id)->t_golf_course_id;

            DB::commit();
            // return $this->web->updateBack();
            return redirect()->route('golf-course.teebox.index', ['golf_course_id' => $t_golf_course_id])->with('success', 'Data successfully updated');;
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function destroy_tee(string $id)
    {
        DB::beginTransaction();
        try{
            $t_golf_course_id =$this->teeBox->findOrfail($id)->t_golf_course_id;
            $this->teeBox->findOrfail($id)->delete();
            DB::commit();
            return redirect()->route('golf-course.teebox.index', ['golf_course_id' => $t_golf_course_id])->with('success', 'Data successfully deleted');;
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function index_hole(Request $request, $golf_course_id)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Masters/GolfCourse/Hole/index',
                'title' => 'Data Hole',
                'hole' => $this->hole->where('course_id', $golf_course_id)->orderBy('hole_number', 'asc')->get(),
                'golfCourse' => $this->model->findOrfail($golf_course_id),
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function create_hole($id)
    {
        try{
            $data = [
                'content' => 'Admin/Masters/GolfCourse/Hole/addEdit',
                'title' => 'Add Hole',
                'golfCourse' => $this->model->findOrfail($id),
                'hole' => null,
                'MasterHole' => $this->config->where('parameter', 'm_hole')->get(),
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function store_hole(Request $request)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                'course_id' => 'required',
                'hole_number' => 'required',
                'par' => 'required',
            ]);
            $this->hole->create($datas);

            DB::commit();
            return redirect()->route('golf-course.hole.index', ['golf_course_id' => $datas['course_id']])->with('success', 'Data successfully added');;
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function edit_hole(string $id)
    {
        try{
            $data = [
                'content' => 'Admin/Masters/GolfCourse/Hole/addEdit',
                'title' => 'Edit Hole',
                'golfCourse'=> $this->hole->findOrfail($id),
                'hole'=> $this->hole->findOrfail($id),
                'MasterHole' => $this->config->where('parameter', 'm_hole')->get(),
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function update_hole(Request $request, string $id)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                'hole_number' => 'required',
                'par' => 'required',
            ]);
            $model = $this->hole->findOrfail($id)->update($datas);

            $course_id =$this->hole->findOrfail($id)->course_id;

            DB::commit();
            // return $this->web->updateBack();
            return redirect()->route('golf-course.hole.index', ['golf_course_id' => $course_id])->with('success', 'Data successfully updated');;
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function delete_hole(string $id)
    {
        DB::beginTransaction();
        try{

            $course_id =$this->hole->findOrfail($id)->course_id;

            $this->hole->findOrfail($id)->delete();
            DB::commit();
            return redirect()->route('golf-course.hole.index', ['golf_course_id' => $course_id])->with('success', 'Data successfully deleted');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }
    public function index_course_area(Request $request, $golf_course_id)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Masters/GolfCourse/CourseArea/index',
                'title' => 'Data Course Area',
                'course_area' => $this->course_area->where('course_id', $golf_course_id)->get(),
                'golfCourse' => $this->model->findOrfail($golf_course_id),
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }


    public function create_course_area($id)
    {
        try{
            $data = [
                'content' => 'Admin/Masters/GolfCourse/CourseArea/addEdit',
                'title' => 'Add Course Area',
                'golfCourse' => $this->model->findOrfail($id),
                'course_area' => null,
                'MasterHole' => $this->config->where('parameter', 'm_hole')->get(),
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function store_course_area(Request $request)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                'course_id' => 'required',
                'course_name' => 'required',
                'holes_number' => 'required',
            ]);
            $this->course_area->create($datas);

            DB::commit();
            return redirect()->route('golf-course.course_area.index', ['golf_course_id' => $datas['course_id']])->with('success', 'Data successfully added');;
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function edit_course_area(string $id)
    {
        try{
            $data = [
                'content' => 'Admin/Masters/GolfCourse/CourseArea/addEdit',
                'title' => 'Edit Course Area',
                'course_area'=> $this->course_area->findOrfail($id),
                'golfCourse'=> $this->course_area->findOrfail($id)->course_id,

            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function update_course_area(Request $request, string $id)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                'course_name' => 'required',
                'holes_number' => 'required',
            ]);
            $model = $this->course_area->findOrfail($id)->update($datas);

            $course_id =$this->course_area->findOrfail($id)->course_id;

            DB::commit();
            // return $this->web->updateBack();
            return redirect()->route('golf-course.course_area.index', ['golf_course_id' => $course_id])->with('success', 'Data successfully updated');;
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function delete_course_area(string $id)
    {
        DB::beginTransaction();
        try{

            $course_id =$this->course_area->findOrfail($id)->course_id;

            $this->course_area->findOrfail($id)->delete();
            DB::commit();
            return redirect()->route('golf-course.course_area.index', ['golf_course_id' => $course_id])->with('success', 'Data successfully deleted');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function getCourseAreas($id)
    {
        $areas = $this->course_area->where('course_id', $id)
            ->orderBy('id', 'asc')
            ->get(['id', 'course_name', 'holes_number']);

        return response()->json($areas);
    }

}
