@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ config('app.name') }}
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
<br>
<a href="{{ url('/produk') }}">Produk</a> | <a href="{{ url('/akun') }}">Akun</a> | <a href="{{ url('/akun/subscribtion') }}">Ubsubscribe</a>
@endcomponent
@endslot
@endcomponent
