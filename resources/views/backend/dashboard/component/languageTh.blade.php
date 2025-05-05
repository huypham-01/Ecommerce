@foreach ($languages as $language)
            @if (session('app_locale') === $language->canonical)
                @continue
            @endif
            <th class="text-center">
                <span><img class="image img-scaledown language-flag" src="{{ $language->image }}" alt=""></span>
            </th>            
@endforeach