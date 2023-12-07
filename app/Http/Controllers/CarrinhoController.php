<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Carrinho;
use App\Models\Endereco;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CarrinhoController extends Controller
{
    public function store(Produto $produto, Request $request){
        $item = Carrinho::where('USUARIO_ID', Auth::user()->USUARIO_ID)
                ->where('PRODUTO_ID', $produto->PRODUTO_ID)->first();

        if($item){
            $item = $item->update([
                'ITEM_QTD' => $request->ITEM_QTD
            ]);
        }else{
            $item =Carrinho::create([
                'USUARIO_ID' => Auth::user()->USUARIO_ID,
                'PRODUTO_ID' => $produto->PRODUTO_ID,
                'ITEM_QTD' => 1
            ]);
        }
        return redirect(route('carrinho.index'));
    }

    public function index(){
        $carrinho = Carrinho::where('USUARIO_ID', Auth::user()->USUARIO_ID)->get();
        $enderecos = Endereco::where('USUARIO_ID', Auth::user()->USUARIO_ID)->get();
    
        $descontoTotal = 0;
    
        foreach ($carrinho as $item) {
            $descontoProduto = $item->Produto->PRODUTO_DESCONTO * $item->ITEM_QTD;
            $descontoTotal += $descontoProduto;
        }
    
        $descontoTotal = floatval($descontoTotal);
    
        return view('carrinho.store')->with('carrinho', $carrinho)->with('enderecos', $enderecos)->with('descontoTotal', $descontoTotal);
    }
    public function removerProduto(Request $request)
{
    $produtoId = $request->input('produto_id');

    $carrinho = Session::get('carrinho', []);

    if (isset($carrinho[$produtoId])) {
        
        unset($carrinho[$produtoId]);

        Session::put('carrinho', $carrinho);

        return response()->json(['success' => true, 'message' => 'Produto removido do carrinho com sucesso']);
    } else {
        return response()->json(['success' => false, 'message' => 'Produto não encontrado no carrinho']);
    }
}
}