<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')
        <script id="{{$id}}" name="{{$name}}" type="text/plain" style="width:1024px;height:500px;">{!! old($column, $value) !!}</script>
    </div>
</div>