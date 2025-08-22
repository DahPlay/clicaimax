@extends('auth.template.index')

@section('css')
    <link rel="stylesheet" href="{{ asset('Auth-Panel/dist/css/front/front.css') }}">
@endsection

<style>
    .title-input2 {
        color:
            {{ config('custom.text_color_form') }}
        ;
        font-weight: 500;
    }

    .subtitle-register2 {
        font-weight: 700;
        color:
            {{ config('custom.text_color_form') }}
        ;
        margin-bottom: 50px !important;
        text-align: center;
    }
</style>

@section('content')
    <div class="register-box flex-column">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card-register m-auto"
            style="background-color: {{ config('custom.background_form') }}; color: {{ config('custom.text_color_recuperar') }};">
            <div class="card-body-register login-card-body">
                <div class="login-logo">
                    <a href="{{ route('login') }}">
                        <img src="{{ config('custom.logo_1') }}" alt="">
                    </a>
                </div>

                <p class="subtitle-register2">Crie sua conta e aproveite todo nosso conteúdo!</p>

                <form action="{{ route('register') }}" method="post">
                    @csrf

                    <input type="hidden" name="source" id="source" class="form-control" required
                        value="{{ old('source', session('customerData')['source'] ?? '') }}" {{ isset(session('customerData')['source']) ? 'readonly' : '' }}>

                    <div class="input-group mb-3" style="color: {{ config('custom.text_color_recuperar') }};">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="title-input2 mb-0" for="plan">Planos *</label>
                            <button type="button" class="btn btn-primary btn-view title-input2" data-toggle="modal"
                                data-target="#modalPlanos">Ver
                                planos
                            </button>
                        </div>



                        <select id="plan_id" class="form-control" name="plan_id" required>
                            <option value="">Selecione...</option>
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}" data-telemedicine="{{ $plan->is_active_telemedicine }}"
                                    @selected($plan->id == $planId)>
                                    {{ $plan->name . ' - ' . number_format($plan->value, 2, ',', '.') }}
                                </option>
                            @endforeach
                        </select>



                        <div class="form-group mt-3">
                            <label for="coupon">Cupom de Desconto</label>
                            <div class="d-flex gap-2">
                                <input type="text" id="coupon" name="coupon" class="form-control"
                                    placeholder="Digite seu cupom">
                                <button type="button" id="applyCoupon" class="btn btn-primary">Aplicar</button>
                            </div>
                            <small id="couponFeedback" class="form-text text-danger"></small>
                        </div>

                    </div>

                    <div class="input-group mb-3">
                        <label class="title-input2" for="usuario">Digite seu usuário *</label>
                        <input type="text" name="login" id="usuario" class="form-control" placeholder="Usuário *" required
                            value="{{ old('login', session('customerData')['login'] ?? '') }}">
                    </div>

                    @error('login')
                        <span class="text-danger">{{ $message }}</span>
                        <hr>
                    @enderror

                    <div class="input-group mb-3">
                        <label class="title-input2" for="name">Qual seu nome completo *</label>
                        <input type="text" name="name" id="name" class="form-control"
                            placeholder="Digite seu nome completo *" required
                            value="{{ old('name', session('customerData')['name'] ?? '') }}">
                    </div>

                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        <hr>
                    @enderror

                    <div class="input-group mb-3">
                        <label class="title-input2" for="document">CPF *</label>
                        <input type="text" @error('document') has-error @enderror value="{{ old('document') ?? '' }}"
                            name="document" id="document" class="form-control" placeholder="Digite seu cpf *" required>
                    </div>
                    <div id="dependentes-fields" style="display:none;">
                        <div class="input-group mb-3">
                            <label class="title-input2" for="cpf_dependente_1">CPF Dependente 1</label>
                            <input type="text" name="cpf_dependente_1" id="cpf_dependente_1" class="form-control"
                                placeholder="Digite o CPF do seu 1° Dependente">
                        </div>
                        <div class="input-group mb-3">
                            <label class="title-input2" for="cpf_dependente_2">CPF Dependente 2</label>
                            <input type="text" name="cpf_dependente_2" id="cpf_dependente_2" class="form-control"
                                placeholder="Digite o CPF do seu 2° Dependente">
                        </div>
                        <div class="input-group mb-3">
                            <label class="title-input2" for="cpf_dependente_3">CPF Dependente 3</label>
                            <input type="text" name="cpf_dependente_3" id="cpf_dependente_3" class="form-control"
                                placeholder="Digite o CPF do seu 3° Dependente">
                        </div>
                    </div>


                    @error('document')
                        <span class="text-danger">{{ $message }}</span>
                        <hr>
                    @enderror

                    <div class="input-group mb-3">
                        <label class="title-input2" for="mobile">Digite seu número *</label>
                        <input type="text" @error('mobile') has-error @enderror value="{{ old('mobile') ?? '' }}"
                            name="mobile" id="mobile" class="form-control" placeholder="(00) 00000-0000" required>
                    </div>

                    @error('mobile')
                        <span class="text-danger">{{ $message }}</span>
                        <hr>
                    @enderror

                    <div class="input-group mb-3">
                        <label class="title-input2" for="email">Digite seu email *</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="meuemail@mail.com"
                            required value="{{ old('email', session('customerData')['email'] ?? '') }}">
                    </div>

                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        <hr>
                    @enderror

                    @if (
                            !session()->has('customerData') ||
                            (session()->has('customerData') && session('customerData')['source'] !== 'temporarily')
                        )
                        <div class="input-group mb-3">
                            <label class="title-input2" for="password">Crie sua senha *</label>
                            <input type="password" @error('password') has-error @enderror
                                value="{{ session()->has('authenticate') ? session('customerData')['password'] : '' }}"
                                name="password" id="password" class="form-control" placeholder="Crie uma senha forte" required
                                {{ session()->has('authenticate') ? 'readonly' : '' }}>
                            {{-- <div class="tips-password">
                                <p>Use no <strong>mínimo 6 caracteres</strong></p>
                                <p>* 1 letra maiúscula</p>
                                <p>* 1 número</p>
                                <p>* 1 carácter especial ex: @#$</p>
                            </div> --}}
                        </div>

                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                            <hr>
                        @enderror

                        <div class="input-group mb-3">
                            <label class="title-input2" for="password_confirmation">Confirmação de senha *</label>
                            <input type="password" @error('password_confirmation') has-error @enderror
                                value="{{ old('password_confirmation') ?? '' }}" name="password_confirmation"
                                id="password_confirmation" class="form-control" placeholder="Repita sua senha" required>
                        </div>

                        @error('password_confirmation')
                            <span class="text-danger">{{ $message }}</span>
                            <hr>
                        @enderror
                    @endif

                    {{-- <div class="input-group row-input">
                        <input type="checkbox" id="confirm-terms" required>
                        <span><strong>Aceito</strong> receber novidades, ofertas especiais e outras informações do Agro
                            Mercado.</span>
                    </div> --}}

                    {{-- <div class="">
                        <div class="col-12 info-form">
                            <p class="p-2">O Agro
                                Mercado usará seus dados para oferecer e dar suporte aos serviços prestados e para e
                                enviar informações relacionadas ao serviço. Podemos usar esses dados conforme explica a
                                nossa Política de Privacidade. Ao clicar em "continuar”você confirma estar ciente da nossa
                                política e dos termos específicos aplicáveis ao Brasil.</p>
                        </div>
                    </div> --}}

                    <div class="row">
                        <div class="col-9 m-auto d-flex justify-content-center">
                            <button type="submit" class="acess-button register"
                                style="background-color: {{ config('custom.button_color_entrar') }};">Continuar
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-9 m-auto d-flex justify-content-center">
                            {{-- <a href="{{ route('password.email') }}" class="btn btn-block btn-primary">
                                <i class="fa fa-lock mr-2"></i> Esqueci minha senha
                            </a> --}}
                            <a href="{{ route('login') }}" class="btn btn-block have-account acess-button"
                                style="background-color: {{ config('custom.button_color_entrar') }};">
                                <i class="fa fa-user-plus mr-2"></i> Já tenho conta.
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <footer class="section-container d-flex flex-column align-items-center footer-register"
        style="background-color: {{ config('custom.background_baseboard') }};">
        <p>{{ config('custom.text_baseboard') }}</p>

        <div
            class="d-flex align-items-center justify-content-center w-100 position-relative container-media flex-column flex-sm-row">
            <div class="social-media d-flex justify-content-center">
                <div class="container-social-media"
                    style="background-color: {{ config('custom.background_social_media') }};">
                    <a href="{{ config('custom.link_social_media_1') }}"><img
                            src="{{ config('custom.image_social_media_1') }}" alt=""></a>
                </div>
                <div class="container-social-media"
                    style="background-color: {{ config('custom.background_social_media') }};">
                    <a href="{{ config('custom.link_social_media_2') }}"><img
                            src="{{ config('custom.image_social_media_2') }}" alt=""></a>
                </div>
                <div class="container-social-media"
                    style="background-color: {{ config('custom.background_social_media') }};">
                    <a href="{{ config('custom.link_social_media_3') }}"><img
                            src="{{ config('custom.image_social_media_3') }}" alt=""></a>
                </div>
            </div>
            <img class="logo-footer" src="{{ config('custom.logo_baseboard') }}" alt="">
        </div>
        <p class="copyright-footer">{{ config('custom.text_copy') }}</p>
    </footer>

    <div class="modal fade" id="modalPlanos" tabindex="-1" aria-labelledby="modalLabelPlanos" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabelPlanos">Planos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- <section id="planos" class="sixth-section d-flex flex-column align-items-center">
                        <h3 class="text-center">Escolha o plano que mais combina com você!</h3>
                        <p class="subtitle-plans text-center">Estamos desenvolvendo uma <span class="sub">comunicação
                                clara</span>
                            e próxima de você!</p>

                        <div class="d-flex flex-column flex-lg-row align-items-center container-plans position-relative">
                            @foreach ($plans as $plan)
                            <a href="{{ route('register', ['planId' => $plan->id]) }}">
                                <div
                                    class="plan d-flex flex-column align-items-center {{ $plan->is_best_seller ? 'best-seller' : '' }}">
                                    @if ($plan->is_best_seller)
                                    <div class="box-best-seller position-absolute">
                                        <span>Mais vendido</span>
                                    </div>
                                    @endif

                                    <div class="important-info-plan d-flex flex-column align-items-center">
                                        <span class="title-plan">{{ $plan->name }}</span>
                                        <span class="value-plan">R$ <span class="value">{{ number_format($plan->value, 2,
                                                ',', '.') }}</span></span>
                                        <span style="color: black;">{{ $plan->free_for_days > 0 ? $plan->description :
                                            'Renovação Automática' }}</span>
                                    </div>

                                    <div class="about-plan d-flex flex-column align-items-center">
                                        @foreach ($plan->benefits as $benefit)
                                        <div class="about-plan-item d-flex">
                                            <img src="{{ asset('Auth-Panel/dist/img/plans-icon.svg') }}" alt="">
                                            <span style="color: black;">{{ $benefit->description }}</span>
                                        </div>
                                        @endforeach
                                    </div>

                                    <button type="button" class="btn start-now-button">
                                        Começar agora
                                    </button>
                                </div>
                            </a>
                            @endforeach
                        </div>

                        <p class="last-info-plans text-center my-5">Curta nossas <strong>séries</strong>,
                            <strong>filmes</strong> e
                            </strong>conteúdos exclusivos</strong> feitos para você!
                        </p>
                    </section>--}}
                    @include('site.partials.plan-section')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascriptLocal')
    <script>
        $(function () {
            initSelects2();
            initMasks();

            const $dependentesFields = $('#dependentes-fields');

            $('#plan_id').on('change', function () {
                const planId = $(this).val();
                const telemedicine = $(this).find(':selected').data('telemedicine');

                // console.log('Plano selecionado ID:', planId);
                // console.log('Is active telemedicine:', telemedicine);

                if (telemedicine == 1) {
                    $dependentesFields.show();
                } else {
                    $dependentesFields.hide();
                    // opcional: limpar os campos ao esconder
                    $dependentesFields.find('input').val('');
                }
            });

            // Se o select já vier com valor selecionado ao carregar a página
            const initialTelemedicine = $('#plan_id').find(':selected').data('telemedicine');
            if (initialTelemedicine == 1) {
                $dependentesFields.show();
            } else {
                $dependentesFields.hide();
            }
        });


        function initSelects2() {
            $('#plan_id').select2({
                theme: "bootstrap4",
                allowClear: true,
            });
        }

        function initMasks() {
            $('#document').mask('000.000.000-00');
            $('#cpf_dependente_1').mask('000.000.000-00');
            $('#cpf_dependente_2').mask('000.000.000-00');
            $('#cpf_dependente_3').mask('000.000.000-00');
            $('#mobile').mask('(00) 00000-0000');
        }

        document.getElementById('applyCoupon').addEventListener('click', function () {
            const coupon = document.getElementById('coupon').value;
            const planId = document.querySelector('select[name="plan_id"]').value;

            if (!coupon || !planId) {
                document.getElementById('couponFeedback').innerText = 'Selecione um plano e insira um cupom.';
                return;
            }

            fetch('/validate-coupon', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ coupon: coupon, plan_id: planId }),
            })
                .then(response => response.json())
                .then(data => {
                    const feedback = document.getElementById('couponFeedback');
                    if (data.valid) {
                        feedback.innerText = data.message;
                        feedback.classList.remove('text-danger');
                        feedback.classList.add('text-success');

                        // Atualiza o valor do plano no HTML (no select)
                        const selectedOption = document.querySelector(`select[name="plan_id"] option[value="${planId}"]`);
                        console.log(selectedOption)
                        if (selectedOption) {
                            selectedOption.innerText = `${selectedOption.innerText.split(' - ')[0]} - R$ ${data.discounted_value}`;
                        }

                    } else {
                        feedback.innerText = data.message;
                        feedback.classList.remove('text-success');
                        feedback.classList.add('text-danger');
                    }
                });
        });


    </script>
@endsection