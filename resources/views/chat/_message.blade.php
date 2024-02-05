<div class="chat-header clearfix">
    @include('chat._header')
</div>
<div class="chat-history">
    @include('chat._chat')
</div>
<div class="chat-message clearfix">
    <form action="{{ route('chat.submit_message') }}" id="submit_message" method="POST" class="mb-0" enctype="multipart/form-data">
        <input type="hidden" value="{{ isset($data['getReceiver']) ? $data['getReceiver']->id : '' }}" name="receiver_id">
        @csrf
        <div>
            <textarea name="message" required id="ClearMessage" class="form-control"></textarea>
        </div>
        <div class="row">
            <div class="col-md-6 hidden-sm">
                <a href="javascript:void(0);" id="OpenFile" style="margin-top: 10px;" class="btn btn-outline-primary"><i class="fa fa-image"></i></a>
                <input type="file" name="file_name" style="display: none;" id="file_name">
                <span id="getFileName"></span>
            </div>
            <div class="col-md-6" style="text-align: right;">
                <button style="margin-top: 10px;" class="btn btn-primary" type="submit">Send</button>
            </div>
        </div>

    </form>
</div>
