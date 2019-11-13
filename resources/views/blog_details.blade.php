@extends('layouts.app')
@section('title', $blog->meta_title)
@section('description', $blog->meta_desc)
@section('css')
@stop
@section('content')
<section class="product-wrapper dashboard-body">
    <div class="container">
        <div class="row product">
            <div class="col-lg-12 produst-right-side">
                <div class="dash-container">
                    <h3>{{ $blog->title }}</h3>
                    <span>By {{ $blog->created_by }}</span>
                    <span>{{ date('F d, Y', strtotime($blog->created_at)) }}</span>
                    <hr class="divider-hr">
                    <section class="gallerry" id="tag_container">
                        {!! $blog->content !!}
                    </section>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="com-sec">
            @php
                $count = count($blog->comments)
            @endphp
            <div class="show_comments">
                <h5>{{ $count > 1 ? $count . ' Comments' : ($count > 0 ? $count. ' Comment' : 'Comment')}} </h5>
                @if($blog->comments->isNotEmpty())
                    @foreach($blog->comments as $comment)
                        <div class="{{ $loop->first ? 'first' : '' }}">
                            <div class="commnet-p">{{ $comment->comment }}</div>
                            <div class="comment-h">
                                <h4>{{ $comment->name }}&nbsp;&nbsp;{{ $comment->created_at->diffForHumans() }}</h4>
                            </div>

                        </div>
                    @endforeach
                @else
                    <div class="first no-cmt">There aren't any comments for this post yet.</div>
                @endif
                @guest
                    <p class="sign-in">You must <a href="{{ url('/login') }}">sign in</a> to leave a comment</p>
                @endauth
            </div>
        </div>
        @auth
            <div class="comment-box">
                <div class="leave_comment">
                    <h1>Leave a comment</h1>
                    <p style="display:none;" id="message" 
                       class="alert" >
                    </p>
                    <form class="form-horizontal login_validator"
                          method="post">
                        <div class="input-group">
                            <textarea name="comment" id="comment" 
                                      cols="100" rows="6"></textarea>
                        </div>
                        @if ($errors->has('comment'))
                        <span class="help-block">
                            <strong>{{ $errors->first('comment') }}</strong>
                        </span>
                        @endif
                        <button class="btn btn-primary" type="submit" 
                                id="button_comment">post comment</button>
                    </form>
                </div>
            </div>
        @endauth
    </div>

</section>
@endsection
@section('pagescript')
<script>
    $(document).ready(function () {
        $("#button_comment").click(function (e) {
            e.preventDefault();
            var blog_id = "{{$blog->id}}";
            var comment = $('#comment').val();
            $.ajax({
                url: '{{ url("/blog/comment/" . $blog->id) }}',
                type: 'POST',
                data: {_token: '{{ csrf_token() }}',
                    comment: comment
                },
                success: function (response) {
                    $("#message").css("display", "block");
                    if (response.success == 1) {
                        $("#message").removeClass("alert-danger").addClass("alert-success");
                        $('#message').html(response.message);
                        //$("<div class='first'><div class='col-lg-12'>" + response.user_name + "&nbsp;&nbsp;" + response.time + "</div><div class='col-lg-12'>" + response.comment + "</div></div>").insertBefore(".first");
                        //$('.first').eq(1).removeClass('first');
                        //$('.no-cmt').eq(0).remove();
                    } else if (response.error == 1) {
                        $("#message").removeClass("alert-success").addClass("alert-danger");
                        $('#message').html(response.message);
                    } else {
                        location.href = '{{ url("/login") }}';
                    }
                    $('#comment').val("");
                },
                error: function (result) {
                    $("#message").css("display", "block");
                    $("#message").removeClass("alert-success").addClass("alert-danger");
                    $('#message').html(result.responseJSON.errors.comment[0]);
                }
            });
        });
    });
</script>
@stop
