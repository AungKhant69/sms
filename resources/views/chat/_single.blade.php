@if (isset($data['getChatData']))
@foreach ($data['getChatData'] as $value)

    @if ($value->sender_id == Auth::user()->id)

    <li class="clearfix">
        <div class="message-data text-right">
            <span class="message-data-time">{{ Carbon\Carbon::parse($value->created_at)->diffForHumans() }}</span>
            <span href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                @if (!empty(Auth::user()->profile_pic))
                    {!! FormHelper::getProfile(Auth::user()->profile_pic) !!}
                @else
                <img src="{{ asset('dist/img/default-avatar-profile-icon-grey-photo-placeholder-vector-17317730.jpg') }}" alt="Default Image" style="height: 50px; width: 50px; border-radius: 50px">

                @endif
            </span>
        </div>
        <div class="message other-message float-right">
            {!! $value->message !!}
            @if (!empty($value->getFile()))
            <div>
                <a href="{{ $value->getFile() }}" download="" target="_blank">Download</a>
            </div>
            @endif
        </div>


    </li>
    @else

    <li class="clearfix">
        <div class="message-data">
            {{-- @dd($data['getChatData']) --}}
            <span href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                @if (!empty($data['getReceiver']->profile_pic))
                    {!! FormHelper::getProfile($data['getReceiver']->profile_pic) !!}
                @else
                <img src="{{ asset('dist/img/default-avatar-profile-icon-grey-photo-placeholder-vector-17317730.jpg') }}" alt="Default Image" style="height: 50px; width: 50px; border-radius: 50px">

                @endif
            </span>
            <span class="message-data-time">{{ Carbon\Carbon::parse($value->created_at)->diffForHumans() }}</span>
        </div>
        <div class="message my-message">
            {!! $value->message !!}
            @if (!empty($value->getFile()))
            <div>
                <a href="{{ $value->getFile() }}" download="" target="_blank">Download</a>
            </div>
            @endif
        </div>

    </li>
    @endif
    @endforeach
    @else
    <p>Please choose someone to chat.</p>
@endif
