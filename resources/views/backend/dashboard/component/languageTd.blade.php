@foreach ($languages as $language)
    @if (session('app_locale') === $language->canonical)
        @continue
    @endif
    <th class="text-center">
        @php
            $translate = $model->languages->contains('id', $language->id)
        @endphp
        <a class="{{ ($translate) ? '' : 'text-danger' }}" href="{{ route('language.translate', ['id' => $model->id, 'languageId' => $language->id, 'model' => $modeling]) }}">{{ ($translate) ? 'Đã dịch' : 'Chưa dịch' }}</a>
    </th>            
@endforeach