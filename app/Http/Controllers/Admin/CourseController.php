<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use DB;
use Image;
use Redirect;
use Hash;
use App\Category;
use Storage;
//use FFMpeg;

class CourseController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    public function index() {
        return view('admin.course_list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null) {
        $categories = Category::orderBy('name')->pluck('name', 'id');
        if (isset($id)) {
            $course = Course::findOrFail($id);
            return view('admin.add_course', compact('categories', 'course'));
        } else {
            return view('admin.add_course', compact('categories'));
        }
    }


    public function fetchCourses() {

        $start = Input::get('iDisplayStart');      // Offset
        $length = Input::get('iDisplayLength');     // Limit
        $sSearch = Input::get('sSearch');            // Search string
        $col = Input::get('iSortCol_0');         // Column number for sorting
        $sortType = Input::get('sSortDir_0');         // Sort type
        $where = '';

        // Datatable column number to table column name mapping
        $arr = array(
            0 => 't1.id',
            1 => 't2.name',
            2 => 't1.title',
            3 => 't1.level',
            4 => 't1.created_at',
        );

		
		
        // Map the sorting column index to the column name
        $sortBy = $arr[$col];
        if ($sortBy == '') {
            $sortBy = "t1.id";
        }

       
        // Get the records after applying the datatable filters
        
        if ($sSearch != '') {
            $sSearch = addslashes($sSearch);
            $where = " WHERE t1.title LIKE ('%" . $sSearch . "%') OR t1.level LIKE ('%" . $sSearch . "%') "
                    . "OR t1.created_at LIKE ('%" . $sSearch . "%') OR t2.name LIKE ('%" . $sSearch . "%')";
        }
        // Get the records after applying the datatable filters
        $courses = DB::select(
            DB::raw("SELECT t1.id, t1.title, t2.name, t1.level, t1.created_at FROM courses t1 JOIN categories t2 ON t1.cat_id = t2.id" .
                                $where . " ORDER BY " . $sortBy . " " . $sortType . " LIMIT " . $start . ", " . $length)
        );

        // Get the total count without any condition to maintian the pagination
        $courseCount = DB::select(
            DB::raw("SELECT t1.id, t1.title, t2.name, t1.level, t1.created_at FROM courses t1 JOIN categories t2 ON t1.cat_id = t2.id " .
                                $where)
        );

		
        // Assign it to the datatable pagination variable
        $iTotal = count($courseCount);

        $response = array(
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iTotal,
            'aaData' => array()
        );

        $k = 0;
        if (count($courses) > 0) {
            foreach ($courses as $course) {
                $response['aaData'][$k] = array(
                    0 => $k + 1,
                    1 => $course->name,
                    2 => $course->title,
                    3 => $course->level,
                    4 => date('d-m-Y', strtotime($course->created_at)),
                    5 => '<a href="course/' . $course->id . '" '
                    . 'class="delete hidden-xs hidden-sm" title="Edit">'
                    . '<i class="fa fa-pencil text-warning"></i></a> &nbsp;'
                    . ' <a href="course/delete/' . $course->id . '"'
                    . ' class="delete hidden-xs hidden-sm" title="Delete"'
                    . 'onclick=\'return confirm("Are you sure you want to delete this role?")\'>'
                    . '<i class="fa fa-trash text-danger"></i></a>',
                );
                $k++;
            }
        }
		
		
		
        return response()->json($response);
    }
    
    public function store(Request $request, $id = null) {
        
        $this->validate($request, [
            'category' => 'required',
            'title' => 'required|string|max:255',
            'video' =>'mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi',
            'level' => 'required',
        ]);
        try {
            if (isset($id)) {
                $course = Course::findOrFail($id);
                $msg = 'Course updated successfully';
            } else {
                $course = new Course;
                $msg = 'Course added successfully';
            }
            $course->cat_id = $request->category;
            $course->title = $request->title;
            $course->description = $request->description;
            $course->level = $request->level;
			
			//upload course video
            if ($request->hasFile('video_name')) {
                $videoDestinationPath = asset('/storage/app/uploads/files/course_videos');
                if (isset($id) && Storage::exists('uploads/files/course_videos/' . $course->video_name)) {
                    Storage::delete('uploads/files/course_videos/' . $course->video_name);
                }
                $uniqueid = uniqid();
                $file = $request->file('video_name');
                $original_name = $file->getClientOriginalName();
                $size = $file->getSize();
                $extension = $file->getClientOriginalExtension();
                $videoname = \Carbon\Carbon::now()->format('Ymd').'_'.$uniqueid.'.'.$extension;
                //$audiopath = url('/storage/upload/files/audio/' . $filename);
                $path = $file->storeAs('uploads/files/course_videos/', $videoname);
                $course->video_name = $videoname;
            }
            
            // upload course pdf files
            if ($request->hasFile('file_name')) {
                $allowedExtensions = ['pdf'];
                $pdfdestinationPath = asset('/storage/app/uploads/files/course_pdf');
                if (isset($id) && Storage::exists('uploads/files/course_pdf/' . $course->file_name)) {
                    Storage::delete('uploads/files/course_pdf/' . $course->file_name);
                }
                $file = $request->file('file_name');
                $extension = $file->getClientOriginalExtension();
                $fileName = \Carbon\Carbon::now()->format('Ymd').'_'.rand(10000, 99999) . '.' . $extension;
                if (in_array($extension, $allowedExtensions)) {
                    $path = $file->storeAs('uploads/files/course_pdf/', $fileName);
                }
                $course->file_name = $fileName;
            }
			
		
			
			$allowedExtensions = ['jpeg', 'jpg', 'png', 'PNG', 'gif', 'svg', 'bmp'];
            // upload user avatar
            if ($request->hasFile('thumbnail')) {
                $originaldestinationPath = public_path('/images/thumbnail/');
                if (isset($id) && file_exists($originaldestinationPath . '/' . $course->thumbnail)) {
                    @unlink($originaldestinationPath . '/' . $course->thumbnail);
                }
				
				//print_r($originaldestinationPath . $course->thumbnail);die;
                $file = $request->file('thumbnail');
                $extension = $file->getClientOriginalExtension();
                $fileName = rand(10000, 99999) . '.' . $extension;
                if (in_array($extension, $allowedExtensions)) {
                    $compressed_image = Image::make($file->getRealPath());
                    $compressed_image->fit(348, 239);
                    //$compressed_image->resize(348, 239);
                    $compressed_image->save($originaldestinationPath.$fileName);
                    //$file->move($originaldestinationPath, $fileName);
                }
                $course->thumbnail = $fileName;
            }
            $course->save();
            return Redirect::to('/admin/course_list')->with('success', $msg);
        } catch (Exception $ex) {
            return Redirect::back()->with('error', 'Some error occur!! Please try again.');
        }
        
    }
	
	    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        try {
            $course = Course::findOrFail($id);
            $originaldestinationPath = public_path('/images/thumbnail/');
            if (isset($id) && file_exists($originaldestinationPath . '/' . $course->thumbnail)) {
                @unlink($originaldestinationPath . '/' . $course->thumbnail);
            }
            $videoDestinationPath = asset('/storage/app/uploads/files/course_videos');
            if (isset($id) && Storage::exists('uploads/files/course_videos/' . $course->video_name)) {
                Storage::delete('uploads/files/course_videos/' . $course->video_name);
            }
            $pdfdestinationPath = asset('/storage/app/uploads/files/course_pdf');
            if (isset($id) && Storage::exists('uploads/files/course_pdf/' . $course->file_name)) {
                Storage::delete('uploads/files/course_pdf/' . $course->file_name);
            }
            $course->delete();
            return Redirect::back()->with('success', 'Course Deleted Successfully');
        } catch (Exception $ex) {
            return Redirect::back()->with('error', 'Some error occur!! Please try again.');
        }
    }
	
}
