@extends('auth.template.index')

@section('content')
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            /* Evita qualquer rolagem indesejada na página */
            background-color:
                {{ config('custom.background_login_color') }}
            ;
            /* Fundo da página com a cor de login */
        }

        .container-login {
            display: flex;
            height: 100vh;
            /* Ocupa 100% da altura da viewport */
            width: 100vw;
            overflow: hidden;
            /* Impede rolagem no container principal */
        }

        /* Imagem da esquerda fixa no PC */
        .background-login {
            flex: 0 0 50vw;
            /* Largura fixa em 50% da viewport */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            position: relative;
        }

        /* Coluna da direita que rola e expande no zoom */
        .login-box {
            flex: 1;
            /* Ocupa todo o espaço restante */
            display: flex;
            flex-direction: column;
            background-color:
                {{ config('custom.background_login_color') }}
            ;
            overflow-y: auto;
            /* Permite a rolagem vertical somente nesta coluna */
            -webkit-overflow-scrolling: touch;
        }

        .login-content {
            flex: 1;
            /* Permite que o conteúdo se expanda e ocupe o espaço */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
        }

        /* Ajuste do rodapé */
        .login-footer {
            text-align: right;
            padding: 20px;
            flex-shrink: 0;
            margin-top: auto;
            /* Empurra o rodapé para o final do contêiner */
        }

        .login-footer img {
            max-width: 140px;
            height: auto;
        }

        /* Estilos para o cartão e inputs */
        .card,
        .input-group,
        .acess-button,
        .password-button {
            width: 100%;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .acess-button,
        .password-button {
            margin-top: 10px;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        /* Estilos para o mobile */
        @media (max-width: 768px) {
            .container-login {
                flex-direction: column;
                height: auto;
                /* Permite a expansão em altura no mobile */
                overflow: auto;
                /* Permite rolagem total no mobile */
            }

            body,
            html {
                overflow: auto;
                /* Permite rolagem total no mobile */
            }

            .background-login {
                display: none !important;
            }

            .login-box {
                width: 100vw;
                min-height: 100vh;
                padding-top: 0;
            }

            .login-content {
                justify-content: flex-start;
                padding-top: 20px;
                min-height: auto;
            }

            .login-footer {
                text-align: center;
                padding-top: 0;
                padding-bottom: 20px;
            }
        }
    </style>

    <div class="container-login" style="margin-top:-5%;">
        {{-- Coluna fixa da esquerda para PC --}}
        <div class="background-login d-none d-md-flex"
            style="background-image: url('/Auth-Panel/dist/img/{{ config('custom.background_login_image') }}')">
        </div>

        {{-- Coluna que rola com o formulário e o logo --}}
        <div class="login-box">
            <div class="login-content">
                {{-- Conteúdo do formulário e outros elementos --}}
                @if (count($errors) > 0)
                    <script>
                        $(document).Toasts('create', {
                            class: 'bg-danger',
                            title: 'Atenção ao(s) seguinte(s) erro(s):',
                            position: 'topRight',
                            body: [
                                @foreach ($errors->all() as $error)
                                    "<li>{{ $error }}</li>",
                                @endforeach
                                    ]
                        })
                    </script>
                @endif
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
                                <a href="{{ $baseUrl }}" style="color: {{ config('custom.text_color_cadastre') }};">Home</a>
                            </p>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            <p style=" color: {{ config('custom.text_color_acessar') }};">
                                Acessar {{ config('custom.project_name') }}
                            </p>
                            <i class="fa fa-arrow-down ml-2 animate__animated animate__bounce"
                                style=" color: {{ config('custom.text_color_acessar') }};"></i>
                        </div>
                        <a href="http://clicaimax.com.br/" target="_blank">
                            <img src="{{ config('custom.logo_1') }}" style="width: 140px;"
                                alt="{{ config('custom.project_name') }}">
                        </a>
                    </div>
                </div>
                <h3 class="login-box-msg" style=" color: {{ config('custom.text_color_gerenciar') }}; margin-top: -20%;">Gerenciar Conta</h3>
                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <div class="input-group mb-2 d-flex flex-column">
                        <input type="text" name="login" id="login" class="form-control w-100" placeholder="Usuário"
                            value="{{ old('login') ?? '' }}">
                        @error('login')
                            <span class="text-danger position-relative" style="top: 10px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-group mb-2 d-flex flex-column">
                        <input type="password" name="password" id="password" class="form-control w-100" placeholder="Senha"
                            style="min-height: 40px">
                        @error('password')
                            <span class="text-danger position-relative" style="top: 10px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <div class="col-12 d-flex align-items-center justify-content-center">
                            <button type="submit" class="acess-button"
                                style="background-color: {{ config('custom.button_color_entrar') }}; color: {{ config('custom.button_text_color_entrar') }};">Entrar</button>
                        </div>
                        <div class="col-12 d-flex align-items-center justify-content-center">
                            <a href="{{ route('password.request') }}" class="password-button text-center border text-black"
                                style="background-color: {{ config('custom.button_color_senha') }}; color: {{ config('custom.button_text_color_senha') }}; border: {{ config('custom.button_color_senha') }}!important;">
                                <i class="fab fa-lock mr-2"></i> Esqueci minha senha
                            </a>
                        </div>
                    </div>
                    <div>
                        <div class="col-12">
                            <div class="social-auth-links text-center mb-3">
                                <p style=" color: {{ config('custom.text_color_conta') }};">Não tem uma conta?
                                    <a href="{{ config('app.url') . '#planos' }}"
                                        style=" color: {{ config('custom.text_color_cadastre') }};">Cadastre-se</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="login-footer">
                    <a href="http://clicaimax.com.br/">
                        <img src="{{ config('custom.logo_2') }}" alt="">
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection
