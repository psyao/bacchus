@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Edit a Recipe</div>

				<div class="panel-body">
					{!! Form::model($recipe, ['route' => ['recipes.update', $recipe->id], 'method' => 'PATCH']) !!}
						@include('recipes._form', ['submitButtonText' => 'Edit Recipe'])
                    {!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
