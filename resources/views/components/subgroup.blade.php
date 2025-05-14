<ul>
    @foreach($subgroups as $subgroup)
        <li>
            <a href="{{ url()->query("/category/$subgroup->id",
                                [
                                    'order_by' => 'name',
                                    'order' => request('order') === 'asc' ? 'desc' : 'asc',
                                    'page' => request('page')
                                ]) }}"
                style="{{ in_array($subgroup->id, $selectedGroupsIds) ? 'color: green' : '' }}">{{$subgroup->name}} ({{ $subgroup->getProductsCount() }})</a>
            @if($subgroup->children()->count() && in_array($subgroup->id, $selectedGroupsIds))
                @include('components/subgroup', [
                    'subgroups' => $subgroup->children()->get(),
                    'selectedGroupsIds' => $selectedGroupsIds
                    ])
            @endif
        </li>
    @endforeach
</ul>