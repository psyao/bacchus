@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">List the Recipes</div>

				<div class="panel-body">
					@foreach($recipes as $recipe)
						<li>
							{!! link_to_route('recipes.show', $recipe->name, [$recipe->id]) !!}
							@if (Auth::check())
								<a class="pull-right" data-toggle="modal" data-target="#delete_recipe_{!! $recipe->id !!}"><i class="fa fa-times"></i> Delete</a>
								<a class="pull-right" href="{!! route('recipes.edit', [$recipe->id]) !!}"><i class="fa fa-pencil-square-o"></i> Edit</a>
							@endif
						</li>
					@endforeach
				</div>
			</div>
		</div>
	</div>
</div>

@if (Auth::check())
	@foreach($recipes as $recipe)
		@include('recipes._delete', ['id' => $recipe->id])
	@endforeach
@endif
@endsection
