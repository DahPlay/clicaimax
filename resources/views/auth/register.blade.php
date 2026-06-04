@extends('auth.template.index')

@section('css')
    <link rel="stylesheet" href="{{ asset('Auth-Panel/dist/css/front/front.css') }}">
@endsection

<style>
    .title-input2 {
        color: {{ config('custom.text_color_form') }};
        font-weight: 500;
    }

    .subtitle-register2 {
        font-weight: 700;
        color: {{ config('custom.text_color_form') }};
        margin-bottom: 50px !important;
        text-align: center;
    }

    /* New step-by-step styles */
    .step-progress {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        position: relative;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .step-progress:before {
        content: '';
        position: absolute;
        top: 15px;
        left: 0;
        right: 0;
        height: 2px;
        background: #e0e0e0;
        z-index: 1;
    }

    .step-progress-bar {
        position: absolute;
        top: 15px;
        left: 0;
        height: 2px;
        background: {{ config('custom.button_color_entrar') }};
        z-index: 2;
        transition: width 0.3s ease;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 3;
        flex: 1;
    }

    .step-number {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .step.active .step-number {
        background: {{ config('custom.button_color_entrar') }};
    }

    .step-label {
        font-size: 12px;
        color: #999;
        text-align: center;
    }

    .step.active .step-label {
        color: {{ config('custom.text_color_form') }};
        font-weight: bold;
    }

    .step-content {
        display: none;

    }

    .step-content.active {
        display: block;
    }

    .navigation-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
        gap: 15px;
    }

    .btn-nav {
        padding: 10px 25px;
        border-radius: 5px;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-back {
        background: transparent;
        color: {{ config('custom.text_color_form') }};
        border: 1px solid #ddd;
    }

    .btn-back:hover {
        background: #f5f5f5;
    }

    .btn-next,
    .btn-submit {
        background: {{ config('custom.button_color_entrar') }};
        color: white;
        margin-left: auto;
    }

    .btn-next:hover,
    .btn-submit:hover {
        opacity: 0.9;
    }

    .btn-submit {
        padding: 12px 25px;
        font-weight: 600;
    }

    .footer-links {
        margin-top: 30px;
        text-align: center;
    }

    .footer-links a {
        display: block;
        margin-bottom: 10px;
        color: {{ config('custom.text_color_form') }};
        text-decoration: none;
    }

    .footer-links a:hover {
        text-decoration: underline;
    }

    /* Plan modal adjustments */
    .plan-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        position: relative;
    }

    .best-seller-badge {
        position: absolute;
        top: -10px;
        right: 20px;
        background: #ff5722;
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
    }

    .plan-price {
        font-size: 24px;
        font-weight: bold;
        margin: 10px 0;
    }

    .plan-features {
        margin: 15px 0;
    }

    .plan-features li {
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }

    .plan-features li:before {
        content: "✓";
        margin-right: 8px;
        color: {{ config('custom.button_color_entrar') }};
    }

    /* Dependents step UX */
    .dependents-intro {
        text-align: center;
        margin-bottom: 20px;
    }

    .dependents-intro .title-input2 {
        font-size: 16px;
        margin-bottom: 6px;
        display: block;
    }

    .dependents-intro small {
        opacity: .75;
    }

    .dependents-count-selector {
        display: flex;
        gap: 12px;
        margin-bottom: 25px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .dependent-count-card {
        flex: 1 1 90px;
        max-width: 130px;
        padding: 18px 10px;
        border: 2px solid rgba(255, 255, 255, 0.18);
        border-radius: 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: rgba(255, 255, 255, 0.04);
        color: {{ config('custom.text_color_form') }};
        user-select: none;
    }

    .dependent-count-card:hover {
        border-color: {{ config('custom.button_color_entrar') }};
        transform: translateY(-2px);
    }

    .dependent-count-card.active {
        border-color: {{ config('custom.button_color_entrar') }};
        background: {{ config('custom.button_color_entrar') }};
        color: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.18);
    }

    .dependent-count-number {
        font-size: 30px;
        font-weight: 700;
        line-height: 1;
    }

    .dependent-count-label {
        font-size: 12px;
        margin-top: 6px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .dependent-card {
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 18px;
        background: rgba(255, 255, 255, 0.03);
        animation: fadeInDep .25s ease;
    }

    @keyframes fadeInDep {
        from { opacity: 0; transform: translateY(-4px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .dependent-card-header {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .dependent-card-icon {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: {{ config('custom.button_color_entrar') }};
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        margin-right: 12px;
    }

    .dependent-card-title {
        font-weight: 600;
        margin: 0;
        color: {{ config('custom.text_color_form') }};
    }

    .dependent-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    @media (max-width: 576px) {
        .dependent-row {
            grid-template-columns: 1fr;
        }
    }

    .gender-options {
        display: flex;
        gap: 8px;
        width: 100%;
    }

    .gender-option {
        flex: 1;
        padding: 9px 4px;
        border: 1.5px solid rgba(255, 255, 255, 0.18);
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: transparent;
        color: {{ config('custom.text_color_form') }};
        font-size: 13px;
        font-weight: 500;
    }

    .gender-option:hover {
        border-color: {{ config('custom.button_color_entrar') }};
    }

    .gender-option.active {
        border-color: {{ config('custom.button_color_entrar') }};
        background: {{ config('custom.button_color_entrar') }};
        color: #fff;
    }

    .dependents-empty {
        text-align: center;
        padding: 30px 15px;
        opacity: .65;
        font-size: 14px;
        border: 1px dashed rgba(255, 255, 255, 0.18);
        border-radius: 10px;
        margin-bottom: 18px;
    }

    .step[data-step="3"].step-hidden {
        display: none;
    }

    /* === Container do registro: ampliar pra caber 3 cards (Bloco 1) === */
    .register-box { max-width: 1100px !important; margin: 0 auto !important; }
    .card-register { max-width: 1100px !important; }
    .card-body-register { max-width: 100% !important; padding: 32px 28px !important; }
    @media (max-width: 1100px) {
        .register-box, .card-register { max-width: 96vw !important; }
    }
    @media (max-width: 575.98px) {
        .card-body-register { padding: 24px 16px !important; }
    }

    /* Steps 2/3/4/5 voltam pra largura confortável de formulário */
    .step-content .step-form-inner {
        max-width: 520px;
        margin: 0 auto;
    }

    /* === Trilho de planos (Step 1) === */
    .reg-plan-section {
        position: relative;
        margin-bottom: 18px;
    }

    .reg-plan-track {
        display: flex;
        flex-wrap: nowrap;
        gap: 14px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        justify-content: safe center;
        padding: 6px 4px 14px;
        scrollbar-width: thin;
    }

    .reg-plan-track::-webkit-scrollbar { height: 6px; }
    .reg-plan-track::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,.25);
        border-radius: 4px;
    }

    .reg-plan-card {
        flex: 0 0 200px;
        min-width: 200px;
        scroll-snap-align: center;
        border: 2px solid rgba(255,255,255,.18);
        border-radius: 14px;
        background: rgba(255,255,255,.04);
        padding: 18px 14px;
        text-align: center;
        cursor: pointer;
        transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;
        position: relative;
        color: {{ config('custom.text_color_form') }};
    }

    .reg-plan-card:hover {
        border-color: {{ config('custom.button_color_entrar') }};
        transform: translateY(-3px);
    }

    .reg-plan-card.selected {
        border-color: {{ config('custom.button_color_entrar') }};
        background: rgba(255,255,255,.08);
        box-shadow: 0 6px 18px rgba(0,0,0,.18);
    }

    .reg-plan-card .reg-plan-badge {
        position: absolute;
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
        background: {{ config('custom.button_color_entrar') }};
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        letter-spacing: .3px;
    }

    .reg-plan-card .reg-plan-name {
        font-weight: 700;
        font-size: 15px;
        margin-bottom: 8px;
        min-height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .reg-plan-card .reg-plan-price {
        font-size: 22px;
        font-weight: 800;
        color: {{ config('custom.button_color_entrar') }};
        line-height: 1.1;
    }

    .reg-plan-card .reg-plan-price small {
        display: block;
        font-size: 11px;
        font-weight: 500;
        color: {{ config('custom.text_color_form') }};
        opacity: .75;
        margin-top: 2px;
    }

    .reg-plan-card .reg-plan-features {
        list-style: none;
        padding: 0;
        margin: 12px 0 14px;
        font-size: 12px;
        text-align: left;
    }

    .reg-plan-card .reg-plan-features li {
        padding: 3px 0;
        display: flex;
        align-items: flex-start;
        gap: 6px;
    }

    .reg-plan-card .reg-plan-features li i {
        color: {{ config('custom.button_color_entrar') }};
        margin-top: 3px;
        font-size: 10px;
    }

    .reg-plan-card .reg-plan-pick {
        display: inline-block;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        padding: 6px 14px;
        border-radius: 20px;
        border: 1.5px solid {{ config('custom.button_color_entrar') }};
        color: {{ config('custom.button_color_entrar') }};
        background: transparent;
        transition: background .18s ease, color .18s ease;
    }

    .reg-plan-card.selected .reg-plan-pick {
        background: {{ config('custom.button_color_entrar') }};
        color: #fff;
    }

    .reg-plan-prev,
    .reg-plan-next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        background: {{ config('custom.button_color_entrar') }};
        color: #fff;
        font-size: 16px;
        z-index: 4;
        cursor: pointer;
        display: none;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,.2);
    }

    .reg-plan-prev { left: -10px; }
    .reg-plan-next { right: -10px; }

    .reg-plan-prev.show,
    .reg-plan-next.show { display: flex; }

    @media (max-width: 575.98px) {
        .reg-plan-card { flex: 0 0 86vw; min-width: 86vw; }
    }

    /* === Help text inline (hint embaixo do input) === */
    .field-hint { font-size: 12px; color: rgba(255,255,255,.7); margin-top: 4px; }

    /* === Olho da senha === */
    .input-eye-wrapper { position: relative; width: 100%; }
    .input-eye-wrapper input { padding-right: 38px !important; }
    .input-eye {
        position: absolute; right: 8px; top: 50%; transform: translateY(-50%);
        background: transparent; border: 0; color: #6c757d; cursor: pointer; padding: 4px;
    }
    .input-eye:focus { outline: none; }

    /* === Alerta âmbar inline por step === */
    .step-alert {
        background: rgba(255, 193, 7, .14);
        border: 1px solid rgba(255, 193, 7, .55);
        color: #ffd54f;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 13px;
        margin-top: 14px;
        display: none;
        text-align: center;
    }
    .step-alert.show { display: block; }
    .step-alert .step-alert-icon { color: #ffc107; margin-right: 6px; }
    .step-alert strong { color: #fff; }

    /* === Erros inline por campo === */
    .field-error { color: #ff8a80; font-size: 12px; margin-top: 4px; min-height: 16px; }

    /* === Step 5: pills de método de pagamento === */
    .pay-method-pills {
        display: flex; gap: 10px; flex-wrap: wrap; justify-content: center; margin-bottom: 18px;
    }
    .pay-method-pill {
        background: transparent;
        border: 1.5px solid {{ config('custom.button_color_entrar') }};
        color: {{ config('custom.text_color_form') }};
        padding: 8px 18px; border-radius: 999px; cursor: pointer;
        font-weight: 600; font-size: 13px;
    }
    .pay-method-pill.active {
        background: {{ config('custom.button_color_entrar') }}; color: #fff;
    }

    /* === Step 5: linha MM/AAAA/CVV inline === */
    .pay-card-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 10px;
    }
    @media (max-width: 480px) {
        .pay-card-row { grid-template-columns: 1fr 1fr; }
    }
    .pay-https-note { font-size: 12px; opacity: .75; text-align: center; margin-top: 6px; margin-bottom: 16px; }
    .pay-method-info {
        background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.12);
        border-radius: 10px; padding: 16px; text-align: center; font-size: 14px; line-height: 1.6;
    }
    .pay-method-info strong { display: block; margin-bottom: 6px; font-size: 15px; }

    /* Header de seção dentro de step (ex: "Forma de pagamento") */
    .step-section-title {
        font-size: 22px; font-weight: 700; margin-bottom: 18px;
        color: {{ config('custom.text_color_form') }};
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
            style="background-color: {{ config('custom.background_form') }};
            color: {{ config('custom.text_color_recuperar') }};
            border: 4px solid {{ config('custom.background_button_next_prev') }}; color:{{ config('custom.text_color_button_next_prev') }};">

            <div class="card-body-register login-card-body">
                <div class="login-logo">
                    <a href="http://clicaimax.com.br/">
                        <img src="{{ config('custom.logo_1') }}" alt="">
                    </a>
                </div>

                <p class="subtitle-register2">Crie sua conta e aproveite todo nosso conteúdo!</p>

                <!-- Step Progress -->
                <div class="step-progress">
                    <div class="step-progress-bar" style="width: 0%;"></div>
                    <div class="step active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-label">Plano</div>
                    </div>
                    <div class="step" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-label">Dados Pessoais</div>
                    </div>
                    <div class="step step-hidden" data-step="3">
                        <div class="step-number">3</div>
                        <div class="step-label">Dependentes</div>
                    </div>
                    <div class="step" data-step="4">
                        <div class="step-number">4</div>
                        <div class="step-label">Credenciais</div>
                    </div>
                    <div class="step" data-step="5">
                        <div class="step-number">5</div>
                        <div class="step-label">Pagamento</div>
                    </div>
                </div>

                <form action="{{ route('register') }}" method="post" id="registerForm">
                    @csrf

                    <input type="hidden" name="source" id="source" class="form-control" required
                        value="{{ old('source', session('customerData')['source'] ?? '') }}"
                        {{ isset(session('customerData')['source']) ? 'readonly' : '' }}>

                    <!-- Step 1: Plan Selection (3 cards lado a lado) -->
                    <div class="step-content active" data-step-content="1">
                        <label class="title-input2 d-block text-center mb-3">Escolha seu plano *</label>

                        <div class="reg-plan-section">
                            <button type="button" class="reg-plan-prev" aria-label="Anterior"><i class="fa fa-chevron-left"></i></button>
                            <button type="button" class="reg-plan-next" aria-label="Próximo"><i class="fa fa-chevron-right"></i></button>

                            <div class="reg-plan-track" id="regPlanTrack">
                                @foreach ($plans as $plan)
                                    @php
                                        // Suporta `allowed_billing_types` (T7) como array ou CSV;
                                        // fallback para o `billing_type` singular legado.
                                        $abt = $plan->allowed_billing_types ?? null;
                                        if (is_array($abt))   $abtCsv = implode(',', $abt);
                                        elseif (is_string($abt) && $abt !== '') $abtCsv = $abt;
                                        else                  $abtCsv = (string) ($plan->billing_type ?? 'CREDIT_CARD');
                                    @endphp
                                    <div class="reg-plan-card {{ $plan->id == $planId ? 'selected' : '' }}"
                                         data-plan-id="{{ $plan->id }}"
                                         data-telemedicine="{{ $plan->is_active_telemedicine }}"
                                         data-base-value="{{ $plan->value }}"
                                         data-allowed-billing-types="{{ $abtCsv }}">
                                        @if ($plan->is_best_seller)
                                            <span class="reg-plan-badge">Mais vendido</span>
                                        @endif
                                        <div class="reg-plan-name">{{ $plan->name }}</div>
                                        <div class="reg-plan-price">
                                            <span class="reg-plan-price-value">R$ {{ number_format($plan->value, 2, ',', '.') }}</span>
                                            <small>{{ ucfirst(strtolower($plan->cycle ?? 'mensal')) }}</small>
                                        </div>
                                        <ul class="reg-plan-features">
                                            @foreach ($plan->benefits->take(4) as $benefit)
                                                <li><i class="fa fa-check"></i><span>{{ $benefit->description }}</span></li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="reg-plan-pick">Selecionar</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <input type="hidden" name="plan_id" id="plan_id" value="{{ $planId ?? '' }}" required>

                        <div class="step-form-inner">
                            <div class="form-group mt-3">
                                <label for="coupon" style="color:{{ config('custom.text_color_form') }}">Cupom de
                                    Desconto</label>
                                <div class="d-flex gap-2">
                                    <input type="text" id="coupon" name="coupon" class="form-control"
                                        placeholder="Digite seu cupom">
                                    <button type="button" id="applyCoupon" class="btn btn-primary">Aplicar</button>
                                </div>
                                <small id="couponFeedback" class="form-text text-danger"></small>
                            </div>

                            <div class="navigation-buttons">
                                <button type="button" class="btn btn-nav btn-next"
                                    style="background-color:{{ config('custom.background_button_next_prev') }}; color:{{ config('custom.text_color_button_next_prev') }};">Próximo</button>
                            </div>

                            <div class="step-alert" data-step-alert="1">
                                <i class="fa fa-exclamation-triangle step-alert-icon"></i>
                                <span>Para continuar, preencha: <strong class="step-alert-fields"></strong>.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Personal Info -->
                    <div class="step-content" data-step-content="2">
                        <div class="step-form-inner">
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

                        <div class="input-group mb-1">
                            <label class="title-input2" for="document">CPF *</label>
                            <input type="text" @error('document') has-error @enderror
                                value="{{ old('document') ?? '' }}" name="document" id="document" class="form-control"
                                placeholder="Digite seu cpf *" required>
                        </div>
                        <div class="field-error mb-3" data-error-for="document"></div>

                        @error('document')
                            <span class="text-danger">{{ $message }}</span>
                            <hr>
                        @enderror

                        <div class="input-group mb-3">
                            <label class="title-input2" for="mobile">Digite seu Celular *</label>
                            <input type="text" @error('mobile') has-error @enderror value="{{ old('mobile') ?? '' }}"
                                name="mobile" id="mobile" class="form-control" placeholder="(00) 00000-0000"
                                required>
                        </div>

                        @error('mobile')
                            <span class="text-danger">{{ $message }}</span>
                            <hr>
                        @enderror

                        <div class="input-group mb-3">
                            <label class="title-input2" for="email">Digite seu email *</label>
                            <input type="email" name="email" id="email" class="form-control"
                                placeholder="meuemail@mail.com" required
                                value="{{ old('email', session('customerData')['email'] ?? '') }}">
                        </div>

                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                            <hr>
                        @enderror

                        <div class="navigation-buttons">
                            <button type="button" class="btn btn-nav btn-back"
                                style="background-color:{{ config('custom.background_button_next_prev') }}; color:{{ config('custom.text_color_button_next_prev') }};">Voltar</button>
                            <button type="button" class="btn btn-nav btn-next"
                                style="background-color:{{ config('custom.background_button_next_prev') }}; color:{{ config('custom.text_color_button_next_prev') }};">Próximo</button>
                        </div>

                        <div class="step-alert" data-step-alert="2">
                            <i class="fa fa-exclamation-triangle step-alert-icon"></i>
                            <span>Para continuar, preencha: <strong class="step-alert-fields"></strong>.</span>
                        </div>
                        </div>
                    </div>

                    <!-- Step 3: Dependents (only when telemedicine plan) -->
                    <div class="step-content" data-step-content="3" data-conditional="telemedicine">
                        <div class="step-form-inner">
                        <div class="dependents-intro">
                            <span class="title-input2">Quantos dependentes deseja cadastrar?</span>
                            <small class="d-block">Selecione de 1 a 3 dependentes para incluir nos benefícios de
                                telemedicina.</small>
                        </div>

                        <div class="dependents-count-selector">
                            <div class="dependent-count-card" data-count="1">
                                <div class="dependent-count-number">1</div>
                                <div class="dependent-count-label">Dependente</div>
                            </div>
                            <div class="dependent-count-card" data-count="2">
                                <div class="dependent-count-number">2</div>
                                <div class="dependent-count-label">Dependentes</div>
                            </div>
                            <div class="dependent-count-card" data-count="3">
                                <div class="dependent-count-number">3</div>
                                <div class="dependent-count-label">Dependentes</div>
                            </div>
                        </div>

                        <input type="hidden" name="dependents_count" id="dependents_count"
                            value="{{ old('dependents_count', 0) }}">

                        <div id="dependents-empty-state" class="dependents-empty">
                            Selecione acima a quantidade de dependentes para preencher os dados.
                        </div>

                        <div id="dependents-forms-container">
                            @for ($i = 1; $i <= 3; $i++)
                                <div class="dependent-card" data-dependent-index="{{ $i }}" style="display:none;">
                                    <div class="dependent-card-header">
                                        <div class="dependent-card-icon">{{ $i }}</div>
                                        <h6 class="dependent-card-title">Dependente {{ $i }}</h6>
                                    </div>

                                    <div class="input-group mb-3">
                                        <label class="title-input2" for="dependent_{{ $i }}_name">Nome
                                            completo *</label>
                                        <input type="text" name="dependents[{{ $i }}][name]"
                                            id="dependent_{{ $i }}_name" class="form-control"
                                            placeholder="Nome completo do dependente"
                                            value="{{ old('dependents.' . $i . '.name') }}">
                                    </div>

                                    <div class="dependent-row">
                                        <div class="input-group mb-3">
                                            <label class="title-input2"
                                                for="dependent_{{ $i }}_birth_date">Data de
                                                nascimento *</label>
                                            <input type="date" name="dependents[{{ $i }}][birth_date]"
                                                id="dependent_{{ $i }}_birth_date" class="form-control"
                                                value="{{ old('dependents.' . $i . '.birth_date') }}">
                                        </div>
                                        <div class="input-group mb-1">
                                            <label class="title-input2" for="dependent_{{ $i }}_cpf">CPF
                                                *</label>
                                            <input type="text" name="dependents[{{ $i }}][cpf]"
                                                id="dependent_{{ $i }}_cpf"
                                                class="form-control dependent-cpf"
                                                placeholder="000.000.000-00"
                                                value="{{ old('dependents.' . $i . '.cpf') }}">
                                            <div class="field-error mt-1" data-error-for="dependent_{{ $i }}_cpf"></div>
                                        </div>
                                    </div>

                                    <div class="input-group mb-3">
                                        <label class="title-input2" for="dependent_{{ $i }}_email">Email
                                            *</label>
                                        <input type="email" name="dependents[{{ $i }}][email]"
                                            id="dependent_{{ $i }}_email" class="form-control"
                                            placeholder="email@exemplo.com"
                                            value="{{ old('dependents.' . $i . '.email') }}">
                                    </div>

                                    <div class="input-group mb-3">
                                        <label class="title-input2">Gênero Biológico: *</label>
                                        <div class="gender-options">
                                            @php $oldGender = old('dependents.' . $i . '.gender'); @endphp
                                            <button type="button"
                                                class="gender-option {{ $oldGender === 'M' ? 'active' : '' }}"
                                                data-gender="M"
                                                data-target="dependent_{{ $i }}_gender">Masculino</button>
                                            <button type="button"
                                                class="gender-option {{ $oldGender === 'F' ? 'active' : '' }}"
                                                data-gender="F"
                                                data-target="dependent_{{ $i }}_gender">Feminino</button>
                                        </div>
                                        <input type="hidden" name="dependents[{{ $i }}][gender]"
                                            id="dependent_{{ $i }}_gender" value="{{ $oldGender }}">
                                    </div>
                                </div>
                            @endfor
                        </div>

                        @error('dependents')
                            <span class="text-danger">{{ $message }}</span>
                            <hr>
                        @enderror

                        <div class="navigation-buttons">
                            <button type="button" class="btn btn-nav btn-back"
                                style="background-color:{{ config('custom.background_button_next_prev') }}; color:{{ config('custom.text_color_button_next_prev') }};">Voltar</button>
                            <button type="button" class="btn btn-nav btn-next"
                                style="background-color:{{ config('custom.background_button_next_prev') }}; color:{{ config('custom.text_color_button_next_prev') }};">Próximo</button>
                        </div>

                        <div class="step-alert" data-step-alert="3">
                            <i class="fa fa-exclamation-triangle step-alert-icon"></i>
                            <span>Para continuar, preencha: <strong class="step-alert-fields"></strong>.</span>
                        </div>
                        </div>
                    </div>

                    <!-- Step 4: Credentials -->
                    <div class="step-content" data-step-content="4">
                        <div class="step-form-inner">
                            <div class="input-group mb-1">
                                <label class="title-input2" for="usuario">Seu usuário
                                    <small style="font-weight:400; opacity:.75;">(será o seu e-mail)</small>
                                </label>
                                <input type="text" name="login" id="usuario" class="form-control"
                                       value="{{ old('login', session('customerData')['login'] ?? '') }}" required readonly>
                            </div>
                            <small class="field-hint mb-3 d-block">Para mudar, ajuste o e-mail no passo anterior.</small>

                            @if (
                                !session()->has('customerData') ||
                                    (session()->has('customerData') && session('customerData')['source'] !== 'temporarily'))
                                <div class="input-group mb-1">
                                    <label class="title-input2" for="password">Crie sua senha *</label>
                                    <div class="input-eye-wrapper">
                                        <input type="password"
                                               value="{{ session()->has('authenticate') ? session('customerData')['password'] : '' }}"
                                               name="password" id="password" class="form-control"
                                               placeholder="Crie uma senha forte" required minlength="6"
                                               autocomplete="new-password"
                                               {{ session()->has('authenticate') ? 'readonly' : '' }}>
                                        <button type="button" class="input-eye" data-target="password" aria-label="Mostrar senha">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="field-error" data-error-for="password"></div>

                                <div class="input-group mb-1 mt-3">
                                    <label class="title-input2" for="password_confirmation">Confirmação de senha *</label>
                                    <div class="input-eye-wrapper">
                                        <input type="password" name="password_confirmation"
                                               id="password_confirmation" class="form-control"
                                               placeholder="Repita sua senha" required minlength="6"
                                               autocomplete="new-password">
                                        <button type="button" class="input-eye" data-target="password_confirmation" aria-label="Mostrar senha">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="field-error" data-error-for="password_confirmation"></div>
                            @endif

                            <div class="navigation-buttons mt-3">
                                <button type="button" class="btn btn-nav btn-back"
                                    style="background-color:{{ config('custom.background_button_next_prev') }}; color:{{ config('custom.text_color_button_next_prev') }};">Voltar</button>
                                <button type="button" class="btn btn-nav btn-next"
                                    style="background-color:{{ config('custom.background_button_next_prev') }}; color:{{ config('custom.text_color_button_next_prev') }};">Próximo</button>
                            </div>

                            <div class="step-alert" data-step-alert="4">
                                <i class="fa fa-exclamation-triangle step-alert-icon"></i>
                                <span>Para continuar, preencha: <strong class="step-alert-fields"></strong>.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Payment -->
                    <div class="step-content" data-step-content="5">
                        <div class="step-form-inner">
                            <div class="step-section-title">Forma de pagamento</div>

                            <div class="pay-method-pills" id="payMethodPills">
                                {{-- Pills geradas dinamicamente via JS conforme allowed_billing_types do plano selecionado. --}}
                            </div>

                            <input type="hidden" name="billing_type" id="billing_type" value="CREDIT_CARD">

                            {{-- Bloco de cartão de crédito (default) --}}
                            <div data-pay-block="CREDIT_CARD">
                                <div class="input-group mb-3">
                                    <input type="text" name="credit_card_name" id="card_name" class="form-control"
                                           placeholder="Nome impresso no cartão *"
                                           value="{{ old('credit_card_name', session('customerData')['credit_card_name'] ?? '') }}">
                                </div>
                                <div class="field-error" data-error-for="credit_card_name"></div>

                                <div class="input-group mb-3 mt-2">
                                    <input type="text" inputmode="numeric" name="credit_card_number" id="card_number"
                                           class="form-control" placeholder="Número do cartão *" maxlength="19"
                                           value="{{ old('credit_card_number', session('customerData')['credit_card_number'] ?? '') }}">
                                </div>
                                <div class="field-error" data-error-for="credit_card_number"></div>

                                <div class="pay-card-row mt-2">
                                    <input type="text" inputmode="numeric" name="credit_card_expiry_month" id="card_expiry_month"
                                           class="form-control" placeholder="MM *" maxlength="2"
                                           value="{{ old('credit_card_expiry_month', session('customerData')['credit_card_expiry_month'] ?? '') }}">
                                    <input type="text" inputmode="numeric" name="credit_card_expiry_year" id="card_expiry_year"
                                           class="form-control" placeholder="AAAA *" maxlength="4"
                                           value="{{ old('credit_card_expiry_year', session('customerData')['credit_card_expiry_year'] ?? '') }}">
                                    <input type="text" inputmode="numeric" name="credit_card_ccv" id="card_ccv"
                                           class="form-control" placeholder="CVV *" maxlength="4"
                                           value="{{ old('credit_card_ccv', session('customerData')['credit_card_ccv'] ?? '') }}">
                                </div>
                                <div class="field-error" data-error-for="credit_card_expiry"></div>

                                <p class="pay-https-note mt-3 mb-0">
                                    Não armazenamos dados do cartão — eles vão direto pra Asaas via HTTPS.
                                </p>
                            </div>

                            {{-- Bloco PIX --}}
                            <div data-pay-block="PIX" style="display:none;">
                                <div class="pay-method-info">
                                    <strong><i class="fa fa-qrcode mr-2"></i>Pagamento via PIX</strong>
                                    Após finalizar o cadastro, você receberá um QR Code e código copia-e-cola para
                                    concluir o pagamento. Sua assinatura é ativada assim que o pagamento for confirmado.
                                </div>
                            </div>

                            {{-- Bloco BOLETO --}}
                            <div data-pay-block="BOLETO" style="display:none;">
                                <div class="pay-method-info">
                                    <strong><i class="fa fa-barcode mr-2"></i>Pagamento via Boleto</strong>
                                    Após finalizar o cadastro, você receberá o link do boleto. A compensação pode levar
                                    até 3 dias úteis após o pagamento.
                                </div>
                            </div>

                            <div class="d-flex flex-row input-group mb-2 mt-4">
                                <input type="checkbox" name="terms" id="terms" required="" value="">
                                <span class="text-white ml-2">Aceitar termos e condições</span>
                                <a href="https://saude.clicaimax.com.br/termo-de-uso/" class="ml-2">visualizar termo.</a>
                            </div>

                            <div class="navigation-buttons">
                                <button type="button" class="btn btn-nav btn-back"
                                    style="background-color:{{ config('custom.background_button_next_prev') }}; color:{{ config('custom.text_color_button_next_prev') }};">Voltar</button>
                                <button type="submit" class="btn btn-nav btn-submit"
                                    style="background-color:{{ config('custom.background_button_next_prev') }}; color:{{ config('custom.text_color_button_next_prev') }};">Finalizar
                                    Cadastro</button>
                            </div>

                            <div class="step-alert" data-step-alert="5">
                                <i class="fa fa-exclamation-triangle step-alert-icon"></i>
                                <span>Para continuar, preencha: <strong class="step-alert-fields"></strong>.</span>
                            </div>
                        </div>
                    </div>

                    <div class="footer-links">
                        <a href="{{ route('login') }}">
                            <i class="fa fa-user-plus mr-2"></i> Já tenho conta
                        </a>
                        <a href="http://clicaimax.com.br/">
                            <i class="fa fa-home mr-2"></i> Voltar para Home
                        </a>
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

@endsection

@section('javascriptLocal')
    <script>
        // === Estado global compartilhado ===
        const cpfDuplicateCache = new Map(); // cpfDigits -> bool
        const cpfPending = new Map();        // cpfDigits -> Promise
        const CSRF = '{{ csrf_token() }}';

        $(function() {
            initMasks();
            initStepNavigation();
            initDependentsUI();
            initPlanCards();
            initPlanArrows();
            initPasswordEye();
            initPasswordMatchLive();
            initCpfBlurCheck();
            initPayMethods();

            applyTelemedicineState();
        });

        function isTelemedicine() {
            const $selected = $('.reg-plan-card.selected').first();
            return $selected.length && $selected.data('telemedicine') == 1;
        }

        function initPlanCards() {
            $(document).on('click', '.reg-plan-card', function() {
                const $card = $(this);
                $('.reg-plan-card').removeClass('selected');
                $card.addClass('selected');
                $('#plan_id').val($card.data('plan-id'));
                applyTelemedicineState();
            });
        }

        function initPlanArrows() {
            const $track = $('#regPlanTrack');
            const $prev = $('.reg-plan-prev');
            const $next = $('.reg-plan-next');
            if (!$track.length) return;

            const updateArrows = () => {
                const el = $track[0];
                const hasOverflow = el.scrollWidth > el.clientWidth + 4;
                $prev.toggleClass('show', hasOverflow && el.scrollLeft > 4);
                $next.toggleClass('show', hasOverflow && el.scrollLeft < (el.scrollWidth - el.clientWidth - 4));
            };

            $prev.on('click', () => $track[0].scrollBy({ left: -220, behavior: 'smooth' }));
            $next.on('click', () => $track[0].scrollBy({ left: 220, behavior: 'smooth' }));
            $track.on('scroll', updateArrows);
            $(window).on('resize', updateArrows);
            setTimeout(updateArrows, 50);
        }

        function getVisibleSteps() {
            return isTelemedicine() ? [1, 2, 3, 4, 5] : [1, 2, 4, 5];
        }

        function applyTelemedicineState() {
            const tele = isTelemedicine();
            const $depStep = $('.step[data-step="3"]');

            if (tele) {
                $depStep.removeClass('step-hidden');
            } else {
                $depStep.addClass('step-hidden');
                clearAllDependents();

                if ($('.step-content[data-step-content="3"]').hasClass('active')) {
                    navigateToStep(4);
                }
            }

            // Recalculate progress for current visible step
            const currentStep = parseInt($('.step-content.active').data('step-content')) || 1;
            updateProgressBar(currentStep);
        }

        function initMasks() {
            $('#document').mask('000.000.000-00');
            $('#mobile').mask('(00) 00000-0000');
            $('.dependent-cpf').mask('000.000.000-00');
        }

        function initStepNavigation() {
            $('.btn-next').on('click', async function() {
                const currentStep = parseInt($(this).closest('.step-content').data('step-content'));
                const visible = getVisibleSteps();
                const idx = visible.indexOf(currentStep);
                if (idx < 0 || idx >= visible.length - 1) return;

                const missing = await validateStep(currentStep);
                renderStepAlert(currentStep, missing);
                if (missing.length) return;

                navigateToStep(visible[idx + 1]);
            });

            // Submit do form (Step 5)
            $('#registerForm').on('submit', async function (e) {
                const missing = await validateStep(5);
                renderStepAlert(5, missing);
                if (missing.length) {
                    e.preventDefault();
                    return false;
                }
            });

            $('.btn-back').on('click', function() {
                const currentStep = parseInt($(this).closest('.step-content').data('step-content'));
                const visible = getVisibleSteps();
                const idx = visible.indexOf(currentStep);
                if (idx > 0) {
                    navigateToStep(visible[idx - 1]);
                }
            });
        }

        function navigateToStep(stepNumber) {
            $('.step-content').removeClass('active');
            $(`.step-content[data-step-content="${stepNumber}"]`).addClass('active');

            updateProgressBar(stepNumber);

            $('.step').removeClass('active');
            $(`.step[data-step="${stepNumber}"]`).addClass('active');

            // Auto-populate o usuario com o email ao entrar no Step 4
            if (stepNumber === 4) {
                const email = ($('#email').val() || '').trim();
                if (email) {
                    $('#usuario').val(email);
                }
            }

            // Atualiza pills de pagamento ao entrar no Step 5
            if (stepNumber === 5) {
                refreshPayMethods();
            }

            // Ao sair de um step, esconde o alerta dele
            $('.step-alert').removeClass('show');

            $('html, body').animate({ scrollTop: $('.card-register').offset().top - 20 }, 250);
        }

        // ====== VALIDAÇÃO POR STEP ======

        async function validateStep(step) {
            const missing = [];

            if (step === 1) {
                if (!$('#plan_id').val()) missing.push('Plano');
            }

            if (step === 2) {
                if (!$('#name').val().trim()) missing.push('Nome');
                const doc = $('#document').val().trim();
                if (!doc) {
                    missing.push('CPF');
                } else if (!isValidCPF(doc)) {
                    missing.push('CPF (inválido)');
                    setFieldError('document', 'CPF inválido.');
                } else {
                    const dupe = await isCpfDuplicate(doc);
                    if (dupe) {
                        missing.push('CPF (já cadastrado)');
                        setFieldError('document', 'Este CPF já está cadastrado.');
                    } else {
                        setFieldError('document', '');
                    }
                }
                const mobile = $('#mobile').val().trim();
                if (!mobile || mobile.replace(/\D/g, '').length < 10) missing.push('Celular');
                const email = $('#email').val().trim();
                if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) missing.push('E-mail');
            }

            if (step === 3) {
                const count = parseInt($('#dependents_count').val()) || 0;
                if (count === 0) {
                    missing.push('Quantidade de dependentes');
                }
                for (let i = 1; i <= count; i++) {
                    const $card = $(`.dependent-card[data-dependent-index="${i}"]`);
                    const name   = $card.find(`#dependent_${i}_name`).val().trim();
                    const birth  = $card.find(`#dependent_${i}_birth_date`).val();
                    const email  = $card.find(`#dependent_${i}_email`).val().trim();
                    const cpf    = $card.find(`#dependent_${i}_cpf`).val().trim();
                    const gender = $card.find(`#dependent_${i}_gender`).val();

                    if (!name || !birth || !email || !cpf || !gender) {
                        missing.push(`Dados do Dependente ${i}`);
                        continue;
                    }
                    if (!isValidCPF(cpf)) {
                        missing.push(`CPF do Dependente ${i} (inválido)`);
                        continue;
                    }
                    const dupe = await isCpfDuplicate(cpf);
                    if (dupe) {
                        missing.push(`CPF do Dependente ${i} (já cadastrado)`);
                    }
                }
            }

            if (step === 4) {
                // login auto-populado, só validamos senha
                const hasPwdBlock = $('#password').length > 0;
                if (hasPwdBlock) {
                    const p1 = $('#password').val();
                    const p2 = $('#password_confirmation').val();
                    if (!p1 || p1.length < 6) {
                        missing.push('senha');
                        setFieldError('password', 'A senha precisa ter no mínimo 6 caracteres.');
                    } else {
                        setFieldError('password', '');
                    }
                    if (!p2) {
                        missing.push('confirmação de senha');
                        setFieldError('password_confirmation', 'Confirme a senha.');
                    } else if (p1 && p1 !== p2) {
                        missing.push('confirmação de senha');
                        setFieldError('password_confirmation', 'As senhas não coincidem.');
                    } else {
                        setFieldError('password_confirmation', '');
                    }
                }
            }

            if (step === 5) {
                const billing = $('#billing_type').val();
                if (!billing) missing.push('Forma de pagamento');
                if (billing === 'CREDIT_CARD') {
                    if (!$('#card_name').val().trim()) missing.push('Nome no cartão');
                    const num = $('#card_number').val().replace(/\D/g, '');
                    if (!num || num.length < 13) missing.push('Número do cartão');
                    const mm = $('#card_expiry_month').val().trim();
                    if (!mm || parseInt(mm) < 1 || parseInt(mm) > 12) missing.push('Mês de validade');
                    const yy = $('#card_expiry_year').val().trim();
                    if (!yy || yy.length !== 4) missing.push('Ano de validade');
                    const cvv = $('#card_ccv').val().trim();
                    if (!cvv || cvv.length < 3) missing.push('CVV');
                }
                if (!$('#terms').is(':checked')) missing.push('aceite dos termos');
            }

            return missing;
        }

        function renderStepAlert(step, missing) {
            const $alert = $(`.step-alert[data-step-alert="${step}"]`);
            if (!$alert.length) return;
            if (!missing.length) {
                $alert.removeClass('show');
                return;
            }
            $alert.find('.step-alert-fields').text(missing.join(', '));
            $alert.addClass('show');
        }

        function setFieldError(inputId, msg) {
            $(`.field-error[data-error-for="${inputId}"]`).text(msg || '');
        }

        // ====== OLHO DE SENHA ======
        function initPasswordEye() {
            $(document).on('click', '.input-eye', function () {
                const target = $(this).data('target');
                const $input = $('#' + target);
                if (!$input.length) return;
                const isPwd = $input.attr('type') === 'password';
                $input.attr('type', isPwd ? 'text' : 'password');
                $(this).find('i').toggleClass('fa-eye fa-eye-slash');
            });
        }

        // ====== MATCH DE SENHAS AO VIVO ======
        function initPasswordMatchLive() {
            $(document).on('input', '#password, #password_confirmation', function () {
                const p1 = $('#password').val();
                const p2 = $('#password_confirmation').val();
                if (p1 && p1.length < 6) {
                    setFieldError('password', 'Mínimo 6 caracteres.');
                } else {
                    setFieldError('password', '');
                }
                if (p2 && p1 !== p2) {
                    setFieldError('password_confirmation', 'As senhas não coincidem.');
                } else {
                    setFieldError('password_confirmation', '');
                }
            });
        }

        // ====== CPF: algoritmo + cache + AJAX ======
        function isValidCPF(cpf) {
            cpf = String(cpf || '').replace(/\D/g, '');
            if (cpf.length !== 11) return false;
            if (/^(\d)\1{10}$/.test(cpf)) return false;
            let sum = 0;
            for (let i = 0; i < 9; i++) sum += parseInt(cpf.charAt(i)) * (10 - i);
            let d1 = 11 - (sum % 11); if (d1 >= 10) d1 = 0;
            if (d1 !== parseInt(cpf.charAt(9))) return false;
            sum = 0;
            for (let i = 0; i < 10; i++) sum += parseInt(cpf.charAt(i)) * (11 - i);
            let d2 = 11 - (sum % 11); if (d2 >= 10) d2 = 0;
            if (d2 !== parseInt(cpf.charAt(10))) return false;
            return true;
        }

        async function isCpfDuplicate(cpf) {
            const digits = String(cpf || '').replace(/\D/g, '');
            if (digits.length !== 11) return false;
            if (cpfDuplicateCache.has(digits)) return cpfDuplicateCache.get(digits);
            if (cpfPending.has(digits)) return cpfPending.get(digits);

            const p = fetch('/api/check-cpf', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF
                },
                body: JSON.stringify({ cpf: digits })
            })
            .then(r => r.ok ? r.json() : { exists: false })
            .then(d => {
                const exists = !!(d && d.exists);
                cpfDuplicateCache.set(digits, exists);
                cpfPending.delete(digits);
                return exists;
            })
            .catch(() => {
                cpfPending.delete(digits);
                return false;
            });

            cpfPending.set(digits, p);
            return p;
        }

        function initCpfBlurCheck() {
            $(document).on('blur', '#document, .dependent-cpf', async function () {
                const $el = $(this);
                const val = $el.val().trim();
                if (!val) return;
                if (!isValidCPF(val)) {
                    const inputId = $el.attr('id');
                    setFieldError(inputId, 'CPF inválido.');
                    return;
                }
                const dupe = await isCpfDuplicate(val);
                const inputId = $el.attr('id');
                setFieldError(inputId, dupe ? 'Este CPF já está cadastrado.' : '');
            });
        }

        // ====== PILLS DE PAGAMENTO (Step 5) ======
        function getAllowedBillingTypes() {
            const $selected = $('.reg-plan-card.selected').first();
            if (!$selected.length) return ['CREDIT_CARD'];
            const raw = ($selected.data('allowed-billing-types') || 'CREDIT_CARD').toString();
            return raw.split(',').map(s => s.trim()).filter(Boolean);
        }

        function initPayMethods() {
            $(document).on('click', '.pay-method-pill', function () {
                const method = $(this).data('method');
                $('.pay-method-pill').removeClass('active');
                $(this).addClass('active');
                $('#billing_type').val(method);
                $('[data-pay-block]').hide();
                $(`[data-pay-block="${method}"]`).show();
            });
        }

        function refreshPayMethods() {
            const labels = { CREDIT_CARD: '<i class="fa fa-credit-card mr-2"></i>Cartão de crédito',
                             PIX:         '<i class="fa fa-qrcode mr-2"></i>PIX',
                             BOLETO:      '<i class="fa fa-barcode mr-2"></i>Boleto' };
            const allowed = getAllowedBillingTypes();
            const $pills = $('#payMethodPills').empty();
            allowed.forEach((m, i) => {
                const html = labels[m] || m;
                $pills.append(`<button type="button" class="pay-method-pill ${i === 0 ? 'active' : ''}" data-method="${m}">${html}</button>`);
            });
            const initial = allowed[0] || 'CREDIT_CARD';
            $('#billing_type').val(initial);
            $('[data-pay-block]').hide();
            $(`[data-pay-block="${initial}"]`).show();
        }

        function updateProgressBar(stepNumber) {
            const visible = getVisibleSteps();
            const idx = visible.indexOf(stepNumber);
            const safeIdx = idx >= 0 ? idx : 0;
            const progressPercentage = visible.length > 1 ? (safeIdx / (visible.length - 1)) * 100 : 0;
            $('.step-progress-bar').css('width', progressPercentage + '%');
        }

        function initDependentsUI() {
            $('.dependent-count-card').on('click', function() {
                const count = parseInt($(this).data('count'));
                setDependentCount(count);
            });

            $('.gender-option').on('click', function() {
                const $btn = $(this);
                const targetId = $btn.data('target');
                const value = $btn.data('gender');

                $btn.siblings('.gender-option').removeClass('active');
                $btn.addClass('active');
                $('#' + targetId).val(value);
            });

            // Restore state from old() input if any dependent has data
            const oldCount = parseInt($('#dependents_count').val()) || 0;
            if (oldCount > 0) {
                setDependentCount(oldCount);
            }
        }

        function setDependentCount(count) {
            count = parseInt(count) || 0;
            $('#dependents_count').val(count);

            $('.dependent-count-card').removeClass('active');
            $(`.dependent-count-card[data-count="${count}"]`).addClass('active');

            $('#dependents-empty-state').toggle(count === 0);

            $('.dependent-card').each(function() {
                const $card = $(this);
                const idx = parseInt($card.data('dependent-index'));
                const $inputs = $card.find('input:not([type=hidden])');
                if (idx <= count) {
                    $card.show();
                    $inputs.prop('required', true);
                } else {
                    $card.hide();
                    $inputs.prop('required', false).val('');
                    $card.find('input[type=hidden]').val('');
                    $card.find('.gender-option').removeClass('active');
                }
            });
        }

        function clearAllDependents() {
            setDependentCount(0);
        }

        function validateDependentsStep() {
            const count = parseInt($('#dependents_count').val()) || 0;
            if (count === 0) {
                alert('Selecione a quantidade de dependentes para continuar.');
                return false;
            }

            for (let i = 1; i <= count; i++) {
                const $card = $(`.dependent-card[data-dependent-index="${i}"]`);
                const name = $card.find(`#dependent_${i}_name`).val().trim();
                const birth = $card.find(`#dependent_${i}_birth_date`).val();
                const email = $card.find(`#dependent_${i}_email`).val().trim();
                const cpf = $card.find(`#dependent_${i}_cpf`).val().trim();
                const gender = $card.find(`#dependent_${i}_gender`).val();

                if (!name || !birth || !email || !cpf || !gender) {
                    alert(`Preencha todos os dados do Dependente ${i}.`);
                    return false;
                }

                const cpfDigits = cpf.replace(/\D/g, '');
                if (cpfDigits.length !== 11) {
                    alert(`CPF do Dependente ${i} é inválido.`);
                    return false;
                }
            }
            return true;
        }

        function selectPlan(planId) {
            $(`.reg-plan-card[data-plan-id="${planId}"]`).trigger('click');
        }

        document.getElementById('applyCoupon').addEventListener('click', function() {
            const coupon = document.getElementById('coupon').value;
            const planId = document.getElementById('plan_id').value;

            if (!coupon || !planId) {
                document.getElementById('couponFeedback').innerText = 'Selecione um plano e insira um cupom.';
                return;
            }

            fetch('/validate-coupon', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        coupon: coupon,
                        plan_id: planId
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    const feedback = document.getElementById('couponFeedback');
                    if (data.valid) {
                        feedback.innerText = data.message;
                        feedback.classList.remove('text-danger');
                        feedback.classList.add('text-success');

                        const $priceEl = $(`.reg-plan-card[data-plan-id="${planId}"] .reg-plan-price-value`);
                        if ($priceEl.length) {
                            $priceEl.text('R$ ' + data.discounted_value);
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
