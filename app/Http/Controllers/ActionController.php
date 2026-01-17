<?php

namespace App\Http\Controllers;

use App\Models\Action;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $actions = Action::select(
            'id',
            'action',
            'group_name',
            'points'
        )
        ->paginate(10);

        return view('dashboard.actions.index', compact('actions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.actions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_name' => 'required|string',
            'action' => 'required|string',
            'points' => 'required|numeric',
        ],
        [
            'group_name' => 'O grupo da ação é necessário.',
            'action' => 'A descrição da ação é necessária.',
            'points' => 'A pontuação é necessária.',
        ]);

        if(Action::create($validated)) {
            return redirect(route('actions.index'))->with('success', 'Adicionado com sucesso.');
        }
        return redirect()->back()->with('error', 'Houve algum erro ao adicionar.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Action  $action
     * @return \Illuminate\Http\Response
     */
    public function show(Action $action)
    {
        $action_single = $action->select(
            'id',
            'group_name',
            'action',
            'points'
        )->first();

        return view('dashboard.actions.edit', compact('action_single'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Action  $action
     * @return \Illuminate\Http\Response
     */
    public function edit(Action $action)
    {
        return view('dashboard.actions.edit', compact('action'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Action  $action
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Action $action)
    {
        $request->validate([
            'group_name' => 'required|string',
            'action' => 'required|string',
            'points' => 'required|numeric'
        ],
        [
            'group_name' => 'O grupo da ação é necessário.',
            'action' => 'A descrição da ação é necessária.',
            'points' => 'A pontuação é necessária.',
        ]);

        $action->group_name = $request->group_name;
        $action->action = $request->action;
        $action->points = $request->points;

        if($action->save()) {
            return redirect(route('actions.index'))->with('success', 'Atualizado com sucesso.');
        }
        return redirect()->back()->with('error', 'Houve algum erro ao atualizar.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Action  $action
     * @return \Illuminate\Http\Response
     */
    public function destroy(Action $action)
    {
        if (!$action->delete()) {
            return back()->with('error', 'Erro ao deletar.');
        }
        return back()->with('success', 'Deletado com sucesso.');
    }
}
