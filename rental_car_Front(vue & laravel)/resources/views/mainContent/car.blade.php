@extends('layouts/app')

@section('content')
    {{-- componente  carro vue envolvido em um suspense--}}
    <Suspense>
        <template #default>
            <car-component></car-component>
        </template>

        <template #fallback>
            <loading-component></loading-component>
        </template>
    </Suspense>
@endsection
