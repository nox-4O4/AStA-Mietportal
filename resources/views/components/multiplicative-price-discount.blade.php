@props(['price' => 0.0, 'multipliers' => []])

@if($multipliers)
    @php(ksort($multipliers))
    <ul {{$attributes}}>
        @foreach($multipliers as $day => $multiplier)
            @if(!$loop->first)
                @php([$prevDay, $prevMultiplier] = $prev)
                <li>
                    @if($day > $prevDay + 1)
                        <span class="days">{{$prevDay}}&ndash;{{$day-1}} Tage</span>: <span class="price">@money($prevMultiplier * $price) pro Tag</span>
                    @else
                        <span class="days">{{$prevDay}} {{$prevDay == 1 ? 'Tag' : 'Tage'}}</span>: <span class="price">@money($prevMultiplier * $price) pro Tag</span>
                    @endif
                </li>
            @endif
            @php($prev = [$day, $multiplier])
        @endforeach
        <li><span class="days">{{$day}}+ Tage</span>: <span class="price">@money($multiplier * $price) pro Tag</span></li>
    </ul>
@endif
