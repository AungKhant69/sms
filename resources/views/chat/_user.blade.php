@foreach ($data['getChatUser'] as $user)

<li class="clearfix getChatBox @if (!empty($data['receiver_id'])) @if ($data['receiver_id'] == $user['user_id']) active
  @endif @endif" id="{{ $user['user_id'] }}">
    <a href="{{ route('chat.index', ['receiver_id' => base64_encode($user['user_id'])]) }}">
        {{-- @dd($user['profile_pic']) --}}
        @if (!empty($user['profile_pic']))
            <img src="{{ $user['profile_pic'] }}" alt="avatar">
        @else
        <img src="{{ asset('dist/img/default-avatar-profile-icon-grey-photo-placeholder-vector-17317730.jpg') }}" alt="Default Image" style="height: 50px; width: 50px; border-radius: 50px">

        @endif
    <div class="about">
        <div class="name">{{ $user['name'] }} @if (!empty($user['messageCount']))
            <span id="ClearMessage{{ $user['user_id'] }}" style="background: green; color: #fff; border-radius: 5px; padding: 1px 7px;">
                {{ $user['messageCount'] }}
            </span>
            @endif
        </div>
        <div class="status">
            @if (!empty($user['is_online']))
            <i class="fa fa-circle online"></i>
            @else
            <i class="fa fa-circle offline"></i>

            @endif
              {{ Carbon\Carbon::parse($user['created_at'])->diffForHumans() }} </div>
    </div>
</a>
</li>
@endforeach
{{-- <li class="clearfix active">
    <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar">
    <div class="about">
        <div class="name">Aiden Chavez</div>
        <div class="status"> <i class="fa fa-circle online"></i> online </div>
    </div>
</li> --}}

