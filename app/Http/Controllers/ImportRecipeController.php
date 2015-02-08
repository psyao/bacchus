<?php namespace Bacchus\Http\Controllers;

use Bacchus\Http\Requests\ImportRecipeRequest;
use Bacchus\Recipe;

class ImportRecipeController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('recipes.import');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Bacchus\Http\Requests\ImportRecipeRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ImportRecipeRequest $request)
    {
        $recipe = Recipe::import($request->get('url'));
        $recipe->save();

        if (!$recipe->id)
        {
            flash()->error("The Recipe could not be imported!");

            return redirect()->back()->withInput();
        }

        flash()->success("The Recipe has been imported!");

        return redirect()->route('recipes.edit', [$recipe->id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
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
     * @param  int $id
     *
     * @return Response
     */
    public function update($id)
    {
        //
    }
}
