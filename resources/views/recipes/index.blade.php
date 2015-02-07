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
							{!! link_to_route('recipes.edit', 'Edit', [$recipe->id], ['class' => 'pull-right']) !!}
						</li>
					@endforeach
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
