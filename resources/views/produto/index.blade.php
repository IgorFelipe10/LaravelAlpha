@extends('layout.app')
    @section('main')
    <style>


</style>
    <h1 >Resultados para "{{$search}}"</h1>
    <ul>
        @if(count($produtos) < 1 )
            <h3 >Não existem resultados para "{{$search}}"</h3>
        @else
        <div class="row row-cols-1 row-cols-md-3 g-4" id="container">
    
        @foreach ($produtos as $produto)



                <div class="col">
                    <div class="card">
                    @if (count($produto->ProdutoImagem)>0 )
                    <img src="{{$produto->ProdutoImagem[0]->IMAGEM_URL}}" class="card-img-top" alt="...">
                    @else
                    <img src="../img/indisponivel.jpg" class="card-img-top" alt="...">
                    @endif
                        <div class="card-body">
                            <h5 class="card-title">{{$produto->PRODUTO_NOME}}</h5>
                            <p class="card-text">R${{$produto->PRODUTO_PRECO}}</p>
                            <a href="{{route('produto.show', $produto->PRODUTO_ID)}}"><button type="submit" class="buttonLogin">Comprar <i class="ri-shopping-cart-line"></i></button></a>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        @endif
    </ul>

@endsection
