<?php namespace Bacchus\Http\Controllers;

use Bacchus\Http\Requests\ImportRecipeRequest;
use Bacchus\Http\Requests\RecipeRequest;
use Bacchus\Recipe;

class RecipeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Bacchus\Recipe $recipe
     *
     * @return \Bacchus\Http\Controllers\Response
     */
    public function index(Recipe $recipe)
    {
        $recipes = $recipe->latest()->get();

        return view('recipes.index', compact('recipes'));
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
     * @param \Bacchus\Http\Requests\RecipeRequest $request
     *
     * @return \Bacchus\Http\Controllers\Response
     */
    public function store(RecipeRequest $request)
    {
        $recipe = Recipe::create($request->input());

        if ( !isset($recipe->id))
        {
            flash()->error("The Recipe could not be saved!");

            return redirect()->back()->withInput();
        }

        flash()->success("The Recipe has been saved!");

        return redirect()->route('recipes.show', [$recipe->id]);
    }

    /**
     * Show the form for importing a new resource.
     *
     * @return Response
     */
    public function provide()
    {
        return view('recipes.import');
    }

    /**
     * Import a newly created resource in storage.
     *
     * @param \Bacchus\Http\Requests\ImportRecipeRequest $request
     * @param \Bacchus\Recipe                            $recipe
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(ImportRecipeRequest $request, Recipe $recipe)
    {
        $recipe = $recipe->import($request->input('url'));

        if ( !isset($recipe->id))
        {
            flash()->error("The Recipe could not be imported!");

            return redirect()->back()->withInput();
        }

        flash()->success("The Recipe has been imported!");

        return redirect()->route('recipes.show', [$recipe->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param \Bacchus\Recipe $recipe
     *
     * @return \Bacchus\Http\Controllers\Response
     */
    public function show(Recipe $recipe)
    {
        return view('recipes.show', compact('recipe'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Bacchus\Recipe $recipe
     *
     * @return \Bacchus\Http\Controllers\Response
     */
    public function edit(Recipe $recipe)
    {
        return view('recipes.edit', compact('recipe'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Bacchus\Http\Requests\RecipeRequest $request
     * @param \Bacchus\Recipe                      $recipe
     *
     * @return \Bacchus\Http\Controllers\Response
     */
    public function update(RecipeRequest $request, Recipe $recipe)
    {
        if ( !$recipe->update($request->input()))
        {
            flash()->error("The Recipe could not be updated!");

            return redirect()->back()->withInput();
        }

        flash()->success("The Recipe has been updated!");

        return redirect()->route('recipes.show', [$recipe->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Bacchus\Recipe $recipe
     *
     * @throws \Exception
     * @return \Bacchus\Http\Controllers\Response
     */
    public function destroy(Recipe $recipe)
    {
        if ( !$recipe->delete())
        {
            flash()->error("The Recipe could not be deleted!");

            return redirect()->back();
        }

        flash()->success("The Recipe has been deleted!");

        return redirect()->route('recipes.index');
    }
}
