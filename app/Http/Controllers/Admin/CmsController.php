<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cms;
use Illuminate\Support\Facades\Input;
use DB;
use Image;
use Redirect;

class CmsController extends Controller {

    /**
     * Class constructor.
     *
     * 
     */
    public function __construct() {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('admin.cms_list');
    }

    /**
     * Fetch all the users from database table.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchcms() {

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
            3 => 't1.description',
            4 => 't1.created_at',
        );

        // Map the sorting column index to the column name
        $sortBy = $arr[$col];
        if ($sortBy == '') {
            $sortBy = "t1.id";
        }

        if ($sSearch != '') {
			$sSearch = addslashes($sSearch);
            $where = " WHERE t1.name LIKE ('%" . $sSearch . "%') ";
        }
        // Get the records after applying the datatable filters
        $categories = DB::select(
                        DB::raw("SELECT t1.id, t1.name, t1.description, t1.image, t1.feature, t1.created_at FROM cms t1 " .
                                $where . " ORDER BY " . $sortBy . " " . $sortType . " LIMIT " . $start . ", " . $length)
        );

        // Get the total count without any condition to maintian the pagination
        $userCount = DB::select(
                        DB::raw("SELECT t1.id, t1.name, t1.description, t1.image, t1.feature, t1.created_at FROM cms t1 " .
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
        if (count($categories) > 0) {
            foreach ($categories as $category) {
                $response['aaData'][$k] = array(
                    0 => $k + 1,
                    1 => '<img src="' . (isset($category->image) ? url('public/images/categories/original/' . $category->image) : url('public/images/categories/default.png')). '" height="50px" width="50px" />',
                    2 => $category->name,
                    3 => $category->description,
                    4 => date('d-m-Y', strtotime($category->created_at)),
                    5 => '<a href="cms/' . $category->id . '" '
                    . 'class="delete hidden-xs hidden-sm" title="Edit">'
                    . '<i class="fa fa-pencil text-warning"></i></a> &nbsp;'
                    . ' <a href="cms/delete/' . $category->id . '" c'
                    . 'lass="delete hidden-xs hidden-sm" title="Delete" '
                    . 'onclick=\'return confirm("Are you sure you want to delete this cms?")\'>'
                    . '<i class="fa fa-trash text-danger"></i></a> &nbsp;'
                    /*. ($category->feature == 1 ? '<a href="feature_cms/' . $category->id . '" title="featured" '
                    . 'onclick=\'return confirm("Are you sure you want to make feature this cms content?")\'>'
                    . '<i class="fa fa-facebook text-success"></i></a>' : '<a href="feature_cms/' . $category->id . '" title="non-featured" '
                    . 'onclick=\'return confirm("Are you sure you want to make non-feature this cms content?")\'>'
                    . '<i class="fa fa-facebook"></i></a>'),*/
                );
                $k++;
            }
        }
        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null) {
        if (isset($id)) {
            $category = Cms::select('id', 'name', 'description', 'feature', 'image')->findOrFail($id);
            return view('admin.add_cms', compact('category'));
        } else {
            return view('admin.add_cms');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id = null) {

    // echo "<pre>"; print_r($request->all()); die;

        $this->validate($request, [
            'name' => 'required|string|max:100',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg,bmp|max:2048'
        ]);

        try {
            if (isset($id)) {
                $category = cms::findOrFail($id);
                $msg = 'CMS updated successfully';
            } else {
                $category = new cms;
                $msg = 'CMS added successfully';
            }
            $category->name = $request->name;
            $category->description = $request->cat_desc;
            $category->feature = isset($request->feature) ? 1 : 0;
            $allowedExtensions = ['jpeg', 'jpg', 'png', 'PNG', 'gif', 'svg', 'bmp'];
            // upload user avatar
            if ($request->hasFile('image')) {
                $mediumdestinationPath = public_path('/images/categories/');
                $originaldestinationPath = public_path('/images/categories/original/');
                if (isset($id) && file_exists($originaldestinationPath . '/' . $category->cat_image)) {
                    @unlink($originaldestinationPath . '/' . $category->cat_image);
                }
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $fileName = rand(10000, 99999) . '.' . $extension;
                if (in_array($extension, $allowedExtensions)) {
                    $file->move($originaldestinationPath, $fileName);
                }
                $category->image = $fileName;
            }
          // echo "<pre>"; print_r($category); die;
            $category->save();
            return Redirect::to('/admin/cms_list')->with('success', $msg);
        } catch (Exception $ex) {
            return Redirect::back()->with('error', 'Some error occur!! Please try again.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        try {
            $cms = Cms::findOrFail($id);
            $destinationPath = public_path('/images/categories');
            if (file_exists($destinationPath . '/original/' . $cms->image)) {
                @unlink($destinationPath . '/original/' . $cms->image);
            }
            $cms->delete();
            return Redirect::back()->with('success', 'Page Deleted Successfully');
        } catch (Exception $ex) {
            return Redirect::back()->with('error', 'Some error occur!! Please try again.');
        }
    }
    
    /* start category feature functionality */

    public function feature_cms($id) {

        $getDetail = Cms::where(['id' => $id])->first();
        
        if ($getDetail->feature == '1') {
            $data = array(
                'feature' => '0'
            );
        } else {
            $data = array(
                'feature' => '1'
            );
        }

        Cms::where(['id' => $id])->update($data);
        return Redirect::back();
    }
    // Home Page CMS
    public function homepagecms(){
        $homecms = DB::table('home_cms')->first();
        return view('admin.add_home_cms', compact('homecms'));
    }
    
    public function savehomepagecms(Request $request){
        $this->validate($request, [
            'top_left_image' => 'image|mimes:jpeg,png,jpg,gif,svg,bmp|max:2048|dimensions:min_width=621,min_height=822',
            'top_desc' => 'required',
            'top_right_image' => 'image|mimes:jpeg,png,jpg,gif,svg,bmp|max:2048|dimensions:min_width=522,min_height=570',
            'about_us_image' => 'image|mimes:jpg,jpeg,png,PNG,gif,svg,bmp|max:2048|dimensions:min_width=758,min_height=520',
            'about_us_desc' => 'required',
            'what_we_do' => 'required',
            'banner_text' => 'required',
        ]);
        $homecms_image = DB::table('home_cms')->select('top_left_image', 'top_right_image', 'about_us_image')->first();
        $home_cms = [
                    'top_desc' => $request->top_desc,
                    'about_us_desc' => $request->about_us_desc,
                    'what_we_do' => $request->what_we_do,
                    'banner_text' => $request->banner_text
                ];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'PNG', 'gif', 'svg', 'bmp'];
        $imagePath = public_path('/images/categories/original/');
        if ($request->hasFile('top_left_image')) {
            if (file_exists($imagePath . '/' . $homecms_image->top_left_image)) {
                    @unlink($imagePath . '/' . $homecms_image->top_left_image);
            }
            $top_left_image = $request->file('top_left_image');
            $extension = $top_left_image->getClientOriginalExtension();
            $left_top_img_name = rand(10000, 99999) . '.' . $extension;
            if (in_array($extension, $allowedExtensions)) {
                $compressed_image = Image::make($top_left_image->getRealPath());					
                $compressed_image->resize(621, 822);
                $compressed_image->save($imagePath.$left_top_img_name);
                //$top_left_image->move($imagePath, $left_top_img_name);
            }
            $home_cms['top_left_image'] = $left_top_img_name;
        }
        if ($request->hasFile('top_right_image')) {
            if (file_exists($imagePath . '/' . $homecms_image->top_right_image)) {
                @unlink($imagePath . '/' . $homecms_image->top_right_image);
            }
            $top_right_image = $request->file('top_right_image');
            $extension = $top_right_image->getClientOriginalExtension();
            $right_top_img_name = rand(10000, 99999) . '.' . $extension;
            if (in_array($extension, $allowedExtensions)) {
                $compressed_image = Image::make($top_right_image->getRealPath());					
                $compressed_image->resize(522, 570);
                $compressed_image->save($imagePath.$right_top_img_name);
                //$top_right_image->move($imagePath, $right_top_img_name);
            }
            $home_cms['top_right_image'] = $right_top_img_name;
        }
        if ($request->hasFile('about_us_image')) {
            if (file_exists($imagePath . '/' . $homecms_image->about_us_image)) {
                @unlink($imagePath . '/' . $homecms_image->about_us_image);
            }
            $about_us_image = $request->file('about_us_image');
            $extension = $about_us_image->getClientOriginalExtension();
            $about_us_img_name = rand(10000, 99999) . '.' . $extension;
            if (in_array($extension, $allowedExtensions)) {
                $compressed_image = Image::make($about_us_image->getRealPath());				
                $compressed_image->resize(758, 520);
                $compressed_image->save($imagePath.$about_us_img_name);
                //$about_us_image->move($imagePath, $about_us_img_name);
            }
            $home_cms['about_us_image'] = $about_us_img_name;
        }
        if (DB::table('home_cms')->exists()) {
            DB::table('home_cms')
            ->update($home_cms);
            $msg = "Page Updated Successfully";
        } else {
            DB::table('home_cms')
            ->insert($home_cms);
            $msg = "Page Added Successfully";
        }
        return Redirect::back()->with('success', $msg);
    }

}
