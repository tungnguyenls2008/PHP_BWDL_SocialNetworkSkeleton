<div class="row">
    <div class="col-lg-8">
        <!-- Timeline statuses and replies -->
        @if (!$statuses->count())
            <p>There's nothing in your timeline, yet.</p>
        @else
            @foreach ($statuses as $status)
              {{-- status --}}
            <div class="media">
                <a class="pull-left" href="{{route('profile.index',['username'=> $status->user->username])}}">
                    <img class="mr-3 rounded-circle" style="width:60px;" alt="{{$status->user->getNameOrUsername()}}" src="{{$status->user->getAvatarURL()}}">
                </a>
                <div class="media-body">
                    <h4 class="media-heading">
                      <a href="{{route('profile.index',['username'=> $status->user->username])}}">
                        {{$status->user->getNameOrUsername()}}
                      </a>
                    </h4>
                    <p>{{$status->body}}</p>
                    <ul class="list-inline">
                        <li class="list-inline-item" data-toggle="tooltip" title="{{$status->created_at}}">
                          Created: {{$status->created_at->diffForHumans()}}
                        </li>

                        @if ($status->user_id === Auth::user()->id)
                        <li class="list-inline-item">
                          <a title="Edit Status" type="button" data-toggle="modal" data-target="#editModal-{{$status->id}}">
                            <i class="fas fa-edit text-info"></i>
                          </a>
                        </li>
                        @endif

                        {{-- A user should not be able to like his own statuses --}}
                        @if ($status->user->isFriendsWith(Auth::user()))
                        <li class="list-inline-item" class="like-btn">
                          <a href="{{route('status.like', ['statusId'=>$status->id])}}" title="Like status">
                            <i class="fas fa-thumbs-up"></i>
                          </a>
                        </li>
                        @endif

                        @if ($status->user_id === Auth::user()->id)
                        <li class="list-inline-item">
                          <a data-toggle="modal" data-target="#deleteModal-{{$status->id}}" title="Delete Status" type="button">
                            <i class="fas fa-trash-alt text-danger"></i>
                          </a>
                        </li>
                        @endif
                      
                        <li class="list-inline-item">
                          {{$status->likes->count()}} 
                          @if($status->likes->count() == 1)
                              <span>Like</span>
                          @else
                              <span>Likes</span>
                          @endif
                        </li>
                        
                        
                        <li class="list-inline-item">
                           {{$status->replies()->count()}} 
                           @if($status->replies()->count() == 1)
                            <span>Reply</span>
                           @else
                            <span>Replies</span>
                           @endif
                        </li>
                        
                        @if($status->updated_at != $status->created_at)
                          <li class="list-inline-item" data-toggle="tooltip" title="{{$status->updated_at}}">
                            Updated: {{$status->updated_at->diffForHumans()}}
                          </li>
                        @endif

                    </ul>

                {{-- reply --}}

              @foreach ($status->replies as $reply)
                <div class="media">
                    <a class="pull-left"  href="{{route('profile.index',['username'=> $reply->user->username])}}">
                        <img class="mr-3 rounded-circle" style="width:40px;" alt="{{$reply->user->getNameOrUsername()}}" src="{{$reply->user->getAvatarURL()}}">
                    </a>
                    <div class="media-body">
                        <h5 class="media-heading">
                          <a class="reply" href="{{route('profile.index',['username'=> $reply->user->username])}}">
                            {{$reply->user->getNameOrUsername()}}
                          </a>
                        </h5>
                        <p>{{$reply->body}}</p>
                        <ul class="list-inline">
                            <li class="list-inline-item" data-toggle="tooltip" title="{{$reply->created_at}}">
                              Created: {{$reply->created_at->diffForHumans()}}
                            </li>

                            @if ($reply->user_id === Auth::user()->id)
                              <li class="list-inline-item">
                                <a title="Edit Reply" type="button" data-toggle="modal" data-target="#editModal-{{$reply->id}}">
                                  <i class="fas fa-edit text-info"></i>
                                </a>
                              </li>
                            @endif

                            {{-- A user shuold not be able to like his own reply --}}
                            @if ($reply->user->isFriendsWith(Auth::user()))
                            <li class="list-inline-item" class="like-btn">
                              <a href="{{route('status.like', ['statusId'=>$reply->id])}}" title="Like Reply">
                                <i class="fas fa-thumbs-up"></i>
                              </a>
                            </li>
                            @endif

                            @if ($reply->user_id === Auth::user()->id)
                            <li class="list-inline-item">
                              <a data-toggle="modal"  data-target="#deleteModal-{{$reply->id}}" title="Delete Reply" type="button">
                                <i class="fas fa-trash-alt text-danger"></i>
                              </a>
                            </li>
                            @endif
                          
                            <li class="list-inline-item">
                              {{$reply->likes->count()}} 
                              @if($reply->likes->count() == 1)
                                  <span>Like</span>
                              @else
                                  <span>Likes</span>
                              @endif
                            </li>

                            @if($reply->updated_at != $reply->created_at)
                              <li class="list-inline-item" data-toggle="tooltip" title="{{$reply->updated_at}}">
                              Updated: {{$reply->updated_at->diffForHumans()}}
                              </li>
                            @endif
                        </ul>
                    </div>
                </div>
                   <!-- The edit reply Modal -->
                   @include('templates.partials.editmodal', ['id' => $reply->id, 'title' => 'reply', 'body' => $reply->body])

                <!-- Delete Reply Button Modal HTML -->
                @include('templates.partials.deletemodal', ['id' => $reply->id])
              @endforeach


    
                   @if ($authUserIsFriend || Auth::user()->id === $status->user_id)
                    <form role="form" action="{{route('status.reply', ['statusId' => $status->id])}}" method="post">
                        <div class="form-group">
                            <textarea name="reply-{{$status->id}}" class="form-control {{$errors->has("reply-{$status->id}") ? ' is-invalid' : ''}}" rows="2" placeholder="Reply to this status"></textarea>
                            @if ($errors->has("reply-{$status->id}"))
                              <span class="help-block text-danger">{{$errors->first("reply-{$status->id}")}}</span>
                            @endif
                        </div>
                        <input type="submit" value="Reply" class="btn btn-outline-primary">
                        <input type="hidden" name="_token" value="{{Session::token()}}">
                    </form>
                    @endif 
                </div>
            </div>
            <hr>

                  <!-- The edit status Modal -->
              @include('templates.partials.editmodal', ['id' => $status->id, 'title' => 'status', 'body' => $status->body])

                          <!-- Delete Status Button Modal HTML -->
              @include('templates.partials.deletemodal', ['id' => $status->id])
            @endforeach

            {{-- Show pagination links --}}
            {{$statuses->links()}}

        @endif
    </div>
</div>