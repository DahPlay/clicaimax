<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerControllerFind extends Controller
{
    public function activeCustomers(Request $request)
    {
        $cpf = $request->input('cpf');

        if (!$cpf) {
            return response()->json(['error' => 'CPF não informado.'], 400);
        }

        //SOMENTE TELEMEDICINA
        // $customer = Customer::where('document', $cpf)
        //     ->whereHas('orders', function ($query) {
        //         $query->where('status', 'ACTIVE')
        //               ->whereHas('plan', function ($q) {
        //                   $q->where('name', 'like', '%Telemedicina%');
        //               });
        //     })
        //     ->with(['orders' => function ($query) {
        //         $query->where('status', 'ACTIVE')
        //               ->whereHas('plan', function ($q) {
        //                   $q->where('name', 'like', '%Telemedicina%');
        //               })
        //               ->with('plan');
        //     }])
        //     ->first();

        // if (!$customer) {
        //     return response()->json(['error' => 'Cliente não encontrado ou sem plano Telemedicina ativo.'], 404);
        // }

        //TODOS OS PLANOS
        $customer = Customer::where('document', $cpf)
            ->whereHas('orders', function ($query) {
                $query->where('status', 'ACTIVE');
            })
            ->with([
                'orders' => function ($query) {
                    $query->where('status', 'ACTIVE')->with('plan');
                }
            ])
            ->first();

        if (!$customer) {
            return response()->json(['error' => 'Cliente não encontrado ou sem plano ativo.'], 404);
        }

        $order = $customer->orders->first();

        return response()->json([
            'nome' => $customer->name,
            'cpf' => $customer->document,
            'cpf_dependente_1' => $customer->cpf_dependente_1,
            'cpf_dependente_2' => $customer->cpf_dependente_2,
            'cpf_dependente_3' => $customer->cpf_dependente_3,
            'status_plano' => $order->status ?? 'N/A',
            'nome_plano' => $order->plan->name ?? 'N/A',
        ]);
    }
}
