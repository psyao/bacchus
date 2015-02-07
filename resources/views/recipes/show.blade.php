@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Show the Recipe</div>

				<div class="panel-body">
					<article>
						<h1>
							{{ $recipe->name }}
							@if (Auth::check())
								<a class="btn btn-danger btn-sm pull-right" data-toggle="modal" data-target="#delete_recipe_{!! $recipe->id !!}"><i class="fa fa-times"></i> Delete</a>
								<a class="btn btn-primary btn-sm pull-right" href="{!! route('recipes.edit', [$recipe->id]) !!}"><i class="fa fa-pencil-square-o"></i> Edit</a>
							@endif
						</h1>

						<h2>Informations</h2>
						<ul>
							<li>Preparation: {{ $recipe->preparation_time }} minutes</li>
							<li>Cooking: {{ $recipe->cooking_time }} minutes</li>
							<li>Total: {{ $recipe->total_time }} minutes</li>
						</ul>

						<ul>
							<li>Level: {{ $recipe->level }}</li>
							<li>Guests: {{ $recipe->guests }} persons</li>
							<li>Cost: {{ $recipe->cost }}</li>
						</ul>

						<h2>Ingredients</h2>

						<h2>Steps</h2>
					</article>
				</div>
			</div>
		</div>
	</div>
</div>

@if (Auth::check())
	@include('recipes._delete', ['id' => $recipe->id])
@endif
@endsection
