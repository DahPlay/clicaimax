@extends('auth.template.index')

@section('content')
<div class="background-login col-6 d-none d-md-flex"
    style="background-image: url('/Auth-Panel/dist/img/background-login.svg')"></div>
<div class="login-box login-page col-12 col-md-6 p-0">
    <div class="card col p-0">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card-body d-flex flex-column login-card-body p-0">
            <div class="card mb-5">
                <div class="card-body text-center">
                    @php
                        $baseUrl = config('app.url');
                        if (app()->environment('local')) {
                            $baseUrl .= ':8000';
                        }
                    @endphp

                    <div class="social-auth-links text-center mb-3">
                        <p style="color: {{ config('custom.text_color_conta') }};">
                            Voltar para
                            <a href="http://clicaimax.com.br/" style="color: {{ config('custom.text_color_cadastre') }};">Home</a>
                        </p>
                    </div>
                    <div class="d-flex justify-content-center">
                        <p>Redefinir Senha</p>
                        <i class="fa fa-arrow-down ml-2 animate__animated animate__bounce"></i>
                    </div>
                    <a href="{{ config('custom.portal_link') }}" target="_blank">
                        <img src="{{ config('custom.logo_1') }}" style="width: 140px;"
                            alt="{{ config('custom.project_name') }}">
                    </a>
                </div>
            </div>

            <h3 class="login-box-msg">Definir nova senha</h3>

            <form action="{{ route('password.update') }}" method="post">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="input-group mb-2 d-flex flex-column">
                    <label for="email"></label>
                    <input type="email" @error('email') is-invalid @enderror value="{{ $email ?? old('email') }}"
                        name="email" id="email" class="form-control w-100" placeholder="E-mail">

                    @error('email')
                        <span class="text-danger position-relative" style="top: 10px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group mb-2 d-flex flex-column">
                    <label for="password"></label>
                    <input type="password" @error('password') is-invalid @enderror name="password" id="password"
                        class="form-control w-100" placeholder="Nova senha">

                    @error('password')
                        <span class="text-danger position-relative" style="top: 10px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group mb-2 d-flex flex-column">
                    <label for="password_confirmation"></label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="form-control w-100" placeholder="Confirme a senha">
                </div>

                <div class="col-12 d-flex align-items-center justify-content-center">
                    <button type="submit" class="acess-button">Redefinir senha</button>
                </div>
            </form>
        </div>
    </div>
    <div class="login-logo position-absolute">
        <a href="http://clicaimax.com.br/">
            <img src="{{ asset('Auth-Panel/dist/img/logo.svg') }}" alt="">
        </a>
    </div>
</div>
@endsection
