<div class="row">
    <div class="col-lg-12">
        @if (isset($data['getReceiver']))
            <span href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                @if (!empty($data['getReceiver']->profile_pic))
                    {!! FormHelper::getProfile($data['getReceiver']->profile_pic) !!}
                @else
                    <img src="{{ asset('dist/img/default-avatar-profile-icon-grey-photo-placeholder-vector-17317730.jpg') }}" alt="Default Image" style="height: 50px; width: 50px; border-radius: 50px">
                @endif
            </span>
            <div class="chat-about">
                <h6 class="m-b-0">{{ $data['getReceiver']->name }}</h6>
                <small>
                    @if (!empty($data['getReceiver']->OnlineUser()))
                        <span style="color: rgb(4, 230, 4);">Online</span>
                    @else
                    Last seen: {{ Carbon\Carbon::parse($data['getReceiver']->updated_at)->diffForHumans() }}
                    @endif

                </small>
            </div>

        @endif
    </div>
</div>

