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

    <div class="form-group col-sm-3{!! $errors->has('preparation_time') ? ' has-error' : '' !!}">
        {!! Form::label('preparation_time', 'Preparation time') !!}
        <div class="input-group">
            {!! Form::input('number', 'preparation_time', null, ['class' => 'form-control', 'min' => 1]); !!}
            <span class="input-group-addon">min</span>
        </div>
    </div>

    <div class="form-group col-sm-3{!! $errors->has('cooking_time') ? ' has-error' : '' !!}">
        {!! Form::label('cooking_time', 'Cooking time') !!}
        <div class="input-group">
            {!! Form::input('number', 'cooking_time', null, ['class' => 'form-control', 'min' => 0]); !!}
            <span class="input-group-addon">min</span>
        </div>
    </div>

    <div class="form-group col-sm-3{!! $errors->has('rest_time') ? ' has-error' : '' !!}">
        {!! Form::label('rest_time', 'Rest time') !!}
        <div class="input-group">
            {!! Form::input('number', 'rest_time', null, ['class' => 'form-control', 'min' => 0]); !!}
            <span class="input-group-addon">min</span>
        </div>
    </div>

    <div class="form-group col-sm-3{!! $errors->has('total_time') ? ' has-error' : '' !!}">
        {!! Form::label('total_time', 'Total time') !!}
        <div class="input-group">
            {!! Form::input('number', 'total_time', null, ['class' => 'form-control', 'required', 'min' => 1]); !!}
            <span class="input-group-addon">min</span>
        </div>
    </div>

    <div class="form-group col-sm-4{!! $errors->has('difficulty') ? ' has-error' : '' !!}">
        {!! Form::label('difficulty', 'Difficulty') !!}
        {!! Form::select('difficulty', ['Easy', 'Medium', 'Hard'], null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-4{!! $errors->has('price') ? ' has-error' : '' !!}">
        {!! Form::label('price', 'Price') !!}
        {!! Form::select('price', ['Low', 'Medium', 'High'], null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-4{!! $errors->has('guests') ? ' has-error' : '' !!}">
        {!! Form::label('guests', 'Guests') !!}
        {!! Form::input('number', 'guests', null, ['class' => 'form-control', 'min' => 1]); !!}
    </div>

    <div class="form-group col-xs-12{!! $errors->has('url') ? ' has-error' : '' !!}">
        {!! Form::label('url', 'Source') !!}
        {!! Form::input('url', 'url', null, ['class' => 'form-control', 'placeholder' => 'http://']); !!}
    </div>

    @if (isset($recipe))
        @foreach($recipe->ingredients as $ingredient)
            <div class="form-group col-sm-6{!! $errors->has('ingredients[name]') ? ' has-error' : '' !!}">
                {!! Form::label('ingredients[body]', 'Ingredient') !!}
                {!! Form::text('ingredients[' . $ingredient->id . '][body]', $ingredient->body, ['class' => 'form-control']); !!}
            </div>
        @endforeach

        @foreach($recipe->steps as $step)
            <div class="form-group col-xs-12{!! $errors->has('steps[name]') ? ' has-error' : '' !!}">
                {!! Form::label('steps[body]', 'Step ' . $step->position) !!}
                {!! Form::textarea('steps[' . $step->id . '][body]', $step->body, ['class' => 'form-control', 'rows' => 2]); !!}
            </div>
        @endforeach
    @else
        @for ($i = 0; $i < 5; $i++)
            <div class="form-group col-sm-6{!! $errors->has('ingredients[name]') ? ' has-error' : '' !!}">
                {!! Form::label('ingredients[body]', 'Ingredient') !!}
                {!! Form::text('ingredients[\'new' . $i . '\'][body]', null, ['class' => 'form-control']); !!}
            </div>
        @endfor

        @for ($i = 0; $i < 5; $i++)
            <div class="form-group col-xs-12{!! $errors->has('steps[name]') ? ' has-error' : '' !!}">
                {!! Form::label('steps[body]', 'Steps') !!}
                {!! Form::textarea('steps[\'new' . $i . '\'][body]', null, ['class' => 'form-control', 'rows' => 2]); !!}
            </div>
        @endfor
    @endif

    <div class="form-group col-xs-12">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Add Recipe', ['class' => 'btn btn-primary btn-block btn-lg']) !!}
    </div>
</div>
