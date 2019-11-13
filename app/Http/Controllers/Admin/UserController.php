<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use DB;
use Redirect;
use Hash;
use Carbon\Carbon;
use Storage;

class UserController extends Controller {

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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id = null) {
        try {
            if (isset($id)) {
                if ($request->password != '') {
                    $this->validate($request, [
                        'name' => 'required|string|max:50',
                        'surname' => 'required|string|max:50',
                        'username' => 'required|alpha_num|max:50',
                        'email' => 'required|string|email|max:60',
                        'password' => 'required|min:8|same:confirmpassword',
                        'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg,bmp|max:2048'
                    ]);
                } else {
                    $this->validate($request, [
                        'name' => 'required|string|max:50',
                        'surname' => 'required|string|max:50',
                        'username' => 'required|alpha_num|max:50',
                        'email' => 'required|string|email|max:60',
                        'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg,bmp|max:2048'
                    ]);
                }

                $user = User::findOrFail($id);
                $msg = 'User updated successfully';
            } else {
                $this->validate($request, [
                    'name' => 'required|string|max:50',
                    'surname' => 'required|string|max:50',
                    'username' => 'required|alpha_num|max:50',
                    'email' => 'required|string|email|max:50',
                    'password' => 'required|min:8|same:confirmpassword',
                    'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg,bmp|max:2048'
                ]);
                $user = new User;
                $msg = 'User added successfully';
            }
            $user->name = $request->name;
            $user->surname = $request->surname;
            $user->username = $request->username;
            $user->email = $request->email;
            if ($request->password != '') {
                $user->password = Hash::make($request->password);
            }
            $user->phone = $request->phone;
            $user->dob = Carbon::parse($request->dob);
            $user->country = $request->country;
            $user->access_level = $request->access_level;
            if(isset($request->access_level)){
                $user->proof_approval = '0';
            }
            $allowedExtensions = ['jpeg', 'jpg', 'png', 'PNG', 'gif', 'svg', 'bmp'];
            // upload user avatar
            if ($request->hasFile('avatar')) {
                $destinationPath = public_path('/images/users');
                if (isset($id) && file_exists($destinationPath . '/' . $user->avatar)) {
                    @unlink($destinationPath . '/' . $user->avatar);
                }
                $file = $request->file('avatar');
                $extension = $file->getClientOriginalExtension();
                $fileName = rand(10000, 99999) . '.' . $extension;
                if (in_array($extension, $allowedExtensions)) {

                    $file->move($destinationPath, $fileName);
                }
                $user->avatar = $fileName;
            }

            if ($request->hasFile('level_proof')) {

                if (isset($id) && Storage::exists('uploads/files/level_proof/' . $user->level_proof)) {
                    Storage::delete('uploads/files/level_proof/' . $user->level_proof);
                }
                $file = $request->file('level_proof');
                $extension = $file->getClientOriginalExtension();
                $fileName = rand(10000, 99999) . '.' . $extension;
                if (in_array($extension, $allowedExtensions)) {
                    $path = $file->storeAs('uploads/files/level_proof/', $fileName);
                }
                $user->level_proof = $fileName;
            }
            $user->email_verified_at = \Carbon\Carbon::now();
            $user->save();
            return Redirect::to('/admin/user_list')->with('success', $msg);
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
            $user = User::findOrFail($id);
            $destinationPath = public_path('/images/users');
            if (file_exists($destinationPath . '/' . $user->avatar)) {
                @unlink($destinationPath . '/' . $user->avatar);
            }
            $user->delete();
            return Redirect::back()->with('success', 'User Deleted Successfully');
        } catch (Exception $ex) {
            return Redirect::back()->with('error', 'Some error occur!! Please try again.');
        }
    }

    /**
     * List all the registered user on admin Panel
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function user_list() {
		$id = Auth::User()->id;
		// echo '<pre>';
		// print_r($id);
		// echo '</pre>';
        return view('admin.user_list');
    }

	
    /**
     * Fetch all the users from database table.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchUsers() {

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
            2 => 't1.surname',
            3 => 't1.username',
            4 => 't1.email',
            5 => 't1.phone',
            6 => 't1.created_at',
        );

		
		
        // Map the sorting column index to the column name
        $sortBy = $arr[$col];
        if ($sortBy == '') {
            $sortBy = "t1.id";
        }

        if ($sSearch != '') {
			$sSearch = addslashes($sSearch);
            $where = " WHERE t1.name LIKE ('%" . $sSearch . "%') OR t1.surname LIKE ('%" . $sSearch . "%') OR t1.username LIKE ('%" . $sSearch . "%') OR t1.email LIKE ('%" . $sSearch . "%') OR t1.phone LIKE ('%" . $sSearch . "%')";
        }
        // Get the records after applying the datatable filters
        $users = DB::select(
                        DB::raw("SELECT t1.id, t1.name, t1.surname, t1.username, t1.email, t1.phone, t1.country, t1.created_at FROM users t1 " .
                                $where . " ORDER BY " . $sortBy . " " . $sortType . " LIMIT " . $start . ", " . $length)
        );

        // Get the total count without any condition to maintian the pagination
        $userCount = DB::select(
                        DB::raw("SELECT t1.id, t1.name, t1.surname, t1.username, t1.email, t1.phone, t1.country, t1.created_at FROM users t1 " .
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
        if (count($users) > 0) {
            foreach ($users as $user) {
                $response['aaData'][$k] = array(
                    0 => $k + 1,
                    1 => $user->name,
                    2 => $user->surname,
                    3 => $user->username,
                    4 => $user->email,
                    5 => $user->phone,
                    6 => date('d-m-Y', strtotime($user->created_at)),
                    7 => '<a href="user/' . $user->id . '" '
                    . 'class="delete hidden-xs hidden-sm" title="Edit">'
                    . '<i class="fa fa-pencil text-warning"></i></a> &nbsp;'
                    . ' <a href="user/delete/' . $user->id . '"'
                    . ' class="delete hidden-xs hidden-sm" title="Delete"'
                    . 'onclick=\'return confirm("Are you sure you want to delete this user?")\'>'
                    . '<i class="fa fa-trash text-danger"></i></a>',
                );
                $k++;
            }
        }
		
		// echo '<pre>';
		// print_r($response);
		// echo '</pre>';
		
        return response()->json($response);
    }
	
	public function level_approvals(){
        return view('admin.level_approval_list');
    }
    
    /**
     * Fetch all the users from database table.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchUnapprovedLevelUser() {

        $start = Input::get('iDisplayStart');      // Offset
        $length = Input::get('iDisplayLength');     // Limit
        $sSearch = Input::get('sSearch');            // Search string
        $col = Input::get('iSortCol_0');         // Column number for sorting
        $sortType = Input::get('sSortDir_0');         // Sort type
        $where = '';

        // Datatable column number to table column name mapping
        $arr = array(
            0 => 't1.id',
            1 => 't1.username',
            3 => 't1.updated_at',
        );


        // Map the sorting column index to the column name
        $sortBy = $arr[$col];
        if ($sortBy == '') {
            $sortBy = "t1.id";
        }

        if ($sSearch != '') {
            $sSearch = addslashes($sSearch);
            $where = " AND t1.username LIKE ('%" . $sSearch . "%')";
        }
        // Get the records after applying the datatable filters
        $users = DB::select(
            DB::raw("SELECT t1.id, t1.name, t1.surname, t1.username, t1.level_proof, t1.updated_at FROM users t1 WHERE t1.proof_approval='1' " .
                                $where . " ORDER BY " . $sortBy . " " . $sortType . " LIMIT " . $start . ", " . $length)
        );

        // Get the total count without any condition to maintian the pagination
        $userCount = DB::select(
            DB::raw("SELECT t1.id, t1.name, t1.surname, t1.username, t1.email, t1.level_proof, t1.updated_at FROM users t1 WHERE t1.proof_approval='1'" .
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
        if (count($users) > 0) {
            foreach ($users as $user) {
                $response['aaData'][$k] = array(
                    0 => $k + 1,
                    1 => $user->username,
                    2 => '<a href="' . asset('storage/app/uploads/files/level_proof/' . $user->level_proof) . '" target="_blank">Download Level Proof</a>',
                    3 => date('d-m-Y', strtotime($user->updated_at)),
                    4 => '<a href="user/' . $user->id . '" '
                    . 'class="delete hidden-xs hidden-sm" title="Edit">'
                    . '<i class="fa fa-pencil text-warning"></i></a> &nbsp;',
                );
                $k++;
            }
        }

        // echo '<pre>';
        // print_r($response);
        // echo '</pre>';

        return response()->json($response);
    }
	
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_add_user($id = null) {
        $countries = DB::table('countries')
                        ->orderBy('country_name', 'ASC')->get();
        if (isset($id)) {
            $user = User::select('id', 'name', 'surname', 'username', 'email', 
			'phone', 'gender', 'avatar', 'country', 'dob', 'level_proof',
			'access_level')->findOrFail($id);
            return view('admin.add_user', compact('user', 'countries', 'states'));
        } else {
            return view('admin.add_user', compact('countries', 'states'));
        }
    }
	
}
