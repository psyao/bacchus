@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">List the Recipes</div>

				<div class="panel-body">
					<table class="table">
						@foreach($recipes as $recipe)
							<tr>
								<td>{!! link_to_route('recipes.show', $recipe->name, [$recipe->id]) !!}</td>
								@if (Auth::check())
									<td>
										<a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#delete_recipe_{!! $recipe->id !!}"><i class="fa fa-times"></i></a>
										<a class="btn btn-danger btn-xs" href="{!! route('recipes.edit', [$recipe->id]) !!}"><i class="fa fa-pencil-square-o"></i></a>
									</td>
								@endif
							</tr>
						@endforeach
					</table>
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
