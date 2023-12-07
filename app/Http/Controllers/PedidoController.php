<?php
namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Carrinho;
use App\Models\PedidoItem;
use App\Models\Endereco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::where('USUARIO_ID', Auth::user()->USUARIO_ID)->get();
        return view('pedido.index')->with('pedidos', $pedidos);
    }

    public function show(Pedido $pedido)
    {
        $carrinho = PedidoItem::where('PEDIDO_ID', $pedido->PEDIDO_ID)->get();
        
        $descontoTotal = 0;
    
        foreach ($carrinho as $item) {
            $descontoProduto = $item->Produto->PRODUTO_DESCONTO; 
            $descontoTotal += $descontoProduto * $item->ITEM_QTD;
        }
    
        return view('pedido.show', ['pedido' => $pedido, 'carrinho' => $carrinho, 'descontoTotal' => $descontoTotal]);
    }
    
    
    
    
    public function checkout(Request $request)
    {
        Log::info('Método checkout está sendo acessado.');
        $request->validate([
            'endereco_id' => 'required'
        ]);
    
        $itensCarrinho = Carrinho::where('USUARIO_ID', Auth::user()->USUARIO_ID)->get();
    
        $enderecoId = $request->input('endereco_id');
    
        DB::beginTransaction();
    
        try {
    
            if ($itensCarrinho->isNotEmpty()) {
                $pedido = Pedido::create([
                    'USUARIO_ID' => Auth::user()->USUARIO_ID,
                    'STATUS_ID' => 1, 
                    'ENDERECO_ID' => $enderecoId,
                    'PEDIDO_DATA' => now()
                ]);
    
                $pedido->endereco()->associate(Endereco::find($enderecoId));
                $pedido->save();
    
                foreach ($itensCarrinho as $item) {
                    PedidoItem::create([
                        'PEDIDO_ID' => $pedido->PEDIDO_ID,
                        'PRODUTO_ID' => $item->PRODUTO_ID,
                        'ITEM_QTD' => $item->ITEM_QTD,
                        'ITEM_PRECO' => $item->Produto->PRODUTO_PRECO
                    ]);
    
                    $item->ITEM_QTD = 0;
                    $item->save();
                }
    
    
                DB::commit();
                Log::info('Pedido criado com sucesso. ID do pedido: ' . $pedido->PEDIDO_ID);
                return Redirect::route('pedido.show', ['pedido' => $pedido->PEDIDO_ID])->with('success', 'Compra finalizada com sucesso!');
            } else {
                Log::warning('Tentativa de finalizar a compra sem itens no carrinho.');
                return Redirect::back()->with('error', 'Não há itens no carrinho para finalizar a compra.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro durante a transação: ' . $e->getMessage());
            return Redirect::back()->with('error', 'Erro durante a finalização da compra.');
        }
    }
    
}

?>
