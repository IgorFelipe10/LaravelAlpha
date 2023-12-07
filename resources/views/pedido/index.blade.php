@extends('layout.app')
@section('main')
<div id="page-pedidos">
    @foreach($pedidos as $pedido)
    <div id="pedido-card">
        <hr size="10">
        <h1 class="fw-bold mb-0 text-black">Pedido de Nº{{$pedido->PEDIDO_ID}}</h1>
        <h6 class="mb-0 text-muted">Data do pedido:{{$pedido->PEDIDO_DATA}} </h6>

        @foreach($pedido->pedidoItem as $item)
        @if($item->ITEM_QTD > 0)
        <div class="col">
            <div id="card" class="card">
                @if (count($item->Produto->ProdutoImagem) > 0)
                <img src="{{$item->Produto->ProdutoImagem[0]->IMAGEM_URL}}" class="card-img-top" alt="...">
                @else
                <img src="../img/indisponivel.jpg" class="card-img-top" alt="...">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{$item->Produto->PRODUTO_NOME}}</h5>
                    <p class="card-text">R${{$item->Produto->PRODUTO_PRECO}}</p>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
    @endforeach
</div>
@endsection
