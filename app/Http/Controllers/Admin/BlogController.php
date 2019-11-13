<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Blog;
use Illuminate\Support\Facades\Input;
use DB;
use Image;
use App\Tag;
use App\Comment;
use App\BlogTag;

class BlogController extends Controller
{
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
        return view('admin.blog_list');
    }
    
    /**
     * Fetch all the users from database table.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchblogs() {
        $start = Input::get('iDisplayStart');      // Offset
        $length = Input::get('iDisplayLength');     // Limit
        $sSearch = Input::get('sSearch');            // Search string
        $col = Input::get('iSortCol_0');         // Column number for sorting
        $sortType = Input::get('sSortDir_0');         // Sort type
        $where = '';

        // Datatable column number to table column name mapping
        $arr = array(
            0 => 't1.id',
            2 => 't1.meta_title',
            3 => 't1.title',
            4 => 't1.created_by',
            5 => 't1.created_at',
        );

        // Map the sorting column index to the column name
        $sortBy = $arr[$col];
        if ($sortBy == '') {
            $sortBy = "t1.id";
        }

        if ($sSearch != '') {
            $sSearch = addslashes($sSearch);
            $where = " WHERE t1.meta_title LIKE ('%" . $sSearch . "%') OR t1.title LIKE ('%" . $sSearch . "%') OR t1.created_by LIKE ('%" . $sSearch . "%') ";
        }
        // Get the records after applying the datatable filters
        $blogs = DB::select(
                        DB::raw("SELECT t1.id, t1.meta_title, t1.title, t1.feature_image, t1.created_at, t1.created_by FROM blogs t1 " .
                                $where . " ORDER BY " . $sortBy . " " . $sortType . " LIMIT " . $start . ", " . $length)
        );

        // Get the total count without any condition to maintian the pagination
        $blogCount = DB::select(
                        DB::raw("SELECT t1.id, t1.meta_title, t1.title, t1.feature_image, t1.created_at, t1.created_by FROM blogs t1 " .
                                $where)
        );

        // Assign it to the datatable pagination variable
        $iTotal = count($blogCount);

        $response = array(
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iTotal,
            'aaData' => array()
        );

        $k = 0;
        if (count($blogs) > 0) {
            foreach($blogs as $blog) {
                $response['aaData'][$k] = array(
                    0 => $k + 1,
                    1 => '<img src="' . (isset($blog->feature_image) ? url('public/images/blog/' . $blog->feature_image) : url('public/images/blog/default.png')). '" height="50px" width="50px" />',
                    2 => $blog->meta_title,
                    3 => $blog->title,
                    4 => $blog->created_by,
                    5 => date('d-m-Y', strtotime($blog->created_at)),
                    6 => '<a href="blog/' . $blog->id . '" '
                    . 'class="delete hidden-xs hidden-sm" title="Edit">'
                    . '<i class="fa fa-pencil text-warning"></i></a> &nbsp;'
                    . ' <a href="blog/delete/' . $blog->id . '" c'
                    . 'lass="delete hidden-xs hidden-sm" title="Delete" '
                    . 'onclick=\'return confirm("Are you sure you want to delete this category?")\'>'
                    . '<i class="fa fa-trash text-danger"></i></a>'
                );
                $k++;
            }
        }
        return response()->json($response);
    }
    /**
     * Show add form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null) {
        $tags = Tag::orderBy('name')->pluck('name', 'id');
        if (isset($id)) {
            $blog = Blog::with('blogtags')->findOrFail($id);
            
            return view('admin.add_blog', compact('blog', 'tags'));
        } else {
            return view('admin.add_blog', compact('tags'));
        }
    }
    /**
     * Add new blog post
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_blog(Request $request, $id = null){
        $this->validate($request, [
            'feature_image' => 'image|mimes:jpeg,png,jpg,gif,svg,bmp|max:2048',
            'meta_title' => 'required|string|max:255',
            'meta_desc' => 'required',
            'title' => 'required|string|max:255',
            'content' => 'required',
            'created_by' => 'required',
        ]);
        try {
            if (isset($id)) {
                $blog = Blog::with('blogtags')->findOrFail($id);
                $blog->slug = $this->createSlug($request->title, $id);
                $msg = 'Blog updated successfully';
                $blogtags = array();
                foreach($blog->blogtags as $blogtag)
                {
                    $blogtags[] = $blogtag->tag_id;
                }
                $tag_to_insert = array_diff($request->tags, $blogtags);
                $tag_to_remove = array_diff($blogtags, $request->tags);
            } else {
                $blog = new Blog;
                $blog->slug = $this->createSlug($request->title);
                $msg = 'Blog added successfully';
            }
            $allowedExtensions = ['jpeg', 'jpg', 'png', 'PNG', 'gif', 'svg', 'bmp'];
            // upload user avatar
            if ($request->hasFile('feature_image')) {
                $originaldestinationPath = public_path('/images/blog/');
                if (isset($id) && file_exists($originaldestinationPath . '/' . $blog->feature_image)) {
                    @unlink($originaldestinationPath . '/' . $blog->feature_image);
                }
                $file = $request->file('feature_image');
                $extension = $file->getClientOriginalExtension();
                $fileName = rand(10000, 99999) . '.' . $extension;
                if (in_array($extension, $allowedExtensions)) {
                    $compressed_image = Image::make($file->getRealPath());
                    $compressed_image->fit(348, 239);
                    //$compressed_image->resize(348, 239);
                    $compressed_image->save($originaldestinationPath.$fileName);
                    //$file->move($originaldestinationPath, $fileName);
                }
                $blog->feature_image = $fileName;
            }
            
            $blog->meta_title = $request->meta_title;
            $blog->meta_desc = $request->meta_desc;
            $blog->title = $request->title;
            $blog->content = $request->content;
            //$blog->tags = implode(',' , $request->tags);
            $blog->created_by = $request->created_by;
            $blog->save();
            $lastInsertId = $blog->id;
            if(isset($id)){
                foreach($tag_to_insert as $tag) {
                    // execute while updating postcast
                    $tags[] = [
                        'blog_id' => $id,
                        'tag_id' => $tag,
                        'created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now()
                    ];
                }
                // remove deselected option from database table
                if(!empty($tag_to_remove)){
                    BlogTag::whereIn('tag_id', $tag_to_remove)->where('blog_id', $id)->delete();
                }
            } else {
                // execute while adding new postcast
                foreach($request->tags as $tag) {
                    $tags[] = [
                        'blog_id' => $lastInsertId,
                        'tag_id' => $tag,
                        'created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now()
                    ];
                }
                
            }
            if(isset($tags)){
                BlogTag::insert($tags);
            }
            return redirect('/admin/blog_list')->with('success', $msg);
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Some error occur!! Please try again.');
        }
    }
    
    public function comment_approval(){
        return view('admin.comment_approval_list');
    }
    
    /**
     * Fetch all the unapproved comment from database table.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchBlogUnapprovedCmt() {
        $start = Input::get('iDisplayStart');      // Offset
        $length = Input::get('iDisplayLength');     // Limit
        $sSearch = Input::get('sSearch');            // Search string
        $col = Input::get('iSortCol_0');         // Column number for sorting
        $sortType = Input::get('sSortDir_0');         // Sort type
        $where = '';

        // Datatable column number to table column name mapping
        $arr = array(
            0 => 't1.id',
            2 => 't2.title',
            4 => 't3.name',
            5 => 't1.created_at',
        );

        // Map the sorting column index to the column name
        $sortBy = $arr[$col];
        if ($sortBy == '') {
            $sortBy = "t1.id";
        }

        if ($sSearch != '') {
            $sSearch = addslashes($sSearch);
            $where = " AND (t2.title LIKE ('%" . $sSearch . "%') OR t1.comment LIKE ('%" . $sSearch . "%') OR t3.name LIKE ('%" . $sSearch . "%'))";
        }
        // Get the records after applying the datatable filters
        $comments = DB::select(
            DB::raw("SELECT t1.id, t1.comment, t1.created_at, t1.publish, t2.title,"
                    . " t2.feature_image, t3.name FROM comments t1 INNER JOIN blogs "
                    . "t2 ON t1.blog_or_pod_id=t2.id INNER JOIN users t3 "
                    . "ON t1.user_id=t3.id WHERE t1.type='blog' AND t1.publish=0" .
                    $where . " ORDER BY " . $sortBy . " " . $sortType . " LIMIT " . $start . ", " . $length)
        );

        // Get the total count without any condition to maintian the pagination
        $commentCount = DB::select(
                        DB::raw("SELECT t1.id, t1.comment, t1.created_at, t1.publish, t2.title, t2.feature_image, t3.name FROM comments t1 INNER JOIN blogs t2 ON t1.blog_or_pod_id=t2.id INNER JOIN users t3 ON t1.user_id=t3.id WHERE t1.type='blog' " .
                                $where)
        );

        // Assign it to the datatable pagination variable
        $iTotal = count($commentCount);

        $response = array(
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iTotal,
            'aaData' => array()
        );

        $k = 0;
        if (count($comments) > 0) {
            foreach($comments as $comment) {
                $response['aaData'][$k] = array(
                    0 => $k + 1,
                    1 => '<img src="' . (isset($comment->feature_image) ? url('public/images/blog/' . $comment->feature_image) : url('public/images/blog/default.png')). '" height="50px" width="50px" />',
                    2 => $comment->title,
                    3 => $comment->comment,
                    4 => $comment->name,
                    5 => date('d-m-Y', strtotime($comment->created_at)),
                    6 => ($comment->publish == 1 ? '<a href="blog/publish/' . $comment->id . '" title="published" '
                    . 'onclick=\'return confirm("Are you sure you want to unpublish this comment?")\'>'
                    . '<i class="fa fa-cloud-upload text-success"></i></a>' : '<a href="blog/publish/' . $comment->id . '" title="unpublished" '
                    . 'onclick=\'return confirm("Are you sure you want to publish this comment?")\'>'
                    . '<i class="fa fa-cloud-upload text-danger"></i></a>&nbsp;')
                    . ' <a href="comment/delete/' . $comment->id . '" c'
                    . 'lass="delete hidden-xs hidden-sm" title="Delete" '
                    . 'onclick=\'return confirm("Are you sure you want to delete this comment?")\'>'
                    . '<i class="fa fa-trash text-danger"></i></a>'
                );
                $k++;
            }
        }
        return response()->json($response);
    }
    // publish the comment of blog
    public function publishComment($id){
        $getDetail = Comment::select('publish')->where(['id' => $id])->first();
        if ($getDetail->publish == '1') {
            $data = array(
                'publish' => '0'
            );
            $msg = "Comment unpublish successfully";
        } else {
            $data = array(
                'publish' => '1'
            );
            $msg = "Comment publish successfully";
        }

        Comment::where(['id' => $id])->update($data);
        return redirect()->back()->with('success', $msg);
    }
    // delete comment from database table
    public function destroyComment($id){
        try {
            $comment = Comment::findOrFail($id);
            $comment->delete();
            return redirect()->back()->with('success', 'Comment Deleted Successfully');
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Some error occur!! Please try again.');
        }
    }
    
    // create unique slug for blog
    public function createSlug($title, $id = 0)
    {
        // Normalize the title
        $slug = str_slug($title);

        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = $this->getRelatedSlugs($slug, $id);

        // If we haven't used it before then we are all good.
        if (! $allSlugs->contains('slug', $slug)){
            return $slug;
        }

        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug . '-' . $i;
            if (! $allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }

        throw new \Exception('Can not create a unique slug');
    }
    
    protected function getRelatedSlugs($slug, $id = 0)
    {
        return Blog::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        try {
            $blog = Blog::findOrFail($id);
            $destinationPath = public_path('/images/blog/');
            if (file_exists($destinationPath . $blog->feature_image)) {
                @unlink($destinationPath . $blog->feature_image);
            }
            $blog->delete();
            return redirect()->back()->with('success', 'Blog Deleted Successfully');
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Some error occur!! Please try again.');
        }
    }
    
    public function gallary(){
        $images = DB::table('blog_images')->select('image')->get();
        return view('admin.image_list', compact('images'));
    }
    
    public function upload_image(Request $request){
        // move images from temp folder to targeted folder and save data to product image table
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'PNG', 'gif', 'bmp'];
        $originaldestinationPath = public_path('/images/blog/');
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $extension = $image->getClientOriginalExtension();
                $fileName = rand(10000, 99999) . '.' . $extension;
                if (in_array($extension, $allowedExtensions)) {
                    $image->move($originaldestinationPath, $fileName);
                }
                DB::table('blog_images')->insert(
                    ['image' => $fileName, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()]
                );
            }
            return redirect('admin/gallary')->with('success', 'Image Added Successfully');
        }
        return view('admin.add_image');
    }
    
    public function upload_ck_image(Request $request){
        $CKEditor = $request->CKEditor;
	$funcNum  = $request->CKEditorFuncNum;
        $originaldestinationPath = public_path('/images/blog/');
	$message  = $url = '';
	if ($request->hasFile('upload')) {
		$file = $request->file('upload');
		if ($file->isValid()) {
                    
                    $filename = rand(1000,9999).$file->getClientOriginalName();
                    $file->move(public_path().'/images/blog/', $filename);
                    
                    $url = url('public/images/blog/' . $filename);
		} else {
			$message = 'An error occurred while uploading the file.';
		}
	} else {
		$message = 'No file uploaded.';
	}
	return '<script>window.parent.CKEDITOR.tools.callFunction('.$funcNum.', "'.$url.'", "'.$message.'")</script>';
    }
    
    /**
     * Show add form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tag_create($id = null) {
        if (isset($id)) {
            $tag = Tag::findOrFail($id);
            return view('admin.add_tag', compact('tag'));
        } else {
            return view('admin.add_tag');
        }
    }
    
    /**
     * Add new blog post
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_tag(Request $request, $id = null){
        $this->validate($request, [
            'name' => 'required'
        ]);
        try {
            if (isset($id)) {
                $tag = Tag::findOrFail($id);
                $msg = 'Tag updated successfully';
            } else {
                $tag = new Tag;
                $msg = 'Tag added successfully';
            }
            $tag->name = $request->name;
            $tag->save();
            return redirect('/admin/tag_list')->with('success', $msg);
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Some error occur!! Please try again.');
        }
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tag_list() {
        return view('admin.tag_list');
    }
    
    /**
     * Fetch all the users from database table.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchtags() {
        $start = Input::get('iDisplayStart');      // Offset
        $length = Input::get('iDisplayLength');     // Limit
        $sSearch = Input::get('sSearch');            // Search string
        $col = Input::get('iSortCol_0');         // Column number for sorting
        $sortType = Input::get('sSortDir_0');         // Sort type
        $where = '';

        // Datatable column number to table column name mapping
        $arr = array(
            0 => 't1.id',
            1 => 't1.name',
            2 => 't1.created_at',
        );

        // Map the sorting column index to the column name
        $sortBy = $arr[$col];
        if ($sortBy == '') {
            $sortBy = "t1.id";
        }

        if ($sSearch != '') {
            $sSearch = addslashes($sSearch);
            $where = " WHERE t1.name LIKE ('%" . $sSearch . "%')";
        }
        // Get the records after applying the datatable filters
        $tags = DB::select(
                        DB::raw("SELECT t1.id, t1.name, t1.created_at FROM tags t1 " .
                                $where . " ORDER BY " . $sortBy . " " . $sortType . " LIMIT " . $start . ", " . $length)
        );

        // Get the total count without any condition to maintian the pagination
        $tagCount = DB::select(
                        DB::raw("SELECT t1.id, t1.name, t1.created_at FROM tags t1 " .
                                $where)
        );

        // Assign it to the datatable pagination variable
        $iTotal = count($tagCount);

        $response = array(
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iTotal,
            'aaData' => array()
        );

        $k = 0;
        if (count($tags) > 0) {
            foreach($tags as $tag) {
                $response['aaData'][$k] = array(
                    0 => $k + 1,
                    1 => $tag->name,
                    2 => date('d-m-Y', strtotime($tag->created_at)),
                    3 => '<a href="tag/' . $tag->id . '" '
                    . 'class="delete hidden-xs hidden-sm" title="Edit">'
                    . '<i class="fa fa-pencil text-warning"></i></a> &nbsp;'
                    . ' <a href="tag/delete/' . $tag->id . '" c'
                    . 'lass="delete hidden-xs hidden-sm" title="Delete" '
                    . 'onclick=\'return confirm("Are you sure you want to delete this category?")\'>'
                    . '<i class="fa fa-trash text-danger"></i></a>'
                );
                $k++;
            }
        }
        return response()->json($response);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tag_destroy($id) {
        try {
            $tag = Tag::findOrFail($id);
            $tag->delete();
            return redirect()->back()->with('success', 'Tag Deleted Successfully');
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Some error occur!! Please try again.');
        }
    }
}
