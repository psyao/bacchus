<?php namespace Bacchus\Http\Controllers;

use Bacchus\Http\Requests\CreateRecipeRequest;
use Bacchus\Recipe;

class RecipeController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return view('recipes.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
     * @param \Bacchus\Http\Requests\CreateRecipeRequest $request
     *
     * @return \Bacchus\Http\Controllers\Response
	 */
    public function store(CreateRecipeRequest $request)
	{
        $recipe = Recipe::create($request->input());

        if ( !isset($recipe->id))
        {
            flash()->error("The Recipe could not be saved!");

            return redirect()->back()->withInput();
        }

        flash()->success("The Recipe has been saved!");

        return redirect()->route('recipes.index');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
     *
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
     *
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
     *
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
     *
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
}
