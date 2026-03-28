@extends('layouts.app')

@section('content')
    <style>
        .admin-page {
            padding: 24px;
            background: #f8fafc;
            min-height: 100vh;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
        }

        .btn-secondary-clean {
            background: #e5e7eb;
            color: #111827;
            padding: 10px 16px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-secondary-clean:hover {
            background: #d1d5db;
        }

        .card-clean {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            max-width: 600px;
        }

        .card-clean-body {
            padding: 24px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label-clean {
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
            color: #374151;
        }

        .form-control-clean {
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        .form-control-clean:focus {
            border-color: #2563eb;
            box-shadow: 0 0 5px rgba(37, 99, 235, 0.3);
        }

        .actions-bar {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-success-clean {
            background: #16a34a;
            color: #fff;
            padding: 10px 14px;
            border-radius: 10px;
            border: none;
        }

        .btn-warning-clean {
            background: #facc15;
            color: #111827;
            padding: 10px 14px;
            border-radius: 10px;
            border: none;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 15px;
        }
    </style>

    <div class="admin-page">

        <!-- Header -->
        <div class="page-header">
            <h1 class="page-title">Ajouter une marque</h1>

            <a href="{{ route('marque.index') }}" class="btn-secondary-clean">
                ← Retour
            </a>
        </div>

        <!-- Erreurs -->
        @if ($errors->any())
            <div class="alert-error">
                <ul style="margin:0; padding-left:15px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Card -->
        <div class="card-clean">
            <div class="card-clean-body">
                <form action="{{ url('marque/create/add') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label-clean">
                            Nom de la marque <span style="color:red">*</span>
                        </label>

                        <input type="text" name="Designation" class="form-control-clean"
                            placeholder="Ex: HP, Dell, Lenovo..." required>
                    </div>

                    <!-- Actions -->
                    <div class="actions-bar">
                        <button type="reset" class="btn-warning-clean">
                            Réinitialiser
                        </button>

                        <button type="submit" class="btn-success-clean">
                            Enregistrer
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
