@extends('layouts.app')

@section('content')
    {{-- componente vue criado para renderizar tela de login --}}
    {{-- foi substituido o conteudo que foi construido pelo Laravel ui e criado este componente vue somente para fins didáticos --}}
    <login-component token_csrf={{@csrf_token()}}></login-component>
@endsection
