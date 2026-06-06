<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Order;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\AppIntegration\CustomerService;
use App\Services\RegistrationService;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm(int|string $planId = null)
    {
        $planId = $planId ?: '';

        // Carrega plan inteiro (allowed_billing_types entra automaticamente quando a coluna existir — T7).
        $plans = Plan::with('benefits:id,plan_id,description')
            ->where('is_active', 1)
            ->orderBy('priority')
            ->get();

        $data = Plan::getPlansData();

        return view('auth.register', [
            'planId' => $planId,
            'plans' => $plans,
            'cycles' => $data['cycles'],
            'plansByCycle' => $data['plansByCycle'],
            'activeCycle' => $data['activeCycle']
        ]);
    }

    /**
     * Verifica se o CPF já existe em customers.document ou cpf_dependente_*.
     */
    public function checkCpf(Request $request)
    {
        $cpf = preg_replace('/\D/', '', (string) $request->input('cpf', ''));

        if (strlen($cpf) !== 11) {
            return response()->json(['exists' => false]);
        }

        $exists = Customer::where('document', $cpf)
            ->orWhere('cpf_dependente_1', $cpf)
            ->orWhere('cpf_dependente_2', $cpf)
            ->orWhere('cpf_dependente_3', $cpf)
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    protected function validator(array $data): ValidationValidator
    {
        $planId = $data['plan_id'] ?? null;
        $isTelemedicinePlan = false;
        if ($planId) {
            $isTelemedicinePlan = (bool) Plan::where('id', $planId)->value('is_active_telemedicine');
        }

        $rules = [
            'id' => ['integer'],
            'name' => ['required', 'string'],
            'document' => ['required', new \App\Rules\Cpf()],
            'mobile' => ['required', 'string'],
            'birthdate' => ['date'],
            'email' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($data) {
                    if (($data['source'] ?? null) === 'temporarily') {
                        return;
                    }

                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $fail('O campo email deve conter um endereço de email válido.');
                    }

                    if (\App\Models\Customer::where('email', $value)->exists()) {
                        $fail('O email já está em uso.');
                    }
                }
            ],
            'login' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($data) {
                    if (($data['source'] ?? null) === 'temporarily') {
                        return;
                    }

                    if (\App\Models\Customer::where('login', $value)->exists()) {
                        $fail('O login já está em uso.');
                    }
                }
            ],
            'password' => [
                function ($attribute, $value, $fail) use ($data) {
                    if (($data['source'] ?? null) !== 'temporarily' && empty($value)) {
                        $fail('O campo senha é obrigatório.');
                    }
                },
                'string',
                'confirmed',
            ],
            'billing_type' => ['required', 'in:CREDIT_CARD,PIX,BOLETO'],
        ];

        // Dados de cartão só são obrigatórios quando o método escolhido for CREDIT_CARD.
        if (($data['billing_type'] ?? 'CREDIT_CARD') === 'CREDIT_CARD') {
            $rules['credit_card_number']       = ['required', new \App\Rules\CreditCard()];
            $rules['credit_card_expiry_month'] = ['required', 'digits:2'];
            $rules['credit_card_expiry_year']  = ['required', 'digits:4'];
            $rules['credit_card_ccv']          = ['required'];
        }

        // Garante que o billing_type escolhido está entre os aceitos pelo plano.
        if (!empty($data['plan_id']) && !empty($data['billing_type'])) {
            $plan = Plan::find($data['plan_id']);
            if ($plan) {
                $allowed = $plan->allowed_billing_types ?: [$plan->billing_type ?: 'CREDIT_CARD'];
                if (!in_array($data['billing_type'], (array) $allowed, true)) {
                    $rules['billing_type'][] = function ($attribute, $value, $fail) use ($allowed) {
                        $fail('Esta forma de pagamento não está disponível para o plano selecionado.');
                    };
                }
            }
        }

        if ($isTelemedicinePlan) {
            $count = (int) ($data['dependents_count'] ?? 0);
            $rules['dependents_count'] = ['required', 'integer', 'min:1', 'max:3'];

            for ($i = 1; $i <= $count; $i++) {
                $rules["dependents.$i.name"] = ['required', 'string', 'max:255'];
                $rules["dependents.$i.birth_date"] = ['required', 'date', 'before:today'];
                $rules["dependents.$i.email"] = ['required', 'email'];
                $rules["dependents.$i.cpf"] = ['required', new \App\Rules\Cpf()];
                $rules["dependents.$i.gender"] = ['required', 'in:M,F'];
            }
        }

        return Validator::make($data, $rules, [
            'dependents_count.required' => 'Selecione a quantidade de dependentes.',
            'dependents_count.min' => 'Informe ao menos 1 dependente.',
            'dependents_count.max' => 'Máximo de 3 dependentes.',
            'dependents.*.name.required' => 'O nome do dependente é obrigatório.',
            'dependents.*.birth_date.required' => 'A data de nascimento do dependente é obrigatória.',
            'dependents.*.birth_date.before' => 'A data de nascimento do dependente deve ser anterior a hoje.',
            'dependents.*.email.required' => 'O email do dependente é obrigatório.',
            'dependents.*.email.email' => 'O email do dependente é inválido.',
            'dependents.*.cpf.required' => 'O CPF do dependente é obrigatório.',
            'dependents.*.gender.required' => 'O gênero do dependente é obrigatório.',
            'dependents.*.gender.in' => 'O gênero do dependente é inválido.',
        ]);
    }

    protected function create(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function register(Request $request, CustomerService $customerService, RegistrationService $registrationService)
    {
        $wantsJson = $request->ajax() || $request->wantsJson() || $request->expectsJson();

        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            if ($wantsJson) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Verifique os campos destacados antes de continuar.',
                    'errors' => $validator->errors()->all(),
                ], 422);
            }
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->only([
            'plan_id',
            'login',
            'password',
            'name',
            'document',
            'mobile',
            'email',
            'payment_asaas_id',
            'credit_card_name',
            'credit_card_number',
            'credit_card_expiry_month',
            'credit_card_expiry_year',
            'credit_card_ccv',
            'coupon',
            'billing_type',
        ]);
        $data['billing_type'] = $data['billing_type'] ?? 'CREDIT_CARD';

        $count = (int) $request->input('dependents_count', 0);
        $dependentsInput = (array) $request->input('dependents', []);
        $dependents = [];
        for ($i = 1; $i <= $count; $i++) {
            if (!isset($dependentsInput[$i])) {
                continue;
            }
            $dep = $dependentsInput[$i];
            $dependents[] = [
                'name' => $dep['name'] ?? null,
                'birth_date' => $dep['birth_date'] ?? null,
                'email' => $dep['email'] ?? null,
                'cpf' => $dep['cpf'] ?? null,
                'gender' => $dep['gender'] ?? null,
            ];
        }
        $data['dependents'] = $dependents;
        $data['cpf_dependente_1'] = $dependents[0]['cpf'] ?? null;
        $data['cpf_dependente_2'] = $dependents[1]['cpf'] ?? null;
        $data['cpf_dependente_3'] = $dependents[2]['cpf'] ?? null;

        $couponName = $request->input('coupon');
        $planId = $request->input('plan_id');

        try {
            if ($couponName) {
                $plan = Plan::find($planId);
                $coupon = $this->getCoupon($couponName);

                if (!$coupon?->is_active || !$plan) {
                    Log::channel('registration')->info('Tentativa de registro com cupom inválido', [
                        'coupon' => $couponName,
                        'plan_id' => $planId,
                        'email' => $data['email'] ?? 'n/a',
                    ]);
                    if ($wantsJson) {
                        return response()->json(['ok' => false, 'message' => 'Cupom inválido.'], 422);
                    }
                    toastr()->info("Cupom inválido.");
                    return back()->withInput()->withErrors(['error' => 'Cupom inválido.']);
                }

                $discountedValue = $this->getDiscount($plan, $coupon);
                $discountedValueFormat = number_format($discountedValue, 2, ',', '.');

                if ($discountedValue > 0 && $discountedValue <= 5) {
                    Log::channel('registration')->info('Tentativa de registro com valor final abaixo do mínimo', [
                        'coupon' => $couponName,
                        'plan_id' => $planId,
                        'final_value' => $discountedValue,
                        'email' => $data['email'] ?? 'n/a',
                    ]);
                    $msg = "O valor final de R$ $discountedValueFormat após o cupom ser aplicado não pode ser menor que R$ 5,00.";
                    if ($wantsJson) {
                        return response()->json(['ok' => false, 'message' => $msg], 422);
                    }
                    toastr()->info($msg);
                    return back()->withInput()->withErrors(['error' => $msg]);
                }
            }

            if (!session()->has('customerData')) {
                $externalCustomer = $this->verifyCustomerInYouCast($customerService);
                if ($externalCustomer instanceof RedirectResponse) {
                    Log::channel('registration')->info('Redirecionado por verificação YouCast', [
                        'email' => $data['email'] ?? 'n/a',
                    ]);
                    return $externalCustomer;
                }
            }

            $data['coupon_id'] = $this->getCoupon($request->coupon)?->id;

            Log::channel('registration')->info('Iniciando processo de registro', [
                'email' => $data['email'],
                'login' => $data['login'],
                'plan_id' => $data['plan_id'],
                'has_coupon' => !empty($data['coupon_id']),
            ]);

            $customer = $registrationService->handle($data);

            Log::channel('registration')->info('Registro concluído com sucesso', [
                'customer_id' => $customer->id,
                'email' => $customer->email,
                'asaas_id' => $customer->customer_id,
                'youcast_id' => $customer->viewers_id,
            ]);

            if ($wantsJson) {
                return response()->json([
                    'ok' => true,
                    'message' => 'Criado com sucesso! Acesse seu e-mail ou faça login.',
                    'redirect' => url('/login'),
                ]);
            }

            toastr()->success('Criado com sucesso! Acesse seu e-mail ou faça login.');
            return redirect('/login');
        } catch (\InvalidArgumentException $e) {
            Log::channel('registration')->warning('Falha na validação do registro', [
                'error' => $e->getMessage(),
                'email' => $data['email'] ?? 'n/a',
                'login' => $data['login'] ?? 'n/a',
            ]);
            if ($wantsJson) {
                return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
            }
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            Log::channel('registration')->error('Erro crítico no registro', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'email' => $data['email'] ?? 'n/a',
                'login' => $data['login'] ?? 'n/a',
            ]);
            if ($wantsJson) {
                return response()->json([
                    'ok' => false,
                    'message' => $e->getMessage() ?: 'Não foi possível concluir o cadastro. Tente novamente.',
                ], 422);
            }
            toastr()->info($e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Ocorreu uma falha ao processar seu cadastro. Tente novamente.']);
        }
    }

    private function verifyCustomerInYouCast(CustomerService $customerService): mixed
    {
        $login = request()->login;
        $password = request()->password;
        $couponName = request()->coupon;
        $coupon = $this->getCoupon($couponName);

        $externalCustomer = $customerService->findExternalCustomerByLogin($login, $password);

        if ($externalCustomer) {
            $authenticateExternalCustomer = $customerService->authenticateExternalCustomer($login, $password);

            $customerData = $externalCustomer['customer'];

            if ($authenticateExternalCustomer) {
                session([
                    'authenticate' => true,
                    'customerData' => $customerData,
                ]);

                return redirect()->route('login')->with([
                    'info' => 'Usuário localizado na plataforma de Streaming. Efetue o login ou recupere a senha.',
                ]);
            }

            $data = request()->only(['login', 'name', 'document', 'mobile', 'birthdate', 'email', 'cpf_dependente_1', 'cpf_dependente_2', 'cpf_dependente_3']);

            Customer::create([
                'viewers_id' => $customerData['viewers_id'],
                'login' => $customerData['login'],
                'name' => $customerData['name'],
                'cpf_dependente_1' => $customerData['cpf_dependente_1'] ?? null,
                'cpf_dependente_2' => $customerData['cpf_dependente_2'] ?? null,
                'cpf_dependente_3' => $customerData['cpf_dependente_3'] ?? null,
                'email' => $data['email'],
                'coupon_id' => $coupon->id ?? null,
            ]);

            return redirect()->route('login')->with([
                'error' => 'Usuário localizado na plataforma de Streaming. Login ou senha incorretos. Tente novamente ou clique em recuperar senha informando o email cadastrado: ' . $customerData['email'],
            ]);
        }

        return null;
    }

    private function getCoupon(mixed $couponName): ?Coupon
    {
        return Coupon::where('name', $couponName)->first();
    }

    private function getDiscount(Plan $plan, Coupon $coupon): mixed
    {
        return $plan->value - ($plan->value * ($coupon->percent / 100));
    }
}
