@extends('layouts.app')
@section('style')
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <style type="text/css">
        .card {
            background: #fff;
            transition: .5s;
            border: 0;
            margin-bottom: 30px;
            border-radius: .55rem;
            position: relative;
            width: 100%;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 10%);
        }

        .chat-app .people-list {
            width: 280px;
            position: absolute;
            left: 0;
            top: 0;
            padding: 20px;
            z-index: 7;
            background: #fff;
        }

        .chat-list {
            height: 400px;
            overflow: auto;
        }

        .chat-app .chat {
            margin-left: 280px;
            border-left: 1px solid #eaeaea
        }

        .people-list {
            -moz-transition: .5s;
            -o-transition: .5s;
            -webkit-transition: .5s;
            transition: .5s
        }

        .people-list .chat-list li {
            padding: 10px 15px;
            list-style: none;
            border-radius: 3px
        }

        .people-list .chat-list li:hover {
            background: #efefef;
            cursor: pointer
        }

        .people-list .chat-list li.active {
            background: #efefef
        }

        .people-list .chat-list li .name {
            font-size: 15px
        }

        .people-list .chat-list img {
            width: 45px;
            height: 45px;
            border-radius: 50%
        }

        .people-list img {
            float: left;
            border-radius: 50%
        }

        .people-list .about {
            float: left;
            padding-left: 8px
        }

        .people-list .status {
            color: #999;
            font-size: 13px
        }

        .chat .chat-header {
            padding: 15px 20px;
            border-bottom: 2px solid #f4f7f6
        }

        .chat .chat-header img {
            float: left;
            border-radius: 40px;
            width: 40px
        }

        .chat .chat-header .chat-about {
            float: left;
            padding-left: 10px
        }

        .chat .chat-history {
            padding: 20px;
            border-bottom: 2px solid #fff;
            height: 400px;
            overflow: auto;
        }

        .chat .chat-history ul {
            padding: 0
        }

        .chat .chat-history ul li {
            list-style: none;
            margin-bottom: 30px
        }

        .chat .chat-history ul li:last-child {
            margin-bottom: 0px
        }

        .chat .chat-history .message-data {
            margin-bottom: 15px
        }

        .chat .chat-history .message-data img {
            border-radius: 40px;
            width: 40px
        }

        .chat .chat-history .message-data-time {
            color: #434651;
            padding-left: 6px
        }

        .chat .chat-history .message {
            color: #444;
            padding: 18px 20px;
            line-height: 26px;
            font-size: 16px;
            border-radius: 7px;
            display: inline-block;
            position: relative
        }

        .chat .chat-history .message:after {
            bottom: 100%;
            left: 7%;
            border: solid transparent;
            content: " ";
            height: 0;
            width: 0;
            position: absolute;
            pointer-events: none;
            border-bottom-color: #fff;
            border-width: 10px;
            margin-left: -10px
        }

        .chat .chat-history .my-message {
            background: #efefef
        }

        .chat .chat-history .my-message:after {
            bottom: 100%;
            left: 30px;
            border: solid transparent;
            content: " ";
            height: 0;
            width: 0;
            position: absolute;
            pointer-events: none;
            border-bottom-color: #efefef;
            border-width: 10px;
            margin-left: -10px
        }

        .chat .chat-history .other-message {
            background: #e8f1f3;
            text-align: right
        }

        .chat .chat-history .other-message:after {
            border-bottom-color: #e8f1f3;
            left: 93%
        }

        .chat .chat-message {
            padding: 20px
        }

        .online,
        .offline,
        .me {
            margin-right: 2px;
            font-size: 8px;
            vertical-align: middle
        }

        .online {
            color: #86c541
        }

        .offline {
            color: #e47297
        }

        .me {
            color: #1d8ecd
        }

        .float-right {
            float: right
        }

        .clearfix:after {
            visibility: hidden;
            display: block;
            font-size: 0;
            content: " ";
            clear: both;
            height: 0
        }

        @media only screen and (max-width: 767px) {
            .chat-list {
                height: 250px;
            }

            .chat-app .people-list {
                /* height: 465px; */
                width: 100%;
                overflow-x: auto;
                background: #fff;
                /* left: -400px;
                                                            display: none; */
                position: relative;
                border-bottom: 2px dashed #d1d1d1;
            }

            .chat-app .people-list.open {
                left: 0
            }

            .chat-app .chat {
                margin: 0
            }

            .chat-app .chat .chat-header {
                border-radius: 0.55rem 0.55rem 0 0
            }

            .chat-app .chat-history {
                height: 350px;
                overflow-x: auto
            }
        }

        @media only screen and (min-width: 768px) and (max-width: 992px) {
            .chat-app .chat-list {
                height: 650px;
                overflow-x: auto
            }

            .chat-app .chat-history {
                height: 600px;
                overflow-x: auto
            }
        }

        #AppendMessage {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: landscape) and (-webkit-min-device-pixel-ratio: 1) {
            .chat-app .chat-list {
                height: 480px;
                overflow-x: auto
            }

            .chat-app .chat-history {
                height: calc(100vh - 350px);
                overflow-x: auto
            }
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>My Chat</h1>
                    </div>

                </div>
            </div>
        </section>

        <section class="content">

            <div class="container-fluid">
                <div class="row clearfix">
                    <div class="col-lg-12">
                        <div class="card chat-app">
                            <div id="plist" class="people-list">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <p class="input-group-text" id="getSearchedUser">Chat History</p>
                                    </div>
                                    {{-- <input type="text" id="getSearch" class="form-control" placeholder="Search...">
                                    <input type="hidden" id="getReceiverIdDynamic" value="{{ $data['receiver_id'] }}"> --}}
                                </div>
                                <ul class="list-unstyled chat-list mt-2 mb-0" id="getSearchedUserDynamic">
                                    @include('chat._user')
                                </ul>
                            </div>
                            <div class="chat" id="getAllChatMessage">
                                {{-- @if (!empty($getReceiver)) --}}
                                @include('chat._message')


                                {{-- @endif --}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>


    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.33/moment-timezone.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.10.0/dist/echo.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script> --}}

    {{-- <script>
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env('PUSHER_APP_KEY') }}',
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true,
        });
    </script> --}}


    <script type="text/javascript">
        // Set timezone
        moment.tz.setDefault('Asia/Yangon');

        // Function to update message time dynamically
        function updateMessageTime() {
            $('.message-data-time').each(function() {
                var timestamp = $(this).data('timestamp');
                if (timestamp) {
                    var formattedTime = moment(timestamp, 'YYYY-MM-DD HH:mm:ss').tz('Asia/Yangons')
                        .fromNow(); // Replace 'YourTimeZone' with your actual timezone
                    $(this).text(formattedTime);
                }
            });
        }

        // Set interval to update message time every minute
        setInterval(updateMessageTime, 60000);

        $(document).on('submit', '#submit_message', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: "{{ url('submit_message') }}",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    var value = data.success.getChat;

                    // Create a new list item based on the message sender
                    var liHtml = '';
                    if (value.sender_id == {{ Auth::user()->id }}) {
                        liHtml = '<li class="clearfix" style="list-style-type: none;">' +
                            '<div class="message-data text-right">' +
                            '<span class="message-data-time" data-timestamp="' + value.created_at +
                            '"></span>' +
                            '<span href="javascript:void(0);" data-toggle="modal" data-target="#view_info">' +
                            '@if (!empty(Auth::user()->profile_pic))' +
                            '{!! FormHelper::getProfile(Auth::user()->profile_pic) !!}' +
                            '@else' +
                            '<img src="{{ asset('dist/img/default-avatar-profile-icon-grey-photo-placeholder-vector-17317730.jpg') }}" alt="Default Image" style="height: 50px; width: 50px; border-radius: 50px">' +
                            '@endif' +
                            '</span>' +
                            '</div>' +
                            '<div class="message other-message float-right">' + value.message +
                            '</div>' +
                            '</li>';
                    } else {
                        liHtml = '<li class="clearfix" style="list-style-type: none;">' +
                            '<div class="message-data">' +
                            '<span href="javascript:void(0);" data-toggle="modal" data-target="#view_info">' +
                            '@if (!empty($data['getReceiver']->profile_pic))' +
                            '{!! FormHelper::getProfile($data['getReceiver']->profile_pic) !!}' +
                            '@else' +
                            '<img src="{{ asset('dist/img/default-avatar-profile-icon-grey-photo-placeholder-vector-17317730.jpg') }}" alt="Default Image" style="height: 50px; width: 50px; border-radius: 50px">' +
                            '@endif' +
                            '</span>' +
                            '<span class="message-data-time" data-timestamp="' + value.created_at +
                            '"></span>' +
                            '</div>' +
                            '<div class="message my-message">' + value.message + '</div>' +
                            '</li>';
                    }

                    // Append the new list item to the chat history container
                    $('.chat-history').append(liHtml);

                    // Update the message time
                    updateMessageTime();

                    // Clear the message input
                    $('#ClearMessage').val('');
                    $('#file_name').val('');
                    $('#getFileName').html('');


                    // Scroll down to the latest message
                    scrollDown();
                },
                error: function(data) {
                    console.log(data);
                },
            });
        });

        function scrollDown() {
            $('.chat-history').animate({
                scrollTop: $('.chat-history').prop('scrollHeight') + 30000
            }, 500);
        }

        scrollDown();

        $(document).on('click', '#OpenFile', function(e) {
            $('#file_name').trigger('click');
        });

        $(document).on('change', '#file_name', function(e) {
            var filename = this.files[0].name;
            console.log(filename);
            $('#getFileName').html(filename);
        });
    </script>
@endsection
