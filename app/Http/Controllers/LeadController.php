<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Cart;
use App\Models\Notes;
use App\Models\Order;
use App\Models\PopUpLeads;
use App\Models\Product;
use App\Models\User;
use App\Models\UserAction;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index()
    {
        $users = User::select(
            'id',
            'name',
        )
        ->paginate(10);

        $actions_count = [];
        $purchases_count = [];

        foreach($users as $user) {
            $actions_count[$user->id] = UserAction::where('user', $user->id)->where('status', 0)->count();
            $purchases_count[$user->id] = Order::where('user', $user->id)->count();
        }

        return view('dashboard.leads.index', compact('users', 'actions_count', 'purchases_count'));
    }

    public function manager($id) {
        $user = User::select(
            'users.id',
            'users.name',
            'users.email',
            'users.phone',
            'users.birth',
            'completes.street',
            'completes.number',
            'completes.complement',
            'completes.locality',
            'completes.city',
            'completes.region_code',
        )
        ->where('users.id', $id)
        ->leftJoin('completes', 'completes.user', '=', 'users.id')
        ->first();

        if(!$user) {
            abort(404, 'Pedido não encontrado.');
        }

        $orders = Order::select(
            'id',
            'products',
            'amount',
            'status',
            'coupon',
            'created_at',
            'updated_at'
        )->where('user', $id)
        ->get();

        $carts = Cart::select(
            'ip',
            'coupon',
            'products',
            'amount',
            'updated_at',
        )
        ->where('user', $id)
        ->get();

        // dd($carts);

        $carts_complete = [];

        foreach($carts as $cart) {
            $products = [];
            foreach(json_decode($cart->products) as $product) {
                $products[] = [
                    'title' => Product::select('title')->where('id', $product->product)->first()->title,
                    'qtd' => $product->qtd,
                    'value_uni' => $product->value_uni,
                    'city' => '',
                ];
            }
            $carts_complete[] = [
                'ip' => $cart->ip,
                'coupon' => $cart->coupon,
                'products' => $products,
                'amount' => $cart->amount,
                'updated_at' => $cart->updated_at
            ];
        }

        $actions = UserAction::select(
            'actions.id as action_id',
            'actions.action',
            'actions.group_name',
            'user_actions.status',
            'user_actions.id as user_actions_id'
        )
        ->where('user', $id)
        ->leftJoin('actions', 'actions.id', '=', 'user_actions.action')
        ->get();

        $lead = PopUpLeads::select('created_at')
        ->where('name', $user->name)
        ->orWhere('phone', $user->phone)
        ->orWhere('email', $user->email)
        ->first();

        $notes = Notes::select('id', 'note', 'updated_at')
        ->where('user', $id)
        ->get();

        return view('dashboard.leads.edit', compact('user', 'orders', 'actions', 'lead', 'notes', 'carts', 'carts_complete'));
    }

    public function action_pending($id) {
        // dd(UserAction::where('id', $id)->first());
        if (!UserAction::where('id', $id)->first()->update(['status' => 0])) {
            return back()->with('error', 'Erro ao atribuir novo status.');
        }
        return back()->with('success', 'Status atualizado com sucesso.');
    }

    public function action_done($id) {
        // $result = UserAction::where('id', $id)->first();
        // dd($result);

        // dd(UserAction::where('id', $id)->first());
        if (!UserAction::where('id', $id)->first()->update(['status' => 1])) {
            return back()->with('error', 'Erro ao atribuir novo status.');
        }
        return back()->with('success', 'Status atualizado com sucesso.');
    }

    public function remove_action(UserAction $action) {
        if (!$action->delete()) {
            return back()->with('error', 'Erro ao deletar.');
        }
        return back()->with('success', 'Deletado com sucesso.');
    }

    public function setActionOnLead(Request $request) {
        $validated = $request->validate([
            'user' => 'required',
            'group_name' => 'required|string',
            'action' => 'required|string',
            'points' => 'required|numeric',
        ],
        [
            'group_name' => 'O grupo da ação é necessário.',
            'action' => 'A descrição da ação é necessária.',
            'points' => 'A pontuação é necessária.',
        ]);

        $action = Action::select()->where('action', $validated['action'])->first();

        if(!$action) {
            $action = Action::create($validated);
            if(!$action) {
                return redirect()->back()->with('error', 'Erro ao criar nova ação.');
            }
        }
        
        $action_attr = UserAction::create([
            'user' => $validated['user'],
            'action' => $action->id,
            'status' => 0
        ]);

        if($action_attr) {
            return redirect()->back()->with('success', 'Adicionado com sucesso.');
        }
        return redirect()->back()->with('error', 'Houve algum erro ao adicionar.');
    }

    public function createNote(Request $request) {
        $validated = $request->validate([
            'user' => 'required|integer',
            'note' => 'required|string',
        ],
        [
            'user' => 'Faltando informar usuário na nota, contate o administrador.',
            'note' => 'A nota está em branco.',
        ]);

        $note = Notes::create($validated);

        if($note) {
            return redirect()->back()->with('success', 'Adicionado com sucesso.');
        }
        return redirect()->back()->with('error', 'Houve algum erro ao adicionar.');
    }
}
