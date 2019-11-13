@foreach($blogs as $blog)
    <div class="col-lg-4 col-md-6">
       <!-- Single Blog -->
        <div class="single-blog">
            <div class="blog-img">
                <a href="{{url('blog/' . $blog->slug)}}">
                    <img src="{{(isset($blog->feature_image) 
                                ? url('public/images/blog/' . $blog->feature_image) 
                                : url('public/images/blog/default.png'))}}" alt="">
                </a>
            </div>
            <div class="blog-content">
               <div class="blog-title">
                  <h4><a href="{{url('blog/' . $blog->slug)}}">{{ $blog->title }}</a></h4>
                  <span>by {{ $blog->created_by }}</span>
                  <div class="meta">
                     <ul>
                        <li>{{ date('F d, Y', strtotime($blog->created_at)) }}</li>
                     </ul>
                  </div>
               </div>
               <p>{!! str_limit(strip_tags($blog->content), 90) !!}</p>
                <ul class="co-words">
                    @foreach($blog->blogtags as $tag)
                        <li value="{{ $tag->tag_id }}">{{ $tag->name }}</li>
                    @endforeach
                </ul>
                <p class="read-more"> <a href="{{url('blog/' . $blog->slug)}}" class="box_btn">read more</a>
                    @if(isset($blog->cmt_count))
                        <span><i class="fa fa-comment" aria-hidden="true"></i>{{ $blog->cmt_count }}</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
@endforeach
<div class="col-lg-12">
{!! $blogs->render() !!}
</div>
