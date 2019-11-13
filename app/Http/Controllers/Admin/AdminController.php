<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin;
use Hash;
use Auth;
use Redirect;
use DB;
use App\User;
use App\Subscriber;
use Illuminate\Support\Facades\Input;

class AdminController extends Controller {
    
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
        return view('admin.dashboard');
    }

    public function settings() {
        $id = Auth::User()->id;
        $getDetail = Admin::select('name', 'username', 'email', 'avatar')->where(['id' => $id])->first();
        return view('admin.setting', ['data' => $getDetail]);
    }

    public function update_settings(Request $request) {
        $this->validate($request, [
            'name' => 'required|string|max:55',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::user()->id . ',id',
            'username' => 'required|alpha_num|max:30|unique:users,username,' . Auth::user()->id . ',id',
        ]);
        $id = Auth::User()->id;

        $settings = Admin::findOrFail($id);
        $settings->name = $request->name;
        $settings->username = $request->username;
        $settings->email = $request->email;
        $allowedExtensions = ['jpg', 'png', 'gif', 'tif', 'bmp'];
        if ($request->hasFile('avatar')) {
            $destinationPath = public_path('/images/users');
            if (file_exists($destinationPath . '/' . $request->hd_avatar)) {
                @unlink($destinationPath . '/' . $request->hd_avatar);
            }
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $fileName = rand(10000, 99999) . '.' . $extension;
            if (in_array($extension, $allowedExtensions)) {

                $file->move($destinationPath, $fileName);
            }
            $settings->avatar = $fileName;
        }

        $settings->save();
        return Redirect::back()->with('message', 'Settings updated successfully');
    }

    public function change_pwd() {
        return view('admin.change_pwd');
    }

    public function update_changed_pwd(Request $request) {
        $this->validate($request, [
            'current-password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $request_data = $request->All();
        $current_password = Auth::User()->password;
        
        if (Hash::check($request_data['current-password'], $current_password)) {
            $user_id = Auth::User()->id;
            $obj_user = Admin::find($user_id);
            $obj_user->password = Hash::make($request_data['password']);
            $obj_user->save();
            return redirect()->back()->with("success", "Password changed successfully!");
        } else {
            return redirect()->back()->with("error", "Please enter correct current password");
        }
    }

    public function subscribers(){
        return view('admin.subscribers_list');
    }

    public function fetchsubscribers() {

        $start = Input::get('iDisplayStart');      // Offset
        $length = Input::get('iDisplayLength');     // Limit
        $sSearch = Input::get('sSearch');            // Search string
        $col = Input::get('iSortCol_0');         // Column number for sorting
        $sortType = Input::get('sSortDir_0');         // Sort type
        $where = '';

        // Datatable column number to table column name mapping
        $arr = array(
            0 => 't1.id',
            1 => 't1.email',
            2 => 't1.created_at',
        );

        // Map the sorting column index to the column name
        $sortBy = $arr[$col];
        if ($sortBy == '') {
            $sortBy = "t1.id";
        }

        if ($sSearch != '') {
			$sSearch = addslashes($sSearch);
            $where = " WHERE t1.email LIKE ('%" . $sSearch . "%')";
        }
        // Get the records after applying the datatable filters
        $subscribers = DB::select(
                        DB::raw("SELECT t1.id, t1.email, t1.updated_at, t1.created_at FROM subscribers t1 " .
                                $where . " ORDER BY " . $sortBy . " " . $sortType . " LIMIT " . $start . ", " . $length)
        );

        // Get the total count without any condition to maintian the pagination
        $userCount = DB::select(
                        DB::raw("SELECT t1.id, t1.email, t1.updated_at, t1.created_at FROM subscribers t1 " .
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
        if (count($subscribers) > 0) {
            foreach ($subscribers as $subscriber) {
                $response['aaData'][$k] = array(
                    0 => $k + 1,
                    1 => $subscriber->email,
                    2 => date('d-m-Y', strtotime($subscriber->created_at)),
                    3 => '<a href="subscriber/delete/' . $subscriber->id . '" c'
                    . 'lass="delete hidden-xs hidden-sm" title="Delete" '
                    . 'onclick=\'return confirm("Are you sure you want to delete this subscriber?")\'>'
                    . '<i class="fa fa-trash text-danger"></i></a>',
                );
                $k++;
            }
        }
        return response()->json($response);
    }



    public function destroy($id) {
        try {
            $subscriber = Subscriber::findOrFail($id);
            $subscriber->delete();
            return Redirect::back()->with('success', 'Subscriber Deleted Successfully');
        } catch (Exception $ex) {
            return Redirect::back()->with('error', 'Some error occur!! Please try again.');
        }
    }
	// Contact Us List
	public function contact_us_list(){
        return view('admin.contact_us_list');
    }

    public function fetchContact() {

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
            2 => 't1.email',
            3 => 't1.phone',
            4 => 't1.body_message',
            5 => 't1.created_at',
        );

        // Map the sorting column index to the column name
        $sortBy = $arr[$col];
        if ($sortBy == '') {
            $sortBy = "t1.id";
        }

        if ($sSearch != '') {
			$sSearch = addslashes($sSearch);
            $where = " WHERE t1.email LIKE ('%" . $sSearch . "%') OR t1.phone LIKE ('%" . $sSearch . "%') OR t1.name LIKE ('%" . $sSearch . "%')";
        }
        // Get the records after applying the datatable filters
        $contact_us = DB::select(
                        DB::raw("SELECT t1.id, t1.name, t1.email, t1.phone, t1.body_message, t1.created_at FROM contact_us t1 " .
                                $where . " ORDER BY " . $sortBy . " " . $sortType . " LIMIT " . $start . ", " . $length)
        );

        // Get the total count without any condition to maintian the pagination
        $userCount = DB::select(
                        DB::raw("SELECT t1.id, t1.name, t1.email, t1.phone, t1.body_message, t1.created_at FROM contact_us t1 " .
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
        if (count($contact_us) > 0) {
            foreach ($contact_us as $contact) {
                $response['aaData'][$k] = array(
                    0 => $k + 1,
                    1 => $contact->name,
                    2 => $contact->email,
                    3 => $contact->phone,
                    4 => $contact->body_message,
                    5 => date('d-m-Y', strtotime($contact->created_at)),
                    6 => '<a href="cantact_us/delete/' . $contact->id . '"'
                    . 'class="delete hidden-xs hidden-sm" title="Delete" '
                    . 'onclick=\'return confirm("Are you sure you want to delete this message?")\'>'
                    . '<i class="fa fa-trash text-danger"></i></a>',
                );
                $k++;
            }
        }
        return response()->json($response);
    }
    
    public function destroyContact($id){
        try {
            $contact_us = DB::table('contact_us')->where('id', $id)->delete();
            return Redirect::back()->with('success', 'Message Deleted Successfully');
        } catch (Exception $ex) {
            return Redirect::back()->with('error', 'Some error occur!! Please try again.');
        }
    }

    public function seller_rating(){
        $sellers = DB::table('books')
            ->join('users', 'books.user_id', '=', 'users.id')
            ->distinct()->select('books.user_id')
            ->select('users.name','users.id','users.email')
            ->orderBy('users.name')
            ->get();
        return view('admin.seller_rating_list',['sellers'=>$sellers]);
    }

    


}
