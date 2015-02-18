@extends('......app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Import a Recipe</div>

				<div class="panel-body">
					{!! Form::open(['route' => 'recipes.import']) !!}
						@if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <li class="list-unstyled">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

						<div class="form-group{!! $errors->has('url') ? ' has-error' : '' !!}">
							{!! Form::label('url', 'URL') !!}
							{!! Form::input('url', 'url', null, ['class' => 'form-control', 'placeholder' => 'http://']); !!}
						</div>

						<div class="form-group">
							{!! Form::submit('Import Recipe', ['class' => 'btn btn-primary btn-block btn-lg']) !!}
						</div>
                    {!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
