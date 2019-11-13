<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Book;
use App\Cms;
use App\Course;
use App\BookImage;
use App\Author;
use App\Testimonial;
use DB;
use Cookie;
use Mail;
use Session;
use App\Subscriber;
use Carbon\Carbon;
use App\User;
use App\Blog;
use App\Comment;
use App\PodCast;
use App\Tag;
use Auth;

class WelcomeController extends Controller {

    /**
     * Display the landing page
     *
     * @return \Illuminate\Http\Response
     */
    public function landing_index() {
        return view('welcome');
    }
    
    /**
     * Display the study program
     *
     * @return \Illuminate\Http\Response
     */
    public function study_program() {
        return view('study_program');
    }
    
	  public function videoPlaylist() {
		  
		  $courses = DB::table('courses')->select('*')->get();
		  // echo '<pre>';
		  // print_r($courses);die;
		  
        return view('videoPlaylist', compact('courses'));
    }
	
	
	public function catlist() {
		  
		  $categories = DB::table('categories')->select('*')->get();
		  
		  $courses = Course::select('*')->groupBy('id')->get();
		  // echo '<pre>';
		  // print_r($course);die;
		  
        return view('category',compact('courses', 'categories'));
    }
	
	public function ajaxcategorylist(Request $request) {
		  $id = $_GET['id'];
		  
		
		
		 $course = Course::where('cat_id', $id)->get();
		 
		 // $a = '<div class="slick-list draggable" tabindex="0"><div class="slick-track" style="width: 4332px; transform: translate3d(-1140px, 0px, 0px); opacity: 1;"><div class="item filter-busi slick-slide slick-cloned" data-slick-index="-5" style="width: 218px;"> <img class="product-selector" src="http://3.132.139.12/public/images/blog/download2.jpg"> <div class="product-detail"> <div class="course-title-">data1</div> <!--<span class="instructor-titles">&lt;p&gt;fdhfgh&lt;/p&gt;</span>--> <span class="star-rating" style="width: 100%;"></span> <div class="price-c"><span class="org-price"> 8,000 </span> 500 </div> </div> </div><div class="item filter-busi slick-slide slick-cloned" data-slick-index="-4" style="width: 218px;"> <img class="product-selector" src="http://3.132.139.12/public/images/blog/download.jpg"> <div class="product-detail"> <div class="course-title-">data2</div> <!--<span class="instructor-titles">&lt;p&gt;fdhfgh&lt;/p&gt;</span>--> <span class="star-rating" style="width: 100%;"></span> <div class="price-c"><span class="org-price"> 8,000 </span> 500 </div> </div> </div><div class="item filter-busi slick-slide slick-cloned" data-slick-index="-3" style="width: 218px;"> <img class="product-selector" src="http://3.132.139.12/public/images/blog/download2.jpg"> <div class="product-detail"> <div class="course-title-">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</div> <!--<span class="instructor-titles">&lt;p&gt;fdhfgh&lt;/p&gt;</span>--> <span class="star-rating" style="width: 100%;"></span> <div class="price-c"><span class="org-price"> 8,000 </span> 500 </div> </div> </div><div class="item filter-busi slick-slide slick-cloned" data-slick-index="-2" style="width: 218px;"> <img class="product-selector" src="http://3.132.139.12/public/images/blog/download1.jpg"> <div class="product-detail"> <div class="course-title-">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</div> <!--<span class="instructor-titles">&lt;p&gt;fdhfgh&lt;/p&gt;</span>--> <span class="star-rating" style="width: 100%;"></span> <div class="price-c"><span class="org-price"> 8,000 </span> 500 </div> </div> </div><div class="item filter-busi slick-slide slick-cloned" data-slick-index="-1" style="width: 218px;"> <img class="product-selector" src="http://3.132.139.12/public/images/blog/download.jpg"> <div class="product-detail"> <div class="course-title-">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</div> <!--<span class="instructor-titles">&lt;p&gt;fdhfgh&lt;/p&gt;</span>--> <span class="star-rating" style="width: 100%;"></span> <div class="price-c"><span class="org-price"> 8,000 </span> 500 </div> </div> </div>';
		// $k = 0;
        // foreach ($course as  $value)
        // {
            // if($k<=4){
				// $addclass ="slick-active"; 
			// }else if($k>=5){
				// $addclass ="slick-cloned"; 
			// }
                    // $a .=  '<div class="item filter-busi slick-slide '.$addclass.'" data-slick-index="'.$k.'" style="width: 218px;">
                    // <img class="product-selector" src="http://3.132.139.12/public/images/blog/'.$value['thumbnail'].'	">
                    // <div class="product-detail">
                    // <div class="course-title-">'.$value['title'].'</div>
                    // <!--<span class="instructor-titles">&lt;p&gt;Test Course Desc&lt;/p&gt;</span>-->
                    // <span class="star-rating" style="width: 100%;"></span>
                    // <div class="price-c"><span class="org-price"> 8,000 </span> 500 </div>
                	// </div>
                // </div>';
               // $k++;
        // }
        
		 // $a .= '</div></div>';
    
		// echo $a;==========================
		
		
		$k = 0;
		foreach ($course as  $value)
        {
			
			if($k<=4){
				$addclass ="slick-active"; 
			}else if($k>=5){
				$addclass ="slick-cloned"; 
			}
		 echo '<div class="owl-item "><div class="item filter-busi slick-slide '.$addclass.'"" data-slick-index="'.$k.'" style="width: 218px;"><div class="most_rel_pro"><img class="product-selector" src="http://3.132.139.12/public/images/blog/'.$value['thumbnail'].'	">
                    <div class="product-detail">
                    <div class="course-title-">'.$value['title'].'</div>
                    <span class="star-rating" style="width: 100%;"></span>
                    <div class="price-c"> 500 </div>
                    </div>
                </div>
            </div>
			</div>';
			$k++;
		}
		
    }
	
	
    /**
     * Display the blog page
     *
     * @return \Illuminate\Http\Response
     */
    public function blog() {
        return view('blog');
    }

    /**
     * Display all books with pagination
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function list_all() {
        $cat_id = Cookie::get('category_id');
        $categories = Category::select('id', 'name', 'cat_image')->get();
        $authors = Author::select('author')->groupBy('author')->get();
        $schools = Book::select('school_name')->groupBy('school_name')->get();
        Cookie::queue(Cookie::forget('category_id'));
        //DB::enableQueryLog();

        $books = Book::select('books.id', 'books.title', 'books.perc_org_price', 'books.perc_listing_price', 'books.quantity', 'books.user_id', DB::raw('(select image from book_images where book_id = books.id order by id asc limit 1) as image'))
                ->Join('categories', 'books.cat_id', '=', 'categories.id')
                ->where('publish', 1)
                ->where(function($query) use ($cat_id) {
                    if ($cat_id) {
                        $query->where('books.cat_id', $cat_id);
                    }
                })
                ->orderBy('books.id', 'DESC')
                ->paginate(8);

        //dd(DB::getQueryLog());
        return view('all_book_list', compact('books', 'categories', 'authors', 'schools', 'cat_id'));
    }

    /**
     * Fetch filtered books by ajax call 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fetch_filter_books(Request $request) {
        //echo "<pre>";
        //print_r($request->all());
        //die();
        $filter_by_title = $request->book_title;
        $filter_category = $request->category;
        $filter_price = $request->priceRange;
        $filter_author = $request->author;
        $filter_school = $request->school;
        $filter_availability = ($request->availability != '') ? $request->availability : '';
        //DB::enableQueryLog();
        $categories = Category::select('id', 'name', 'cat_image')->get();
        $authors = Author::select('author')->groupBy('author')->get();
		$schools = Book::select('school_name')->groupBy('school_name')->get();
        $books = Book::select('books.id', 'books.title', 'books.perc_org_price', 'books.perc_listing_price', 'books.quantity', 'books.user_id', DB::raw('(select image from book_images where book_id = books.id order by id asc limit 1) as image'))
                ->Join('categories', 'books.cat_id', '=', 'categories.id')
                ->Join('authors', 'books.id', '=', 'authors.book_id')
                ->where('publish', 1)
                ->where(function($query) use ($filter_by_title) {
                    if ($filter_by_title)
                        $query->where('books.title', 'like', '%' . $filter_by_title . '%');
                })
                ->where(function($query) use ($filter_school) {
                    if ($filter_school)
                        $query->where('books.school_name', $filter_school);
                })
                ->where(function($query) use ($filter_category) {
                    if ($filter_category)
                        $query->whereIn('books.cat_id', $filter_category);
                })->where(function($query) use ($filter_availability) {
                    if ($filter_availability != '' && $filter_availability == 1) {
                        $query->where('books.quantity', '>=' ,$filter_availability);
                    }
                    if ($filter_availability != '' && $filter_availability == 0) {
                        $query->where('books.quantity', '=' ,$filter_availability);
                    }
                })->where(function($query) use ($filter_author) {
                    if ($filter_author)
                        $query->whereIn('authors.author', $filter_author);
                })
                ->where(function($query) use ($filter_price) {
                    if ($filter_price) {
                        $i = 0;
                        foreach ($filter_price as $price_range) {
                            $price = explode('-', $price_range);
                            if (isset($price[0]) && isset($price[1])) {
                                ($i == 0) ? $query->whereBetween('books.perc_listing_price', [$price[0], $price[1]]) : $query->orWhereBetween('books.perc_listing_price', [$price[0], $price[1]]);
                            }
                            if (($price[0] != '') && ($price[1] == '')) {
                                $query->orWhere('books.perc_listing_price', '>=', $price[0]);
                            }
                            $i++;
                        }
                    }
                })
                ->groupBy('books.id')
                ->groupBy('books.title')
                ->groupBy('books.perc_org_price')
                ->groupBy('books.perc_listing_price')
                ->groupBy('books.quantity')
                ->groupBy('books.user_id')
                ->orderBy('books.id', 'DESC')
                ->paginate(8);

        //dd(DB::getQueryLog());
        if ($request->ajax()) {
            return view('book_list_result', compact('books'));
        }
        return view('all_book_list', compact('books', 'categories', 'authors', 'schools', 'filter_by_title'));
    }

    /**
     * display book details by id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function book_details($id) {
        $book = Book::with(['bookAuthors', 'bookImages'])->where('id', $id)->first();
        foreach ($book->bookAuthors as $author) {
            $author_arr[] = $author->author;
        }
        $seller = User::where('id',$book->user_id)->first();
        // $seller_rating = DB::table('seller_rating')
        //              ->select(DB::raw('AVG(rating) as rating'))
        //              ->where('seller_id', '<>', $book->user_id)
        //              ->get();
        $rating = DB::table('seller_rating')
                ->where('seller_id', $book->user_id)
                ->avg('rating');
        $related_author_books = Book::select('books.id', 'books.title',
                'books.perc_org_price', 'books.perc_listing_price',
                'books.user_id', 'books.school_name', 'books.quantity',
                DB::raw('(select image from book_images where book_id = books.id'
                        . ' order by id asc limit 1) as image'))
                ->Join('authors', 'books.id', '=', 'authors.book_id')
                ->whereIn('authors.author', $author_arr)
                ->where('books.id', '<>', $id)
                ->where('books.publish', 1)
                ->get();
        return view('book_details', compact('book', 'related_author_books','seller','rating'));
    }

    // Add to cart functionality
    public function addToCart($id, $quantity = null) {
        $product = Book::findOrFail($id);
        //echo "<pre>";  print_r($product); die;
        if(isset($quantity)){
            $quantity = $quantity;
        } else {
            $quantity = 1;
        }
        if (!$product) {

            abort(404);
        }

        $cart = session()->get('cart');

        $price_book = '';

        if ($product->perc_listing_price == '') {
            $price_book = $product->perc_org_price;
        } else {
            $price_book = $product->perc_listing_price;
        }

        $book_images = DB::table('book_images')->where('book_id', $id)->first();
//echo "<pre>"; print_r($book_images); die;
        // if cart is empty then this the first product
        if (!$cart) {
            
            $cart = [
                $id => [
                    "name" => $product->title,
                    "quantity" => $quantity,
                    "price" => $price_book,
                    "photo" => isset($book_images->image) ? $book_images->image : '',
                    "weight" => $product->weight,
                    "seller_id" => $product->user_id,
                ]
            ];


            session()->put('cart', $cart);

            return redirect()->back()->with('success', 'Book added to cart successfully!');
        }

        // if cart not empty then check if this product exist then increment quantity
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $cart[$id]['quantity'] + $quantity;

            session()->put('cart', $cart);

            return redirect()->back()->with('success', 'Book added to cart successfully!');
        }

        // if item not exist in cart then add to cart with quantity = 1
        $cart[$id] = [
            "name" => $product->title,
            "quantity" => $quantity,
            "price" => $price_book,
            "photo" => isset($book_images->image) ? $book_images->image : '',
            "weight" => $product->weight,
            "seller_id" => $product->user_id,
        ];

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Book added to cart successfully!');
    }

    public function cart() {
        return view('cart');
    }

    public function update(Request $request) {
        
        if ($request->id and $request->quantity) {
            $cart = session()->get('cart');
            $book_info = Book::select('quantity', 'perc_listing_price')->where('id', $request->id)->first();
            $stock_quantity = $book_info->quantity;
            $price_of_item = $book_info->perc_listing_price;
            $selected_quantity = $request->quantity;
            
            $total_price = $price_of_item * $selected_quantity;
            if ($stock_quantity >= $selected_quantity) {
                $cart[$request->id]["quantity"] = $request->quantity;
                session()->put('cart', $cart);

                $data = ['is_updated' => 1, 'total_price' => $total_price, 'message' => 'Cart Updated Successfully'];
            } else {
                $data = ['is_updated' => 0, 'message' => 'Sorry!! Cart can\'t be Updated Successfully. As stock is not available as entered.'];
            }
        }
        return $data;
    }

    public function remove(Request $request) {
        if ($request->id) {

            $cart = session()->get('cart');

            if (isset($cart[$request->id])) {

                unset($cart[$request->id]);

                session()->put('cart', $cart);
            }

            session()->flash('success', 'Book removed successfully');
        }
    }
    
    public function getRemainingQuantity(Request $request){
        if($request->flag == 'buy_now' && Auth::guest()){
            return 'login';
        }
        $stock_quantity = Book::select('quantity')->where('id', $request->book_id)->first();
        $stock_quantity = $stock_quantity->quantity;
        // sum stock quantity with quantity stored in session by id
        if(!empty(session('cart')[$request->book_id])){
            $total_quantity = $stock_quantity - session('cart')[$request->book_id]['quantity'];
        } else {
            $total_quantity = $stock_quantity;
        }
        
        $selected_quantity = $request->quantity;
        if ($total_quantity >= $selected_quantity) {
            $this->addToCart($request->book_id, $selected_quantity);
            if($request->flag == 'add_cart'){
                return 1;
            } else {
                return 'shipping';
            }
        } else {
            return 0;
        }
    }

    public function sell() {
        return view('sell');
    }

    public function apparel() {
	$apparel=Cms::select('name','description')->where('id', 3)->first();
	//echo "<pre>"; print_r($apparel); die;
        return view('apparel',compact('apparel'));
    }

    public function who() {
	$who=Cms::select('name','description')->where('id', 4)->first();
	//echo "<pre>"; print_r($apparel); die;
        return view('who',compact('who'));
       
    }

    public function faq() {
        $faq = Cms::select('name','description')->where('id', 5)->first(); 
	//echo "<pre>"; print_r($apparel); die;
        return view('faq',compact('faq'));
       // return view('faq');
    }

    public function subscribe(Request $request){
        //$input = request()->all();

        $exist = Subscriber::select('email')->where('email',$request->email)->first();
        //dd($exist);
        if ($exist) {
            return response()->json(['exist'=>'Email already subscribed']);
        }
        $subscriber = new Subscriber;
        $subscriber->email = $request->email;
        $subscriber->save();


        return response()->json(['success'=>'Thank you for your subscription.']);
    }
	
	// contact us
    Public function ContactUsHtml(){		
        return view('contact_us');
    }
	
	public function contactusForm(Request $request){
        $this->validate($request, [
            'name' => 'required|string|max:255',            
            'email' => 'required|string|email|max:255',
            'phone_number' => 'required|numeric|digits:10',
            'message' => 'required'
        ]);
	$contactValue = [
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone_number'], 
            'body_message'=> $request['message'],
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now()
        ];
        Mail::send('contact_us_mail', $contactValue, function($message) {
            $message->to('arunpratap@virtualemployee.com');
            $message->subject('ContactUs Mail');
        });
        
        $lastcontactId = DB::table('contact_us')->insertGetId($contactValue);
        
        Session::flash('message', 'Form submitted successfully.');
            return redirect()->back();
    }
    
    public function blogs(Request $request){
        $blogs = Blog::with(['blogtags'=> function($query) {
                        return $query->join('tags', 'blog_tags.tag_id','=', 'tags.id')
                                ->select('blog_tags.id', 'blog_tags.blog_id', 'blog_tags.tag_id', 'tags.name');
                    }])->select('blogs.*',
                DB::raw('(select count(*) from comments where comments.blog_or_pod_id = blogs.id and comments.type="blog" and comments.publish=1 group by comments.blog_or_pod_id) as cmt_count'))
                ->paginate(12);
        
        $tags = Tag::orderBy('name')->pluck('name', 'id');
        return view('blog_list', compact('blogs', 'tags'));
    }
    
    /**
     * Fetch filtered books by ajax call
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fetch_filtered_blog(Request $request) {
        $filter_by_tag = $request->tag;
        //DB::enableQueryLog();
        $blogs = Blog::with(['blogtags'=> function($query) {
                        return $query->join('tags', 'blog_tags.tag_id','=', 'tags.id')
                                ->select('blog_tags.id', 'blog_tags.blog_id', 'blog_tags.tag_id', 'tags.name');
                    }])
                    ->whereHas('blogtags', function ($query) use ($filter_by_tag){
                        return $query->join('tags', 'blog_tags.tag_id','=', 'tags.id')
                                ->where(function($query) use ($filter_by_tag) {
                                if ($filter_by_tag)
                                    $query->where('blog_tags.tag_id', '=' , $filter_by_tag);
                                });
                    })
                    ->select('blogs.*',
                DB::raw('(select count(*) from comments where comments.blog_or_pod_id = blogs.id and comments.type="blog" and comments.publish=1 group by comments.blog_or_pod_id) as cmt_count'))
                ->paginate(12);
        //dd(DB::getQueryLog());
        if ($request->ajax()) {
            return view('blogs', compact('blogs'));
        }
    }
    
    public function blog_details($slug){
        $blog = Blog::with(['comments'=> function($query) {
                        return $query->join('users', 'comments.user_id','=','users.id')
                                ->select('comments.id', 'comments.comment',
                                        'comments.blog_or_pod_id','comments.user_id',
                                        'comments.created_at',
                                        'users.name','users.avatar')
                                ->where('type', 'blog')
                                ->where('publish', 1)
                                ->orderBy('comments.id', 'DESC');
                    }])
                ->where('slug', $slug)->first();
        
        return view('blog_details', compact('blog'));
    }
    
    public function podcasts(Request $request){
        $pods = PodCast::with(['podtags'=> function($query) {
                        return $query->join('tags', 'pod_tags.tag_id','=', 'tags.id')
                                ->select('pod_tags.id', 'pod_tags.pod_id', 'pod_tags.tag_id', 'tags.name');
                    }])->select('pod_casts.*',
                DB::raw('(select count(*) from comments where comments.blog_or_pod_id = pod_casts.id and comments.type="pod" and comments.publish=1 group by comments.blog_or_pod_id) as cmt_count'))
                ->paginate(12);
        $tags = Tag::orderBy('name')->pluck('name', 'id');
        return view('podcast_list', compact('pods', 'tags'));
    }
    
    /**
     * Fetch filtered books by ajax call 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fetch_filtered_pod(Request $request) {
        $filter_by_tag = $request->tag;
        //DB::enableQueryLog();
        $pods = PodCast::with(['podtags'=> function($query) {
                        return $query->join('tags', 'pod_tags.tag_id','=', 'tags.id')
                                ->select('pod_tags.id', 'pod_tags.pod_id', 'pod_tags.tag_id', 'tags.name');
                    }])
                    ->whereHas('podtags', function ($query) use ($filter_by_tag){
                        return $query->join('tags', 'pod_tags.tag_id','=', 'tags.id')
                                ->where(function($query) use ($filter_by_tag) {
                                if ($filter_by_tag)
                                    $query->where('pod_tags.tag_id', '=' , $filter_by_tag);
                                });
                    })
                    ->select('pod_casts.*',
                DB::raw('(select count(*) from comments where comments.blog_or_pod_id = pod_casts.id and comments.type="pod" and comments.publish=1 group by comments.blog_or_pod_id) as cmt_count'))
                ->paginate(12);
        //dd(DB::getQueryLog());
        if ($request->ajax()) {
            return view('podcasts', compact('pods'));
        }
    }
    
    public function pod_details($slug){
        $pod = PodCast::with(['comments'=> function($query) {
                        return $query->join('users', 'comments.user_id','=','users.id')
                                ->select('comments.id', 'comments.comment',
                                        'comments.blog_or_pod_id','comments.user_id',
                                        'comments.created_at',
                                        'users.name','users.avatar')
                                ->where('type', 'pod')
                                ->where('publish', 1)
                                ->orderBy('comments.id', 'DESC');
                    }])
                ->where('slug', $slug)->first();
        return view('pod_details', compact('pod'));
    }
}