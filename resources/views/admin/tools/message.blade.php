<div class="btn-group" data-toggle="buttons">
    @foreach($options as $option => $label)
        <label class="btn btn-default btn-sm {{ \Request::get('state', '9') == $option ? 'active' : '' }}">
            <input type="radio" class="message-state" value="{{ $option }}">{{$label}}
        </label>
    @endforeach
</div>