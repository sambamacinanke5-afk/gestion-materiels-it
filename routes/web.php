<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\Admin\FournisseurController;
use App\Http\Controllers\Admin\BondelivraisonController;
use App\Http\Controllers\User\BondelivraisonController as UserBondelivraisonController;
use App\Http\Controllers\User\DeploiementController as UserDeploiementController;
use App\Http\Controllers\User\RepartitionController as UserRepartitionController;
use App\Http\Controllers\User\LigneDeploiementController as UserLigneDeploiementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\MarqueController;
use App\Http\Controllers\Admin\TypeMaterielController;
use App\Http\Controllers\Admin\LigneBondelivraisonController;
use App\Http\Controllers\Admin\DeploiementController;
use App\Http\Controllers\Admin\LigneDeploiementController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\RepartitionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\User\LigneBondelivraisonController as UserLigneBondelivraisonController;
use App\Http\Controllers\User\LignedeploiementController as ControllersUserLignedeploiementController;
use App\Http\Controllers\User\UserDashboardController;
use App\Models\LigneDeploiement;
use App\Models\User;
use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| ROUTES PUBLIQUES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/profil', [ProfileController::class, 'edit'])->name('admin.profile.edit');

    Route::prefix('fournisseur')->group(function () {
        Route::get('/', [FournisseurController::class, 'index'])->name('fournisseurs.index');
        Route::get('/create', [FournisseurController::class, 'create'])->name('fournisseurs.create');
        Route::post('/create/add', [FournisseurController::class, 'store'])->name('fournisseurs.store');
        Route::get('/edit/{id}', [FournisseurController::class, 'edit'])->name('fournisseurs.edit');
      Route::put('/update/{id}', [FournisseurController::class, 'update'])->name('fournisseurs.update');
        Route::delete('/destroy/{id}', [FournisseurController::class, 'destroy'])->name('fournisseurs.destroy');
    });
    Route::prefix('bondelivraison')->group(function () {
        Route::get('/', [BondelivraisonController::class, 'index'])->name('bondelivraison.index');
        Route::get('/create', [BondelivraisonController::class, 'create'])->name('bondelivraison.create');
        Route::post('/create/add', [BondelivraisonController::class, 'store'])->name('bondelivraison.store');
        Route::get('/edit/{id}', [BondelivraisonController::class, 'edit'])->name('bondelivraison.edit');
        Route::post('/update/{id}', [BondelivraisonController::class, 'update'])->name('bondelivraison.update');
        Route::delete('/destroy/{id}', [BondelivraisonController::class, 'destroy'])->name('bondelivraison.destroy');
        Route::get('/show/{id}', [BondelivraisonController::class, 'show'])->name('bondelivraison.show');
    });
    Route::prefix('marque')->group(function () {
        Route::get('/', [MarqueController::class, 'index'])->name('marque.index');
        Route::get('/create', [MarqueController::class, 'create'])->name('marque.create');
        Route::post('/create/add', [MarqueController::class, 'store'])->name('marque.store');
        Route::get('/edit/{id}', [MarqueController::class, 'edit'])->name('marque.edit');
         Route::put('/update/{id}', [MarqueController::class, 'update'])->name('marque.update');
        Route::delete('/destroy/{id}', [MarqueController::class, 'destroy'])->name('marque.destroy');
    });
    Route::prefix('gestionmateriel')->group(function () {
        Route::get('/', [TypeMaterielController::class, 'index'])->name('gestionmateriel.index');
        Route::get('/create', [TypeMaterielController::class, 'create'])->name('gestionmateriel.create');
        Route::post('/create/add', [TypeMaterielController::class, 'store'])->name('gestionmateriel.store');
        Route::get('/edit/{id}', [TypeMaterielController::class, 'edit'])->name('gestionmateriel.edit');
        Route::post('/update/{id}', [TypeMaterielController::class, 'update'])->name('gestionmateriel.update');
        Route::delete('/destroy/{id}', [TypeMaterielController::class, 'destroy'])->name('gestionmateriel.destroy');
    });
    Route::prefix('gestiondeploiement')->group(function () {
        Route::get('/', [LigneDeploiementController::class, 'index'])
            ->name('gestiondeploiement.index');
        Route::get('/create', [LigneDeploiementController::class, 'create'])->name('gestiondeploiement.create');
        Route::post('/store', [LigneDeploiementController::class, 'store'])->name('gestiondeploiement.store');
        Route::get('/edit/{id}', [LigneDeploiementController::class, 'edit'])->name('gestiondeploiement.edit');
        Route::post('/update/{id}', [LigneDeploiementController::class, 'update'])->name('gestiondeploiement.update');
        Route::delete('/destroy/{id}', [LigneDeploiementController::class, 'destroy'])->name('gestiondeploiement.destroy');
    });

    Route::prefix('deploiement')->group(function () {
        Route::get('/', [DeploiementController::class, 'index'])->name('deploiement.index');
        Route::get('/create', [DeploiementController::class, 'create'])->name('deploiement.create');
        Route::post('/create/add', [DeploiementController::class, 'store'])->name('deploiement.store');
        Route::get('/edit/{id}', [DeploiementController::class, 'edit'])->name('deploiement.edit');
        Route::post('/deploiement/update/{id}', [DeploiementController::class, 'update'])->name('deploiement.update');
        Route::delete('/destroy/{id}', [DeploiementController::class, 'destroy'])->name('deploiement.destroy');
        Route::get('/show/{id}', [DeploiementController::class, 'show'])->name('deploiement.show');
    });
    Route::prefix('service')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('service.index');
        Route::get('/create', [ServiceController::class, 'create'])->name('service.create');
        Route::post('/create/add', [ServiceController::class, 'store'])->name('service.store');
        Route::get('/edit/{id}', [ServiceController::class, 'edit'])->name('service.edit');
        Route::put('/update/{id}', [ServiceController::class, 'update'])->name('service.update');
        Route::delete('/destroy/{id}', [ServiceController::class, 'destroy'])->name('service.destroy');
        Route::get('/show/{id}', [ServiceController::class, 'show'])->name('service.show');
    });
    Route::prefix('repartition')->group(function () {
        Route::get('/', [RepartitionController::class, 'index'])->name('repartition.index');
        Route::get('/create', [RepartitionController::class, 'create'])->name('repartition.create');
        Route::post('/create/add', [RepartitionController::class, 'store'])->name('repartition.store');
        Route::get('/edit/{id}', [RepartitionController::class, 'edit'])->name('repartition.edit');
        Route::post('/update/{id}', [RepartitionController::class, 'update'])->name('repartition.update');
        Route::delete('/destroy/{id}', [RepartitionController::class, 'destroy'])->name('repartition.destroy');
        Route::get('/show/{id}', [RepartitionController::class, 'show'])->name('repartition.show');
        // Marquer une notification comme lue (individuelle)
        Route::post('/admin/repartition/notification/{id}/read', [RepartitionController::class, 'asRead'])->name('admin.repartition.asRead');
    });


    Route::middleware('auth')->group(function () {
        Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profil/mot-de-passe', [ProfileController::class, 'password'])->name('profile.password');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/pdf/bl/{id}', [PdfController::class, 'bonLivraison'])->name('pdf.bl');
        Route::get('/pdf/repartition/{id}', [PdfController::class, 'repartition'])->name('pdf.repartition');
        Route::get('/pdf/deploiement/{id}', [PdfController::class, 'deploiement'])->name('pdf.deploiement');
    });

    //USER
    Route::prefix('user')->name('user.')->middleware(['auth'])->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('create', [UserController::class, 'create'])->name('create');
        Route::post('create/add', [UserController::class, 'store'])->name('store');
        Route::get('edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::delete('destroy/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::PUT('/update/{id}', [UserController::class, 'update'])->name('update');
        // ⚠️ POST pour toggleStatus
        Route::post('toggle-status/{id}', [UserController::class, 'toggleStatus'])->name('toggleStatus');
    });
});
/*
|--------------------------------------------------------------------------
| USER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        // Notifications
        // Notifications
        Route::post('/notifications/read/{id}', [NotificationController::class, 'read'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll');
        Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
        Route::get('/test-mail', function () {
            Mail::raw('Test email BMS OK', function ($message) {
                $message->to('kmalamine444@gmail.com')
                    ->subject('Test Gmail SMTP');
            });
            return 'Mail envoyé';
        });
        // ================================
        //       BON DE LIVRAISON USER
        // ================================
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

        // ================================
        //          DEPLOIEMENT USER
        // ================================
        Route::prefix('deploiement')->name('deploiement.')->group(function () {
            Route::get('/', [UserDeploiementController::class, 'index'])->name('index');
            Route::get('/create', [UserDeploiementController::class, 'create'])->name('create');
            Route::post('/store', [UserDeploiementController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [UserDeploiementController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [UserDeploiementController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [UserDeploiementController::class, 'destroy'])->name('destroy');
            Route::get('/show/{id}', [UserDeploiementController::class, 'show'])->name('show');
        });

        // ================================
        //      GESTION DEPLOIEMENT USER
        // ================================
        Route::prefix('gestiondeploiement')->name('gestiondeploiement.')->group(function () {
            Route::get('/', [ControllersUserLignedeploiementController::class, 'index'])->name('index');
            Route::get('/create', [ControllersUserLignedeploiementController::class, 'create'])->name('create');
            Route::post('/store', [ControllersUserLignedeploiementController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [ControllersUserLignedeploiementController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [ControllersUserLignedeploiementController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [ControllersUserLignedeploiementController::class, 'destroy'])->name('destroy');
        });

        // ================================
        //          REPARTITION USER
        // ================================
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
