<?php

namespace App\Http\Controllers\User;

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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /* public function index() {
      return view('user.my_book_list');
      } */

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $flag = 0; $account = 0;
        $user_id = Auth::User()->id;
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $seller_addr = User::select('street_line', 'city', 'state', 'country', 'postal_code', 'paypal_email')->findOrFail($user_id);
        if(isset($seller_addr->street_line) && isset($seller_addr->city) && isset($seller_addr->state) && isset($seller_addr->country) && isset($seller_addr->postal_code)){
            $flag = 1;
        }
        if(isset($seller_addr->paypal_email)){
            $account = 1;
        }
        return view('user.add_book', compact('categories', 'flag', 'account'));
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
        ];

        $customMessages = [
            'org_price.required' => 'The Original Price is required',
            'author.0.required' => 'The Author field is required',
        ];
        $prec_org_price = $request->org_price * (25/100);
        $prec_listing_price = $request->listing_price * (25/100);
        $this->validate($request, $rules, $customMessages);
        try {
            $book = new Book;
            $book->title = $request->title;
            $book->cat_id = $request->category;
            $book->user_id = Auth::User()->id;
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
            $bookInsertId = $book->id;

            $count = sizeof($request->author);
            for ($i = 0; $i < $count; $i++) {
                $author = new Author;
                $author->book_id = $bookInsertId;
                $author->author = $request->author[$i];
                $author->save();
            }

            // move images from temp folder to targeted folder and save data to product image table
            $allowedExtensions = ['jpg', 'png', 'PNG', 'gif', 'tif', 'bmp'];
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
            return Redirect::to('/my_book_list')->with('success',
                    'Book added successfully');
        } catch (Exception $ex) {
            return Redirect::back()->with('error',
                    'Some error occur!! Please try again.');
        }
    }
    
    /**
     * Display own book list
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function my_book_list(Request $request) {
        $id = Auth::User()->id;

        //DB::enableQueryLog();

        $books = Book::select('books.id', 'books.title', 'books.org_price', 
                'books.listing_price', 'books.perc_org_price', 'books.perc_listing_price',
                'books.quantity', 'user_id', 'books.publish', 
                DB::raw('(select image from book_images '
                        . 'where book_id = books.id order by id asc limit 1) as image'))
                ->Join('categories', 'books.cat_id', '=', 'categories.id')
                ->where('user_id', $id)
                ->orderBy('books.id', 'DESC')
                ->paginate(8);

        if ($request->ajax()) {
            //dd(DB::getQueryLog());
            return view('book_list_result', compact('books'));
        }
        return view('user.my_book_list', compact('books'));
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
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $seller_addr = User::select('street_line', 'city', 'state', 'country', 'postal_code')->findOrFail(Auth::User()->id);
        if(isset($seller_addr->street_line) && isset($seller_addr->city) && isset($seller_addr->state) && isset($seller_addr->country) && isset($seller_addr->postal_code)){
            $flag = 1;
        } else {
            $flag = 0;
        }
        if (isset($id)) {
            $book = Book::with(['bookAuthors', 'bookImages'])->where('id', $id)->first();
            return view('user.add_book', compact('categories', 'book', 'flag'));
        }
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
        $prec_org_price = $request->org_price * (25/100);
        $prec_listing_price = $request->listing_price * (25/100);
        $this->validate($request, $rules, $customMessages);
        try {
            $book = Book::findOrFail($id);
            $book->title = $request->title;
            $book->cat_id = $request->category;
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
            $allowedExtensions = ['jpg', 'png', 'PNG', 'gif', 'tif', 'bmp'];
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
            return Redirect::to('/my_book_list')->with('success', 'Book updated successfully');
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

    /* start book publish functionality */

    public function pub_unpub_book($id) {

        $getDetail = Book::where(['id' => $id])->first();

        // echo '<pre>';print_r($getDetail);die;
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

}
