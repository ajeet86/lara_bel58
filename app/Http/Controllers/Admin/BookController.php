<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Category;
use App\Book;
use App\Author;
use App\BookImage;
use Auth;
use Redirect;
use Illuminate\Support\Facades\Input;
use DB;

class BookController extends Controller {

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
        return view('admin.book_list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null) {
        $users = User::orderBy('name')->pluck('name', 'id');
        $categories = Category::orderBy('name')->pluck('name', 'id');
        if (isset($id)) {
            $book = Book::with(['bookAuthors', 'bookImages'])->where('id', $id)->first();
            return view('admin.add_book', compact('users', 'categories', 'book'));
        } else {
            return view('admin.add_book', compact('users', 'categories'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $rules = [
            'category' => 'required',
            'isbn' => 'required|string|max:20|unique:books,isbn_no',
            'school_name' => 'required',
            'title' => 'required|string|max:225',
            'org_price' => 'required',
            'listing_price' => 'required',
            'quantity' => 'required|numeric|min:1|not_in:0',
            'author.0' => 'required',
            'weight' => 'required',
                //'condition_type' => ['required', new EnumValue(BookConditionType::class)],
        ];

        $customMessages = [
            'org_price.required' => 'The Original Price is required',
            'author.0.required' => 'The Author field is required',
        ];

        $this->validate($request, $rules, $customMessages);
        // calculate 25% commission
        $prec_org_price = $request->org_price * (25/100);
        $prec_listing_price = $request->listing_price * (25/100);
        try {
            $book = new Book;
            $msg = 'Book added successfully';
            $book->title = $request->title;
            $book->cat_id = $request->category;
            $book->user_id = isset($request->user) ? $request->user : 0;
            $book->admin_id = isset($request->user) ? 0 : Auth::User()->id;
            $book->isbn_no = $request->isbn;
            $book->book_desc = $request->book_desc;
            $book->org_price = $request->org_price;
            $book->perc_org_price = $request->org_price + $prec_org_price;
            $book->listing_price = $request->listing_price;
            $book->perc_listing_price = $request->listing_price + $prec_listing_price;
            $book->book_condition = $request->book_condition;
            $book->quantity = $request->quantity;
            $book->weight = $request->weight;
            $book->school_name = $request->school_name;
            $book->weight = $request->weight;
            $book->save();
            $bookInsertId = $book->id;

            $count = sizeof($request->author);
            for ($i = 0; $i < $count; $i++) {
                $author = new Author;
                $author->book_id = $bookInsertId;
                $author->author = $request->author[$i];
                $author->save();
            }

            // move images from temp folder to targeted folder and save data to product image table
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'PNG', 'gif', 'bmp'];
            $originaldestinationPath = public_path('/images/books/original/');
            if ($request->hasFile('bookImage')) {
                foreach ($request->file('bookImage') as $image) {
                    $extension = $image->getClientOriginalExtension();
                    $fileName = rand(10000, 99999) . '.' . $extension;
                    if (in_array($extension, $allowedExtensions)) {
                        //$compressed_image = Image::make($image->getRealPath());
                        //$thumb = $compressed_image->resize(80, 110);
                        //$thumbdestinationPath = resource_path() . '/assets/images/uploads/product/thumb/';
                        // $compressed_image->save($thumbdestinationPath . $fileName, 25);
                        //$destinationPath = resource_path() . '/assets/images/uploads/product/';
                        $image->move($originaldestinationPath, $fileName);
                    }
                    $bookimg = new BookImage;
                    $bookimg->book_id = $bookInsertId;
                    $bookimg->image = $fileName;
                    $bookimg->save();
                }
            }
            return Redirect::to('/admin/book_list')->with('success', $msg);
        } catch (Exception $ex) {
            return Redirect::back()->with('error', 'Some error occur!! Please try again.');
        }
    }

    /**
     * Fetch all the books from database table.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchBooks() {
        $start = Input::get('iDisplayStart');      // Offset
        $length = Input::get('iDisplayLength');     // Limit
        $sSearch = Input::get('sSearch');            // Search string
        $col = Input::get('iSortCol_0');         // Column number for sorting
        $sortType = Input::get('sSortDir_0');         // Sort type
        $where = '';

        // Datatable column number to table column name mapping
        $arr = array(
            0 => 't1.id',
            1 => 't1.title',
            2 => 't2.name',
            3 => 't1.isbn_no',
            4 => 't1.org_price',
            5 => 't1.listing_price',
            7 => 't4.name',
            8 => 't1.created_at',
        );

        // Map the sorting column index to the column name
        $sortBy = $arr[$col];
        if ($sortBy == '') {
            $sortBy = "t1.id";
        }

        if ($sSearch != '') {
			$sSearch = addslashes($sSearch);
            $where = " WHERE t1.title LIKE ('%" . $sSearch . "%')"
                    . " OR t2.name LIKE ('%" . $sSearch . "%')"
                    . " OR t1.isbn_no LIKE ('%" . $sSearch . "%')"
                    . " OR t4.name LIKE ('%" . $sSearch . "%')"
                    . " OR t3.author LIKE ('%" . $sSearch . "%') ";
        }
        // Get the records after applying the datatable filters
        $books = DB::select(
                        DB::raw("SELECT t1.id, t1.title, t2.name, t1.isbn_no,"
                                . " t1.org_price, t1.listing_price, t1.publish, t1.top_picks, t4.name as seller_name,"
                                . " GROUP_CONCAT(t3.author SEPARATOR ' , ') as author,"
                                . " t1.created_at FROM books t1 "
                                . "JOIN categories t2 ON t1.cat_id = t2.id "
                                . "JOIN users t4 ON t1.user_id = t4.id "
                                . "JOIN authors t3 ON t1.id = t3.book_id " .
                                $where . " GROUP BY t1.id, t1.title, t2.name, t4.name, "
                                . "t1.isbn_no, t1.org_price, t1.listing_price, t1.publish, t1.top_picks,"
                                . "t1.created_at ORDER BY " . $sortBy . " " . $sortType . " LIMIT " . $start . ", " . $length)
        );
        // Get the total count without any condition to maintian the pagination
        $bookCount = DB::select(
                        DB::raw("SELECT t1.id, t1.title, t2.name, t1.isbn_no, "
                                . "t1.org_price, t1.listing_price, t1.top_picks, t4.name as seller_name,"
                                . " GROUP_CONCAT(t3.author, ',') as author,"
                                . " t1.created_at FROM books t1"
                                . " JOIN categories t2 ON t1.cat_id = t2.id"
                                . " JOIN users t4 ON t1.user_id = t4.id "
                                . " JOIN authors t3 ON t1.id = t3.book_id" .
                                $where . " GROUP BY t1.id, t1.title, t2.name, t4.name, "
                                . " t1.isbn_no, t1.org_price, t1.listing_price,"
                                . " t1.publish, t1.top_picks, t1.created_at")
        );

        // Assign it to the datatable pagination variable
        $iTotal = count($bookCount);

        $response = array(
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iTotal,
            'aaData' => array()
        );

        $k = 0;
        if (count($books) > 0) {
            foreach ($books as $book) {
                $response['aaData'][$k] = array(
                    0 => $k + 1,
                    1 => $book->title,
                    2 => $book->name,
                    3 => $book->isbn_no,
                    4 => $book->org_price,
                    5 => $book->listing_price,
                    6 => $book->author,
                    7 => $book->seller_name,
                    8 => date('d-m-Y', strtotime($book->created_at)),
					9 => '<label class="custom-control custom-checkbox error_color">'
                    . '<input type="checkbox" class="custom-control-input" '
                    . 'name="top_picks" class="top_picks" '
                    . 'value="1" ' . ($book->top_picks == 1 ? "checked" : "") .' '
                    . 'onclick="return top_picks(' . $book->id .');">'
                    . '<span class="custom-control-indicator"></span></label>',
                    10 => '<a href="book/' . $book->id . '" '
                    . 'class="delete hidden-xs hidden-sm" title="Edit">'
                    . '<i class="fa fa-pencil text-warning"></i></a> &nbsp;'
                    . ' <a href="book/delete/' . $book->id . '" '
                    . 'class="delete hidden-xs hidden-sm" title="Delete"'
                    . 'onclick=\'return confirm("Are you sure you want to delete this book?")\'>'
                    . '<i class="fa fa-trash text-danger"></i></a> &nbsp;'
                    . ($book->publish == 1 ? '<a href="pub_unpub_book/' . $book->id . '" title="publish" '
                    . 'onclick=\'return confirm("Are you sure you want to unpublish this book?")\'>'
                    . '<i class="fa fa-cloud-upload text-success"></i></a>' : '<a href="pub_unpub_book/' . $book->id . '" title="unpublish" '
                    . 'onclick=\'return confirm("Are you sure you want to publish this book?")\'>'
                    . '<i class="fa fa-cloud-upload text-danger"></i></a>'),
                );
                $k++;
            }
        }
        return response()->json($response);
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
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $rules = [
            'category' => 'required',
            'isbn' => 'required|string|max:20|unique:books,isbn_no,' . $id,
            'school_name' => 'required',
            'title' => 'required|string|max:225',
            'org_price' => 'required',
            'listing_price' => 'required',
            'quantity' => 'required|numeric|min:1|not_in:0',
            'weight' => 'required',
        ];

        $customMessages = [
            'org_price.required' => 'The Original Price is required',
        ];

        $this->validate($request, $rules, $customMessages);
        // calculate 25% commission
        $prec_org_price = $request->org_price * (25/100);
        $prec_listing_price = $request->listing_price * (25/100);
        try {
            $book = Book::findOrFail($id);
            $book->title = $request->title;
            $book->cat_id = $request->category;
            $book->user_id = isset($request->user) ? $request->user : 0;
            $book->admin_id = isset($request->user) ? 0 : Auth::User()->id;
            $book->isbn_no = $request->isbn;
            $book->book_desc = $request->book_desc;
            $book->org_price = $request->org_price;
            $book->perc_org_price = $request->org_price + $prec_org_price;
            $book->listing_price = $request->listing_price;
            $book->perc_listing_price = $request->listing_price + $prec_listing_price;
            $book->book_condition = $request->book_condition;
            $book->quantity = $request->quantity;
            $book->school_name = $request->school_name;
            $book->weight = $request->weight;
            $book->save();

            foreach ($request->author as $key => $author_nm) {
                if ($key == $id) {
                    foreach ($author_nm as $author_id => $value) {
                        Author::where('book_id', '=', $key)
                                ->where('id', '=', $author_id)
                                ->update([
                                    'author' => $value,
                        ]);
                    }
                } else {
                    $author = new Author;
                    $author->book_id = $id;
                    $author->author = $author_nm;
                    $author->save();
                }
            }
            // move images from temp folder to targeted folder and save data to product image table
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'PNG', 'gif', 'tif', 'bmp'];

            $originaldestinationPath = public_path('/images/books/original/');

            if ($request->hasFile('bookImage')) {
                foreach ($request->file('bookImage') as $image) {
                    $extension = $image->getClientOriginalExtension();
                    $fileName = rand(10000, 99999) . '.' . $extension;
                    if (in_array($extension, $allowedExtensions)) {
                        //$compressed_image = Image::make($image->getRealPath());
                        //$thumb = $compressed_image->resize(80, 110);
                        //$thumbdestinationPath = resource_path() . '/assets/images/uploads/product/thumb/';
                        // $compressed_image->save($thumbdestinationPath . $fileName, 25);
                        //$destinationPath = resource_path() . '/assets/images/uploads/product/';
                        $image->move($originaldestinationPath, $fileName);
                    }

                    $bookimg = new BookImage;
                    $bookimg->book_id = $id;
                    $bookimg->image = $fileName;
                    $bookimg->save();
                }
            }
            return Redirect::to('/admin/book_list')->with('success', 'Book updated successfully');
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
            $book = Book::findOrFail($id);
            $book_images = BookImage::where('book_id', $id)->select('id', 'image')->get();
            foreach ($book_images as $image) {
                $orgdestinationPath = public_path('/images/books/original/');
                if (file_exists($orgdestinationPath . $image->image)) {
                    @unlink($orgdestinationPath . $image->image);
                }
            }
            $book->bookImages()->delete();
            $book->bookAuthors()->delete();
            $book->delete();
            return Redirect::back()->with('success', 'Book Deleted Successfully');
        } catch (Exception $ex) {
            return Redirect::back()->with('error', 'Some error occur!! Please try again.');
        }
    }

    /**
     * Remove the specified author from databse table.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_author($id) {
        try {
            $author = Author::findOrFail($id);
            $author->delete();
            return response()->json('success');
        } catch (Exception $ex) {
            return response()->json('error', 'Some error occur!! Please try again.');
        }
    }

    /**
     * Remove the specified book image from databse table.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_book_image($id) {
        try {
            $book_img = BookImage::findOrFail($id);
            $orgdestinationPath = public_path('/images/books/original/');
            if (file_exists($orgdestinationPath . $book_img->image)) {
                @unlink($orgdestinationPath . $book_img->image);
            }
            $book_img->delete();
            return response()->json('success');
        } catch (Exception $ex) {
            return response()->json('error', 'Some error occur!! Please try again.');
        }
    }

    /**
     * book publish or unpublish.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function pub_unpub_book($id) {
        $getDetail = Book::select('publish')->where(['id' => $id])->first();
        if ($getDetail->publish == '1') {
            $data = array(
                'publish' => '0'
            );
            $msg = "Book unpublish successfully";
        } else {
            $data = array(
                'publish' => '1'
            );
            $msg = "Book publish successfully";
        }

        Book::where(['id' => $id])->update($data);
        return Redirect::back()->with('success', $msg);
    }
	
	/**
     * add on top picks or remove from top picks.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */ 
    public function top_picks($id) {
        $getDetail = Book::select('top_picks')->where(['id' => $id])->first();
        if ($getDetail->top_picks == '1') {
            $data = array(
                'top_picks' => '0'
            );
            $msg = "Removed from top picks successfully";
            
        } else {
            $data = array(
                'top_picks' => '1'
            );
            $msg = "Added to top picks successfully";
        }

        Book::where(['id' => $id])->update($data);
        return Redirect::back()->with('success', $msg);
    }

}
