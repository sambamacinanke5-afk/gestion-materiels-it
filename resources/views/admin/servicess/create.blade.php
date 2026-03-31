@extends('layouts.app')

@section('content')
    <style>
        .admin-page {
            padding: 24px;
            background: #f8fafc;
            min-height: 100vh;
        }

        .page-header {
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
        }

        .card-clean {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        }

        .card-clean-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            font-weight: 100;
            margin-bottom: 5px;
            color: #374151;
        }

        .form-control-clean {
            width: 100%;
            padding: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            outline: none;
        }

        .form-control-clean:focus {
            border-color: #2563eb;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-primary-clean {
            background: #2563eb;
            color: #fff;
            padding: 10px 16px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-primary-clean:hover {
            background: #1d4ed8;
        }

        .btn-delete {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px 16px;
            border-radius: 10px;
            text-decoration: none;
        }

        .error-box {
            margin-bottom: 15px;
            color: red;
        }
    </style>

    <div class="admin-page">

        <!-- Header -->
        <div class="page-header">
            <h1 class="page-title">Ajouter un Service</h1>
        </div>

        <!-- Erreurs -->
        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulaire -->
        <div class="card-clean">
            <div class="card-clean-body">

                <form action="{{ route('service.store') }}" method="POST">
                    @csrf

                    <!-- Nom -->
                    <div class="form-group">
                        <label class="form-label">Nom du service</label>
                        <input type="text" name="nom" value="{{ old('nom') }}" class="form-control-clean" required>
                    </div>

                    // Site
                    <div class="form-group">
                        <label class="form-label">Site</label>
                        <select name="site_id" class="form-control-clean">
                            <option value="">-- Choisir un site (optionnel) --</option>
                            @foreach($sites as $site)
                                <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>
                                    {{ $site->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="actions">
                        <a href="{{ route('service.index') }}" class="btn-delete">
                            Retour
                        </a>

                        <button type="submit" class="btn-primary-clean">
                            Enregistrer
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
