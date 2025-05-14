<x-layout>
    <x-slot:title>{{$currentCategoryName}}</x-slot>
    @if ($currentCategoryName)
        <h1>{{$currentCategoryName}}</h1>
    @endif
    <div class="row">
        <aside class="col-md-3">
            <nav>
                <ul>
                    @foreach ($tree as $item)
                        <li>
                            <a href="{{ url()->query("/category/$item->id",
                                [
                                    'order_by' => 'name',
                                    'order' => request('order') === 'asc' ? 'desc' : 'asc',
                                    'page' => request('page')
                                ]) }}"
                                style="{{ in_array($item->id, $selectedGroupsIds) ? 'color: green' : '' }}">{{$item->name}} ({{ $item->getProductsCount() }})</a>
                            @if($item->children()->count() && in_array($item->id, $selectedGroupsIds))
                                @include('components/subgroup', [
                                    'subgroups' => $item->children()->get(),
                                    'selectedGroupsIds' => $selectedGroupsIds
                                    ])
                            @endif    
                        </li>
                    @endforeach
                </ul>
            </nav>
        </aside>
        <main class="col-md-9">
            <div class="row">
                <div class="col-md-2 offset-md-10 gx-2 d-flex justify-content-between">
                    <a href="{{ url()->query(
                        Request::url(),
                        [
                            'order_by' => 'name',
                            'order' => request('order') === 'asc' ? 'desc' : 'asc',
                            'page' => request('page')
                        ]) }}">Название {{request('order_by') === 'name' ? request('order') === 'asc' ? '↓' : '↑' : (request('order_by') !== 'currentPrice' ? '↓' : '')}}</a>
                    <a href="{{ url()->query(
                        Request::url(),
                        [
                            'order_by' => 'currentPrice',
                            'order' => request('order') === 'asc' ? 'desc' : 'asc',
                            'page' => request('page')
                        ]) }}">Цена {{request('order_by') === 'currentPrice' ? request('order') === 'asc' ? '↓' : '↑' : ''}}</a>
                </div>
                <ul>
                    @foreach ($products as $product)
                        <li class="productItem">
                            <a href="{{ url()->query("/product/$product->id") }}">{{$product->name}}</a>
                            - ₽ {{$product->currentPrice()}}</li>
                    @endforeach
                </ul>
                {{-- TODO: сделать кастомную пагинацию с учетом сортировки --}}
                {{-- https://laravel.com/docs/12.x/pagination#customizing-the-pagination-view --}}
                {{ $products->links() }}
            </div>
        </main>
    </div>
</x-layout>