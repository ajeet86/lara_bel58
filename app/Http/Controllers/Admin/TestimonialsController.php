<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Testimonial;
use Illuminate\Support\Facades\Input;
use DB;
use Image;
use Redirect;

class TestimonialsController extends Controller
{
    
    public function __construct() {
        $this->middleware('auth:admin');
    }


    public function index()
    {
    	$testimonials = Testimonial::all();
    	return view('admin.testimonials_list',['testimonials'=>$testimonials])->with('no', 1);
    }

    public function create($id = null)
    {
        if (isset($id)) {
            $testimonial = Testimonial::select('id', 'name', 'content', 'status', 'image')->findOrFail($id);
            return view('admin.add_testimonial', compact('testimonial'));
        } else {
            return view('admin.add_testimonial');
        }
    }

    public function fetchtestimonials() {

        $start = Input::get('iDisplayStart');      // Offset
        $length = Input::get('iDisplayLength');     // Limit
        $sSearch = Input::get('sSearch');            // Search string
        $col = Input::get('iSortCol_0');         // Column number for sorting
        $sortType = Input::get('sSortDir_0');         // Sort type
        $where = '';

        // Datatable column number to table column name mapping
        $arr = array(
            0 => 't1.id',
            2 => 't1.name',
            3 => 't1.content',
            4 => 't1.created_at',
        );

        // Map the sorting column index to the column name
        $sortBy = $arr[$col];
        if ($sortBy == '') {
            $sortBy = "t1.id";
        }

        if ($sSearch != '') {
			$sSearch = addslashes($sSearch);
            $where = " WHERE t1.name LIKE ('%" . $sSearch . "%') OR t1.content LIKE ('%" . $sSearch . "%') ";
        }
        // Get the records after applying the datatable filters
        $testimonials = DB::select(
                        DB::raw("SELECT t1.id, t1.name, t1.content, t1.image, t1.status, t1.created_at FROM testimonials t1 " .
                                $where . " ORDER BY " . $sortBy . " " . $sortType . " LIMIT " . $start . ", " . $length)
        );

        // Get the total count without any condition to maintian the pagination
        $userCount = DB::select(
                        DB::raw("SELECT t1.id, t1.name, t1.content, t1.image, t1.status, t1.created_at FROM testimonials t1 " .
                                $where)
        );

        // Assign it to the datatable pagination variable
        $iTotal = count($userCount);

        $response = array(
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iTotal,
            'aaData' => array()
        );

        $k = 0;
        if (count($testimonials) > 0) {
            foreach ($testimonials as $testimonial) {
                $response['aaData'][$k] = array(
                    0 => $k + 1,
                    1 => '<img src="' . (isset($testimonial->image) ? url('public/images/testimonials/original/' . $testimonial->image) : url('public/images/testimonials/default.png')). '" height="50px" width="50px" />',
                    2 => $testimonial->name,
                    3 => $testimonial->content,
                    4 => date('d-m-Y', strtotime($testimonial->created_at)),
                    5 => '<a href="testimonial/' . $testimonial->id . '" '
                    . 'class="delete hidden-xs hidden-sm" title="Edit">'
                    . '<i class="fa fa-pencil text-warning"></i></a> &nbsp;'
                    . ' <a href="testimonial/delete/' . $testimonial->id . '" c'
                    . 'lass="delete hidden-xs hidden-sm" title="Delete" '
                    . 'onclick=\'return confirm("Are you sure you want to delete this testimonial?")\'>'
                    . '<i class="fa fa-trash text-danger"></i></a> &nbsp;'
                    . ($testimonial->status == 1 ? '<a href="publish_testimonial/' . $testimonial->id . '" title="publish" '
                    . 'onclick=\'return confirm("Are you sure you want to unpublish this testimonial?")\'>'
                    . '<i class="fa fa-check-circle text-success"></i></a>' : '<a href="publish_testimonial/' . $testimonial->id . '" title="non-featured" '
                    . 'onclick=\'return confirm("Are you sure you want to publish this testimonial?")\'>'
                    . '<i class="fa fa-check-circle"></i></a>'),
                );
                $k++;
            }
        }
        return response()->json($response);
    }

    public function store(Request $request, $id = null) {
        $this->validate($request, [
            'name' => 'required|string|max:100',
			'image' => 'image|mimes:jpeg,png,jpg,gif,svg,bmp|max:2048'
        ]);

        try {
            if (isset($id)) {
                $testimonial = Testimonial::findOrFail($id);
                $msg = 'Testimonial updated successfully';
            } else {
                $testimonial = new Testimonial;
                $msg = 'Testimonial added successfully';
            }
            $testimonial->name = $request->name;
            $testimonial->content = $request->content;
            $allowedExtensions = ['jpeg', 'jpg', 'png', 'PNG', 'gif', 'svg', 'bmp'];
            // upload user avatar
            if ($request->hasFile('image')) {
                $mediumdestinationPath = public_path('/images/testimonials/');
                $originaldestinationPath = public_path('/images/testimonials/original/');
                if (isset($id) && file_exists($originaldestinationPath . '/' . $testimonial->image)) {
                    @unlink($originaldestinationPath . '/' . $testimonial->image);
                }
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $fileName = rand(10000, 99999) . '.' . $extension;
                if (in_array($extension, $allowedExtensions)) {
                    $file->move($originaldestinationPath, $fileName);
                }
                $testimonial->image = $fileName;
            }
            $testimonial->save();
            return Redirect::to('/admin/testimonials')->with('success', $msg);
        } catch (Exception $ex) {
            return Redirect::back()->with('error', 'Some error occur!! Please try again.');
        }
    }

    


    public function destroy($id) {
        try {
            $testimonial = Testimonial::findOrFail($id);
            $destinationPath = public_path('/images/testimonials');
            if (file_exists($destinationPath . '/original/' . $testimonial->image)) {
                @unlink($destinationPath . '/original/' . $testimonial->image);
            }
            $testimonial->delete();
            return Redirect::back()->with('success', 'Testimonial Deleted Successfully');
        } catch (Exception $ex) {
            return Redirect::back()->with('error', 'Some error occur!! Please try again.');
        }
    }


    public function publish_testimonial($id) {

        $getDetail = Testimonial::where(['id' => $id])->first();
        
        if ($getDetail->status == '1') {
            $data = array(
                'status' => '0'
            );
        } else {
            $data = array(
                'status' => '1'
            );
        }

        Testimonial::where(['id' => $id])->update($data);
        return Redirect::back();
    }
}
