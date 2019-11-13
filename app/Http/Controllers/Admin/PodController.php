<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PodCast;
use Illuminate\Support\Facades\Input;
use DB;
use Image;
use App\PodTag;
use App\Tag;
use App\Comment;

class PodController extends Controller
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
        return view('admin.pod_list');
    }
    
    /**
     * Show add form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null) {
        $tags = Tag::orderBy('name')->pluck('name', 'id');
        if (isset($id)) {
            $pod = PodCast::with('podtags')->findOrFail($id);
            return view('admin.add_pod', compact('pod', 'tags'));
        } else {
            return view('admin.add_pod', compact('tags'));
        }
    }
    
    /**
     * Add new pod post
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_pod(Request $request, $id = null){
        $this->validate($request, [
            'feature_image' => 'image|mimes:jpeg,png,jpg,gif,svg,bmp|max:2048',
            'meta_title' => 'required|string|max:255',
            'meta_desc' => 'required',
            'title' => 'required|string|max:255',
            'audio' =>'nullable|file|mimes:audio/mpeg,mpga,mp3,wav,aac',
            'content' => 'required',
            'created_by' => 'required',
        ]);
        try {
            if (isset($id)) {
                $pod = PodCast::with('podtags')->findOrFail($id);
                $pod->slug = $this->createSlug($request->title, $id);
                $msg = 'Pod updated successfully';
                // store tag id on array variable 
                $podtags = array();
                foreach($pod->podtags as $podtag)
                {
                    $podtags[] = $podtag->tag_id;
                }
                $tag_to_insert = array_diff($request->tags,$podtags);
                $tag_to_remove = array_diff($podtags, $request->tags);
            } else {
                $pod = new PodCast;
                $pod->slug = $this->createSlug($request->title);
                $msg = 'Pod added successfully';
            }
            $allowedExtensions = ['jpeg', 'jpg', 'png', 'PNG', 'gif', 'svg', 'bmp'];
            // upload user avatar
            if ($request->hasFile('feature_image')) {
                $originaldestinationPath = public_path('/images/pod/');
                if (isset($id) && file_exists($originaldestinationPath . '/' . $pod->feature_image)) {
                    @unlink($originaldestinationPath . '/' . $pod->feature_image);
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
                $pod->feature_image = $fileName;
            }
            if ($request->hasFile('audio')) {
                $audioDestinationPath = public_path('/storage/upload/files/audio');
                if (isset($id) && file_exists($audioDestinationPath . '/' . $pod->audio)) {
                    @unlink($audioDestinationPath . '/' . $pod->audio);
                }
                $uniqueid = uniqid();
                $file = $request->file('audio');
                $original_name = $file->getClientOriginalName();
                $size = $file->getSize();
                $extension = $file->getClientOriginalExtension();
                $filename = \Carbon\Carbon::now()->format('Ymd').'_'.$uniqueid.'.'.$extension;
                //$audiopath = url('/storage/upload/files/audio/' . $filename);
                $path = $file->storeAs('public/upload/files/audio/', $filename);
                $pod->audio = $filename;
            }
            $pod->meta_title = $request->meta_title;
            $pod->meta_desc = $request->meta_desc;
            $pod->title = $request->title;
            $pod->content = $request->content;
            $pod->created_by = $request->created_by;
            $pod->save();
            $lastInsertId = $pod->id;
            if(isset($id)){
                foreach($tag_to_insert as $tag) {
                    // execute while updating postcast
                    $tags[] = [
                        'pod_id' => $id,
                        'tag_id' => $tag,
                        'created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now()
                    ];
                }
                // remove deselected option from database table
                if(!empty($tag_to_remove)){
                    PodTag::whereIn('tag_id', $tag_to_remove)->where('pod_id', $id)->delete();
                }
            } else {
                // execute while adding new postcast
                foreach($request->tags as $tag) {
                    $tags[] = [
                        'pod_id' => $lastInsertId,
                        'tag_id' => $tag,
                        'created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now()
                    ];
                }
                
            }
            if(isset($tags)){
                PodTag::insert($tags);
            }
            return redirect('/admin/pod_list')->with('success', $msg);
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Some error occur!! Please try again.');
        }
    }
    
    // create unique slug for pod
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
        return PodCast::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }
    
    /**
     * Fetch all the pods from database table.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchpods() {
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
        $pods = DB::select(
            DB::raw("SELECT t1.id, t1.meta_title, t1.title, t1.feature_image, t1.created_at, t1.created_by FROM pod_casts t1 " .
                                $where . " ORDER BY " . $sortBy . " " . $sortType . " LIMIT " . $start . ", " . $length)
        );

        // Get the total count without any condition to maintian the pagination
        $podCount = DB::select(
                        DB::raw("SELECT t1.id, t1.meta_title, t1.title, t1.feature_image, t1.created_at, t1.created_by FROM pod_casts t1 " .
                                $where)
        );

        // Assign it to the datatable pagination variable
        $iTotal = count($podCount);

        $response = array(
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iTotal,
            'aaData' => array()
        );

        $k = 0;
        if (count($pods) > 0) {
            foreach($pods as $pod) {
                $response['aaData'][$k] = array(
                    0 => $k + 1,
                    1 => '<img src="' . (isset($pod->feature_image) ? url('public/images/pod/' . $pod->feature_image) : url('public/images/pod/default.png')). '" height="50px" width="50px" />',
                    2 => $pod->meta_title,
                    3 => $pod->title,
                    4 => $pod->created_by,
                    5 => date('d-m-Y', strtotime($pod->created_at)),
                    6 => '<a href="podcast/' . $pod->id . '" '
                    . 'class="delete hidden-xs hidden-sm" title="Edit">'
                    . '<i class="fa fa-pencil text-warning"></i></a> &nbsp;'
                    . ' <a href="pod/delete/' . $pod->id . '" c'
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
    public function destroy($id) {
        try {
            $pod = PodCast::findOrFail($id);
            $destinationPath = public_path('/images/pod/');
            if (file_exists($destinationPath . $pod->feature_image)) {
                @unlink($destinationPath . $pod->feature_image);
            }
            $audioDestinationPath = public_path('/storage/upload/files/audio');
            if (file_exists($audioDestinationPath . '/' .$pod->audio)) {
                @unlink($audioDestinationPath . '/' . $pod->audio);
            }
            $pod->delete();
            return redirect()->back()->with('success', 'Pod Deleted Successfully');
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Some error occur!! Please try again.');
        }
    }
    
    public function comment_approval(){
        return view('admin.pod_comment_approval_list');
    }
    
    /**
     * Fetch all the unapproved comment from database table.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchPodUnapprovedCmt() {
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
                    . " t2.feature_image, t3.name FROM comments t1 INNER JOIN pod_casts "
                    . "t2 ON t1.blog_or_pod_id=t2.id INNER JOIN users t3 "
                    . "ON t1.user_id=t3.id WHERE t1.type='pod' AND t1.publish=0" .
                    $where . " ORDER BY " . $sortBy . " " . $sortType . " LIMIT " . $start . ", " . $length)
        );

        // Get the total count without any condition to maintian the pagination
        $commentCount = DB::select(
                        DB::raw("SELECT t1.id, t1.comment, t1.created_at, t1.publish, t2.title, t2.feature_image, t3.name FROM comments t1 INNER JOIN pod_casts t2 ON t1.blog_or_pod_id=t2.id INNER JOIN users t3 ON t1.user_id=t3.id WHERE t1.type='pod' " .
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
                    1 => '<img src="' . (isset($comment->feature_image) ? url('public/images/pod/' . $comment->feature_image) : url('public/images/pod/default.png')). '" height="50px" width="50px" />',
                    2 => $comment->title,
                    3 => $comment->comment,
                    4 => $comment->name,
                    5 => date('d-m-Y', strtotime($comment->created_at)),
                    6 => ($comment->publish == 1 ? '<a href="pod/publish/' . $comment->id . '" title="published" '
                    . 'onclick=\'return confirm("Are you sure you want to unpublish this comment?")\'>'
                    . '<i class="fa fa-cloud-upload text-success"></i></a>' : '<a href="pod/publish/' . $comment->id . '" title="unpublished" '
                    . 'onclick=\'return confirm("Are you sure you want to publish this comment?")\'>'
                    . '<i class="fa fa-cloud-upload text-danger"></i></a>&nbsp;')
                    . ' <a href="pod_comment/delete/' . $comment->id . '" c'
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
    
    public function pod_ck_image(Request $request){
        $CKEditor = $request->CKEditor;
	$funcNum  = $request->CKEditorFuncNum;
        $originaldestinationPath = public_path('/images/pod/');
	$message  = $url = '';
	if ($request->hasFile('upload')) {
		$file = $request->file('upload');
		if ($file->isValid()) {
                    
                    $filename = rand(1000,9999).$file->getClientOriginalName();
                    $file->move(public_path().'/images/pod/', $filename);
                    
                    $url = url('public/images/pod/' . $filename);
		} else {
                    $message = 'An error occurred while uploading the file.';
		}
	} else {
		$message = 'No file uploaded.';
	}
	return '<script>window.parent.CKEDITOR.tools.callFunction('.$funcNum.', "'.$url.'", "'.$message.'")</script>';
    }
}
