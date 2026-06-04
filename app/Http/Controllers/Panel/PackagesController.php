<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\PackageRequest;
use App\Models\Package;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PackagesController extends Controller
{
    public function __construct(public Package $model, public Request $request)
    {
        $this->middleware('can:developer');
    }

    public function index(): View
    {
        return view($this->request->route()->getName());
    }

    public function loadDatatable(): JsonResponse
    {
        $packages = $this->model
            ->get();

        return DataTables::of($packages)
            ->addColumn('checkbox', function ($package) {
                return view('panel.packages.local.index.datatable.checkbox', compact('package'));
            })
            ->editColumn('id', function ($package) {
                return view('panel.packages.local.index.datatable.id', compact('package'));
            })
            ->editColumn('created_at', function ($package) {
                return $package->created_at ? date('d/m/Y H:i:s', strtotime($package->created_at)) : 'Sem data';
            })
            ->editColumn('is_active', function ($item) {
                return view('panel.packages.local.index.datatable.is_active', compact('item'));
            })
            ->addColumn('action', function ($package) {
                $loggedId = auth()->user()->id;

                return view('panel.packages.local.index.datatable.action', compact('package', 'loggedId'));
            })
            ->toJson();
    }

    public function create(): View
    {
        $package = new Package();
        return view('panel.packages.local.index.modals.create', compact('package'));
    }

    public function store(PackageRequest $request): RedirectResponse
    {
        $isActive = match ($request->validated()['is_active']) {
            'on' => true,
            '0' => false,
        };

        $isSuspension = match ($request->validated()['is_suspension']) {
            'on' => true,
            '0' => false,
        };

        $data = $request->validated();

        $data['is_active'] = $isActive;
        $data['is_suspension'] = $isSuspension;

        $model = Package::create($data);

        if ($model) {
            toastr('Salvo!');
            return redirect()->route('panel.packages.index');
        }
        toastr('Tente novamente ou entre em contato com o administrador do sistema!');
        return redirect()->route('panel.packages.index');
    }

    public function delete($id): View
    {
        $package = $this->model->find($id);

        return view('panel.packages.local.index.modals.delete', compact("package"));
    }

    public function destroy($id): RedirectResponse
    {
        $this->model->find($id)->delete();
        toastr('Apagado!');
        return redirect()->route('panel.packages.index');
    }

    public function edit($id): View
    {
        $package = $this->model->find($id);

        return view('panel.packages.local.index.modals.edit', compact("package"));
    }


    public function update($id, PackageRequest $request): RedirectResponse
    {
        $isActive = match ($request->validated()['is_active']) {
            'on' => true,
            '0' => false,
        };

        $isSuspension = match ($request->validated()['is_suspension']) {
            'on' => true,
            '0' => false,
        };

        $data = $request->validated();

        $data['is_active'] = $isActive;
        $data['is_suspension'] = $isSuspension;

        $model = $this->model->find($id)->update($data);

        if ($model) {
            toastr('Salvo!');
            return redirect()->route('panel.packages.index');
        }
        toastr('Tente novamente ou entre em contato com o administrador do sistema!');
        return redirect()->route('panel.packages.index');
    }

    public function toggleActive($id): JsonResponse
    {
        $item = $this->model->find($id);

        if (!$item) {
            return response()->json([
                'status' => 400,
                'errors' => [
                    'message' => ['Os dados não foram encontrados!']
                ],
            ]);
        }

        $item->is_active = $item->is_active ? 0 : 1;
        $item->save();

        return response()->json([
            'status' => 200,
            'is_active' => (int) $item->is_active,
            'message' => $item->is_active ? 'Ativado' : 'Desativado',
        ]);
    }


    /*public function deleteAll(): View
    {
        ds($this->request->all());
        $itens = $this->request->checkeds;

        session()->put('ids', $itens);

        return view('panel.packages.local.index.modals.remove-all', compact("itens"));
    }

    public function destroyAll(): JsonResponse
    {
        dd('destroyall');
        foreach (session()->get('ids') as $item) {
            $item = $this->model->find($item["id"]);

            if ($item) {
                $item->delete();

                if (!$item) {
                    return response()->json([
                        'status' => '400',
                        'errors' => [
                            'message' => ['Erro executar a ação, tente novamente!']
                        ],
                    ]);
                }
            } else {
                return response()->json([
                    'status' => '400',
                    'errors' => [
                        'message' => ['Os dados não foram encontrados!']
                    ],
                ]);
            }
        }

        return response()->json([
            'status' => '200',
            'message' => 'Ação executada com sucesso!'
        ]);
    }*/
}
