@if ($errors->any())
    <ul class="alert alert-danger">
        @foreach($errors->all() as $error)
            <li class="list-unstyled">{{ $error }}</li>
        @endforeach
    </ul>
@endif

<div class="row">
    <div class="form-group col-xs-12{!! $errors->has('name') ? ' has-error' : '' !!}">
        {!! Form::label('name', 'Name') !!}
        {!! Form::text('name', null, ['class' => 'form-control', 'required']); !!}
    </div>

    <div class="form-group col-sm-4{!! $errors->has('preparation_time') ? ' has-error' : '' !!}">
        {!! Form::label('preparation_time', 'Preparation time') !!}
        <div class="input-group">
            {!! Form::input('number', 'preparation_time', null, ['class' => 'form-control', 'min' => 0, 'max' => 600]); !!}
            <span class="input-group-addon">min</span>
        </div>
    </div>

    <div class="form-group col-sm-4{!! $errors->has('cooking_time') ? ' has-error' : '' !!}">
        {!! Form::label('cooking_time', 'Cooking time') !!}
        <div class="input-group">
            {!! Form::input('number', 'cooking_time', null, ['class' => 'form-control', 'min' => 0, 'max' => 600]); !!}
            <span class="input-group-addon">min</span>
        </div>
    </div>

    <div class="form-group col-sm-4{!! $errors->has('total_time') ? ' has-error' : '' !!}">
        {!! Form::label('total_time', 'Total time') !!}
        <div class="input-group">
            {!! Form::input('number', 'total_time', null, ['class' => 'form-control', 'required', 'min' => 1, 'max' => 600]); !!}
            <span class="input-group-addon">min</span>
        </div>
    </div>

    <div class="form-group col-sm-4{!! $errors->has('level') ? ' has-error' : '' !!}">
        {!! Form::label('level', 'Level') !!}
        {!! Form::select('level', ['Easy', 'Medium', 'Hard'], null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-4{!! $errors->has('cost') ? ' has-error' : '' !!}">
        {!! Form::label('cost', 'Cost') !!}
        {!! Form::select('cost', ['Low', 'Medium', 'High'], null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-4{!! $errors->has('guests') ? ' has-error' : '' !!}">
        {!! Form::label('guests', 'Guests') !!}
        {!! Form::input('number', 'guests', null, ['class' => 'form-control', 'min' => 1, 'max' => 20]); !!}
    </div>

    <div class="form-group col-xs-12{!! $errors->has('url') ? ' has-error' : '' !!}">
        {!! Form::label('url', 'Source') !!}
        {!! Form::input('url', 'url', null, ['class' => 'form-control', 'placeholder' => 'http://']); !!}
    </div>



    <div class="form-group col-xs-12">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Add Recipe', ['class' => 'btn btn-primary btn-block btn-lg']) !!}
    </div>
</div>
