<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PdfController;

// Nouveau controller unique pour les dashboards
use App\Http\Controllers\DashboardController;
//Controller pour les Menus
use App\Http\Controllers\Admin\MenuController;
//Controller pour les rôles
use App\Http\Controllers\Admin\RoleController;
//Controller pour les Permissions
use App\Http\Controllers\Admin\PermissionController;

// Admin
use App\Http\Controllers\Admin\FournisseurController;
use App\Http\Controllers\Admin\BondelivraisonController;
use App\Http\Controllers\Admin\MarqueController;
use App\Http\Controllers\Admin\TypeMaterielController;
use App\Http\Controllers\Admin\LigneBondelivraisonController;
use App\Http\Controllers\Admin\DeploiementController;
use App\Http\Controllers\Admin\LigneDeploiementController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\RepartitionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\MaterielController;

// User
use App\Http\Controllers\User\BondelivraisonController as UserBondelivraisonController;
use App\Http\Controllers\User\DeploiementController as UserDeploiementController;
use App\Http\Controllers\User\RepartitionController as UserRepartitionController;
use App\Http\Controllers\User\LignedeploiementController as UserLignedeploiementController;
use App\Http\Controllers\User\NotificationController;

/*
|--------------------------------------------------------------------------
| ROUTES PUBLIQUES
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('auth.login'));
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ROUTES AUTHENTIFIEES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARDS SEPARES PAR ROLE
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'redirect'])
        ->name('dashboard');

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/it', [DashboardController::class, 'it'])
            ->name('it')
            ->middleware('role:it');

        Route::get('/mg', [DashboardController::class, 'mg'])
            ->name('mg')
            ->middleware('role:mg');

        Route::get('/admin', [DashboardController::class, 'admin'])
            ->name('admin')
            ->middleware('role:admin');

        Route::get('/audit', [DashboardController::class, 'audit'])
            ->name('audit')
            ->middleware('role:audit');
    });

    /*
   |--------------------------------------------------------------------------
   | Gestions des Menus
   |--------------------------------------------------------------------------
   */
    Route::middleware(['auth'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::resource('menus', MenuController::class);
        });


    /*
     |--------------------------------------------------------------------------
     | Gestions des Rôles
     |--------------------------------------------------------------------------
     */

    Route::middleware(['auth'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::resource('roles', RoleController::class);
        });

    /*
    |--------------------------------------------------------------------------
    | Gestions des Permissions
    |--------------------------------------------------------------------------
    */

    Route::middleware(['auth'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::resource('permissions', PermissionController::class);
        });
    /*
    |--------------------------------------------------------------------------
    | Gestions des Users
    |--------------------------------------------------------------------------
    */

    Route::middleware(['auth'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::resource('users', UserController::class);
        });

    /*
    |--------------------------------------------------------------------------
    |Routes custums pour la suspension et le reset de password
    |--------------------------------------------------------------------------
    */

    Route::middleware(['auth'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::resource('users', UserController::class);

            Route::patch('users/{user}/suspend', [UserController::class, 'suspend'])->name('users.suspend');
            Route::patch('users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
            Route::post('users/{user}/send-password-reset', [UserController::class, 'sendPasswordReset'])->name('users.send-password-reset');
        });

    /*
    |--------------------------------------------------------------------------
    | PROFIL
    |--------------------------------------------------------------------------
    */
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/mot-de-passe', [ProfileController::class, 'password'])->name('profile.password');

    /*
    |--------------------------------------------------------------------------
    | PDF
    |--------------------------------------------------------------------------
    */
    Route::get('/pdf/bl/{id}', [PdfController::class, 'bonLivraison'])->name('pdf.bl');
    Route::get('/pdf/repartition/{id}', [PdfController::class, 'repartition'])->name('pdf.repartition');
    Route::get('/pdf/deploiement/{id}', [PdfController::class, 'deploiement'])->name('pdf.deploiement');

    /*
    |--------------------------------------------------------------------------
    | ADMIN / REFERENTIELS
    |--------------------------------------------------------------------------
    */
    Route::prefix('fournisseurs')->name('fournisseurs.')->middleware('role:admin|it')->group(function () {
        Route::get('/', [FournisseurController::class, 'index'])->name('index');
        Route::get('/create', [FournisseurController::class, 'create'])->name('create');
        Route::post('/', [FournisseurController::class, 'store'])->name('store'); // <--- POST sur '/'
        Route::get('/{id}/edit', [FournisseurController::class, 'edit'])->name('edit');
        Route::put('/{id}', [FournisseurController::class, 'update'])->name('update');
        Route::delete('/{id}', [FournisseurController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [FournisseurController::class, 'show'])->name('show');
    });

    Route::prefix('bondelivraison')->name('bondelivraison.')->middleware('role:admin|it')->group(function () {
        Route::get('/', [BondelivraisonController::class, 'index'])->name('index');
        Route::get('/create', [BondelivraisonController::class, 'create'])->name('create');
        Route::post('/create/add', [BondelivraisonController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [BondelivraisonController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [BondelivraisonController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [BondelivraisonController::class, 'destroy'])->name('destroy');
        Route::get('/show/{id}', [BondelivraisonController::class, 'show'])->name('show');
    });

    Route::prefix('marque')->name('marque.')->middleware('role:admin|it')->group(function () {
        Route::get('/', [MarqueController::class, 'index'])->name('index');
        Route::get('/create', [MarqueController::class, 'create'])->name('create');
        Route::post('/create/add', [MarqueController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MarqueController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [MarqueController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [MarqueController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('gestionmateriel')->name('gestionmateriel.')->middleware('role:admin|it')->group(function () {
        Route::get('/', [TypeMaterielController::class, 'index'])->name('index');
        Route::get('/create', [TypeMaterielController::class, 'create'])->name('create');
        Route::post('/create/add', [TypeMaterielController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [TypeMaterielController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [TypeMaterielController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [TypeMaterielController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('materiels')->name('materiels.')->middleware('role:admin|it')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MaterielController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\MaterielController::class, 'create'])->name('create');
            Route::post('/store', [\App\Http\Controllers\Admin\MaterielController::class, 'store'])->name('store');
            Route::get('/edit/{materiel}', [\App\Http\Controllers\Admin\MaterielController::class, 'edit'])->name('edit');
            Route::put('/update/{materiel}', [\App\Http\Controllers\Admin\MaterielController::class, 'update'])->name('update');
            Route::delete('/destroy/{materiel}', [\App\Http\Controllers\Admin\MaterielController::class, 'destroy'])->name('destroy');
        });



    Route::prefix('service')->name('service.')->middleware('role:admin|it')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::get('/create', [ServiceController::class, 'create'])->name('create');
        Route::post('/create/add', [ServiceController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ServiceController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [ServiceController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [ServiceController::class, 'destroy'])->name('destroy');
        Route::get('/show/{id}', [ServiceController::class, 'show'])->name('show');
    });

    /*
    |--------------------------------------------------------------------------
    | IT
    |--------------------------------------------------------------------------
    */
    Route::prefix('deploiement')->name('deploiement.')->middleware('role:admin|it')->group(function () {
        Route::get('/', [DeploiementController::class, 'index'])->name('index');
        Route::get('/create', [DeploiementController::class, 'create'])->name('create');
        Route::post('/create/add', [DeploiementController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [DeploiementController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [DeploiementController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [DeploiementController::class, 'destroy'])->name('destroy');
        Route::get('/show/{id}', [DeploiementController::class, 'show'])->name('show');
    });

    Route::prefix('gestiondeploiement')->name('gestiondeploiement.')->middleware('role:admin|it')->group(function () {
        Route::get('/', [LigneDeploiementController::class, 'index'])->name('index');
        Route::get('/create', [LigneDeploiementController::class, 'create'])->name('create');
        Route::post('/store', [LigneDeploiementController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [LigneDeploiementController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [LigneDeploiementController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [LigneDeploiementController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | MG / REPARTITION
    |--------------------------------------------------------------------------
    */
    Route::prefix('repartition')->name('repartition.')->middleware('role:admin|mg')->group(function () {
        Route::get('/', [RepartitionController::class, 'index'])->name('index');
        Route::get('/create', [RepartitionController::class, 'create'])->name('create');
        Route::post('/create/add', [RepartitionController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [RepartitionController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [RepartitionController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [RepartitionController::class, 'destroy'])->name('destroy');
        Route::get('/show/{id}', [RepartitionController::class, 'show'])->name('show');
        Route::post('/notification/{id}/read', [RepartitionController::class, 'asRead'])->name('asRead');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN / GESTION DES UTILISATEURS
    |--------------------------------------------------------------------------
    */
    Route::prefix('user')->name('user.')->middleware('role:admin')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/create/add', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/toggle-status/{id}', [UserController::class, 'toggleStatus'])->name('toggleStatus');
    });

    /*
    |--------------------------------------------------------------------------
    | ROUTES USER EXISTANTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('user')->name('user.')->group(function () {
        Route::post('/notifications/read/{id}', [NotificationController::class, 'read'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll');
        Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');

        Route::get('/test-mail', function () {
            Mail::raw('Test email BMS OK', function ($message) {
                $message->to('kmalamine444@gmail.com')->subject('Test Gmail SMTP');
            });
            return 'Mail envoyé';
        });

        Route::prefix('bondelivraison')->name('bondelivraison.')->group(function () {
            Route::get('/', [UserBondelivraisonController::class, 'index'])->name('index');
            Route::get('/create', [UserBondelivraisonController::class, 'create'])->name('create');
            Route::post('/store', [UserBondelivraisonController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [UserBondelivraisonController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [UserBondelivraisonController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [UserBondelivraisonController::class, 'destroy'])->name('destroy');
            Route::get('/show/{id}', [UserBondelivraisonController::class, 'show'])->name('show');
            Route::patch('/status/{id}', [UserBondelivraisonController::class, 'changeStatus'])->name('status');
            Route::post('/asRead/{id}', [UserBondelivraisonController::class, 'asRead'])->name('asRead');
        });

        Route::prefix('deploiement')->name('deploiement.')->group(function () {
            Route::get('/', [UserDeploiementController::class, 'index'])->name('index');
            Route::get('/create', [UserDeploiementController::class, 'create'])->name('create');
            Route::post('/store', [UserDeploiementController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [UserDeploiementController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [UserDeploiementController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [UserDeploiementController::class, 'destroy'])->name('destroy');
            Route::get('/show/{id}', [UserDeploiementController::class, 'show'])->name('show');
        });

        Route::prefix('gestiondeploiement')->name('gestiondeploiement.')->group(function () {
            Route::get('/', [UserLignedeploiementController::class, 'index'])->name('index');
            Route::get('/create', [UserLignedeploiementController::class, 'create'])->name('create');
            Route::post('/store', [UserLignedeploiementController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [UserLignedeploiementController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [UserLignedeploiementController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [UserLignedeploiementController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('repartition')->name('repartition.')->group(function () {
            Route::get('/', [UserRepartitionController::class, 'index'])->name('index');
            Route::get('/create', [UserRepartitionController::class, 'create'])->name('create');
            Route::post('/store', [UserRepartitionController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [UserRepartitionController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [UserRepartitionController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [UserRepartitionController::class, 'destroy'])->name('destroy');
            Route::get('/show/{id}', [UserRepartitionController::class, 'show'])->name('show');
            Route::get('/{id}/pdf', [UserRepartitionController::class, 'pdf'])->name('pdf');
        });
    });
});
