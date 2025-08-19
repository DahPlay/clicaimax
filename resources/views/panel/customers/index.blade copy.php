@extends("$routeAmbient.template.index")

@section('title', (auth()->user()->access && auth()->user()->access->name === 'User') ? 'Minha Conta' : ($title ?? config('custom.project_name')))
 {{-- Título da aba do navegador --}}

@section('content')
    <div class="content-wrapper">
        @include("$routeAmbient.$routeCrud.breadcrumb")

        {{-- Título principal da página 
        <div class="p-3">
            <h4 class="mb-0">
                @if(auth()->user()->access && auth()->user()->access->name === 'User')
                    Minha Conta
                @else
                    {{$titleBreadCrumb}}
                @endif
            </h4>
        </div>--}}

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="table" class="table table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="width: 1%"></th>
                                            <th><input type="checkbox" id="btn-marcar-todos"></th>
                                            <th>#</th>
                                            <th>Nome</th>
                                            <th>Cliente Asaas</th>
                                            <th>Cliente {{ config('custom.project_name') }}</th>
                                            <th>Login</th>
                                            <th>E-mail</th>
                                            <th>Documento</th>
                                            <th>Criação</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@includeIf("$routeAmbient.$routeCrud.local.index.head")
@includeIf("$routeAmbient.$routeCrud.local.index.javascript")