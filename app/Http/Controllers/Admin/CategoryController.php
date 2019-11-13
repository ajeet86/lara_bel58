<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\Input;
use DB;
use Image;
use Redirect;

class CategoryController extends Controller {

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
        return view('admin.category_list');
    }

    /**
     * Fetch all the users from database table.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchcategories() {

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
            3 => 't1.cat_desc',
            4 => 't1.created_at',
        );

        // Map the sorting column index to the column name
        $sortBy = $arr[$col];
        if ($sortBy == '') {
            $sortBy = "t1.id";
        }

        if ($sSearch != '') {
			$sSearch = addslashes($sSearch);
            $where = " WHERE t1.name LIKE ('%" . $sSearch . "%') OR t1.cat_desc LIKE ('%" . $sSearch . "%') ";
        }
        // Get the records after applying the datatable filters
        $categories = DB::select(
                        DB::raw("SELECT t1.id, t1.name, t1.cat_desc, t1.cat_image, t1.feature, t1.created_at FROM categories t1 " .
                                $where . " ORDER BY " . $sortBy . " " . $sortType . " LIMIT " . $start . ", " . $length)
        );

        // Get the total count without any condition to maintian the pagination
        $userCount = DB::select(
                        DB::raw("SELECT t1.id, t1.name, t1.cat_desc, t1.cat_image, t1.feature, t1.created_at FROM categories t1 " .
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
                    1 => '<img src="' . (isset($category->cat_image) ? url('public/images/categories/original/' . $category->cat_image) : url('public/images/categories/default.png')). '" height="50px" width="50px" />',
                    2 => $category->name,
                    3 => $category->cat_desc,
                    4 => date('d-m-Y', strtotime($category->created_at)),
                    5 => '<a href="category/' . $category->id . '" '
                    . 'class="delete hidden-xs hidden-sm" title="Edit">'
                    . '<i class="fa fa-pencil text-warning"></i></a> &nbsp;'
                    . ' <a href="category/delete/' . $category->id . '" c'
                    . 'lass="delete hidden-xs hidden-sm" title="Delete" '
                    . 'onclick=\'return confirm("Are you sure you want to delete this category?")\'>'
                    . '<i class="fa fa-trash text-danger"></i></a> &nbsp;'
                    . ($category->feature == 1 ? '<a href="feature_category/' . $category->id . '" title="featured" '
                    . 'onclick=\'return confirm("Are you sure you want to make non-feature this category?")\'>'
                    . '<i class="fa fa-facebook text-success"></i></a>' : '<a href="feature_category/' . $category->id . '" title="non-featured" '
                    . 'onclick=\'return confirm("Are you sure you want to make feature this category?")\'>'
                    . '<i class="fa fa-facebook"></i></a>'),
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
            $category = Category::select('id', 'name', 'cat_desc', 'feature', 'cat_image')->findOrFail($id);
            return view('admin.add_category', compact('category'));
        } else {
            return view('admin.add_category');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id = null) {
        $this->validate($request, [
            'name' => 'required|string|max:100',
			'cat_image' => 'image|mimes:jpeg,png,jpg,gif,svg,bmp|max:2048'
        ]);

        try {
            if (isset($id)) {
                $category = Category::findOrFail($id);
                $msg = 'Category updated successfully';
            } else {
                $category = new Category;
                $msg = 'Category added successfully';
            }
            $category->name = $request->name;
            $category->cat_desc = $request->cat_desc;
            $category->feature = isset($request->feature) ? 1 : 0;
            $allowedExtensions = ['jpeg', 'jpg', 'png', 'PNG', 'gif', 'svg', 'bmp'];
            // upload user avatar
            if ($request->hasFile('cat_image')) {
                $mediumdestinationPath = public_path('/images/categories/');
                $originaldestinationPath = public_path('/images/categories/original/');
                /* if (isset($id) && file_exists($mediumdestinationPath . '/' . $category->cat_image)) {
                  @unlink($destinationPath . '/' . $category->cat_image);
                  } */
                if (isset($id) && file_exists($originaldestinationPath . '/' . $category->cat_image)) {
                    @unlink($originaldestinationPath . '/' . $category->cat_image);
                }
                $file = $request->file('cat_image');
                $extension = $file->getClientOriginalExtension();
                $fileName = rand(10000, 99999) . '.' . $extension;
                if (in_array($extension, $allowedExtensions)) {
                    // $compressed_image = Image::make($file->getRealPath());					
                    // $medium = $compressed_image->resize(500, 500);
                    //$mediumdestinationPath = public_path('/images/categories/');
                    // $compressed_image->save($mediumdestinationPath, 80);
                    //$compressed_image->encode($extension, 75);
                    //$large = $compressed_image->resize(450, 600);
                    //$largedestinationPath = resource_path() . '/assets/images/uploads/category/large/';
                    //$compressed_image->save($largedestinationPath.$fileName, 80);
                    //$fileName = time() .'.'. $file->getClientOriginalExtension();
                    $file->move($originaldestinationPath, $fileName);
                }
                $category->cat_image = $fileName;
            }
            $category->save();
            return Redirect::to('/admin/category_list')->with('success', $msg);
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
            $category = Category::findOrFail($id);
            $destinationPath = public_path('/images/categories');
            if (file_exists($destinationPath . '/original/' . $category->cat_image)) {
                @unlink($destinationPath . '/original/' . $category->cat_image);
            }
            $category->delete();
            return Redirect::back()->with('success', 'Category Deleted Successfully');
        } catch (Exception $ex) {
            return Redirect::back()->with('error', 'Some error occur!! Please try again.');
        }
    }
    
    /* start category feature functionality */

    public function feature_category($id) {

        $getDetail = Category::where(['id' => $id])->first();
        
        if ($getDetail->feature == '1') {
            $data = array(
                'feature' => '0'
            );
        } else {
            $data = array(
                'feature' => '1'
            );
        }

        Category::where(['id' => $id])->update($data);
        return Redirect::back();
    }

}
