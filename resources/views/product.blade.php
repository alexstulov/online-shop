<x-layout>
    <x-slot:title>{{$product->name}}</x-slot>
    {{-- <span>Categories: {{ print_r($product->groupDrill())}}</span> --}}
    <nav area-label="breadcrumb" class="mt-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Главная</a></li>
            @foreach ($product->groupDrill() as $group)
                <li class="breadcrumb-item">
                    <a href='{{ url("/category/$group->id") }}'>{{$group->name}}</a>
                </li>
            @endforeach
        </ol>
    </nav>
    @if ($product->name)
        <h1>{{$product->name}}</h1>
    @endif
    
    <h2>Цена: ₽ {{$product->currentPrice()}}</h2>
</x-layout>