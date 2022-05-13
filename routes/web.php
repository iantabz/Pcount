<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\ExportsController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PhysicalCountController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SetupController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', [AppController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AppController::class, 'dashboard'])->name('dashboard');

    Route::prefix('setup')->group(function () {
        Route::prefix('location')->group(function () {
            Route::get('/getResults', [SetupController::class, 'getResultsLocation']);
            Route::post('/toggleStatus', [SetupController::class, 'toggleStatusLocation']);
            Route::post('/createLocation', [SetupController::class, 'createLocation']);
            Route::get('generateLocation', [SetupController::class, 'generateLocation']);
            Route::get('/getCompany', [LocationController::class, 'getCompany']);
            Route::get('/getBU', [LocationController::class, 'getBU']);
            Route::get('/getDept', [LocationController::class, 'getDept']);
            Route::get('/getSection', [LocationController::class, 'getSection']);
            Route::get('/getRacks', [SetupController::class, 'getRacks']);
            Route::post('/createRack', [SetupController::class, 'createRack']);
        });

        Route::prefix('users')->group(function () {
            Route::get('/getResults', [SetupController::class, 'getResultsUser']);
            Route::post('/toggleStatus', [SetupController::class, 'toggleStatusUser']);
            Route::post('/createUser', [SetupController::class, 'createUser']);
            Route::get('/getUsertypes', [SetupController::class, 'getUsertypes']);
        });

        Route::prefix('masterfiles')->group(function () {
            Route::get('/getVendorMasterfile', [SetupController::class, 'getVendorMasterfile']);
            Route::get('/getItemCategoryMasterfile', [SetupController::class, 'getItemCategoryMasterfile']);
        });
    });

    Route::prefix('employee')->group(function () {
        Route::get('/search', [SetupController::class, 'searchEmployee']);
    });

    Route::prefix('uploading')->group(function () {
        Route::prefix('nav_upload')->group(function () {
            Route::post('/navPcount', [FileUploadController::class, 'navPcount']);
            Route::get('/getCompany', [FileUploadController::class, 'getCompany']);
            Route::get('/getBU', [FileUploadController::class, 'getBU']);
            Route::get('/getDept', [FileUploadController::class, 'getDept']);
            Route::get('/getSection', [FileUploadController::class, 'getSection']);
            Route::get('/getVendor', [FileUploadController::class, 'getVendor']);
            Route::get('/getCategory', [FileUploadController::class, 'getCategory']);
        });

        Route::prefix('masterfiles')->group(function () {
            Route::post('/importItemMasterfile', [FileUploadController::class, 'importItemMasterfile']);
            Route::post('/importVendorMasterfile', [FileUploadController::class, 'importVendorMasterfile']);
            Route::post('/importItemCategoryMasterfile', [FileUploadController::class, 'importItemCategoryMasterfile']);
        });
    });

    Route::prefix('reports')->group(function () {
        Route::prefix('appdata')->group(function () {
            Route::get('/getResults', [PhysicalCountController::class, 'getResults']);
            Route::post('/generate', [PhysicalCountController::class, 'generate']);
            Route::get('/generateAppDataExcel', [PhysicalCountController::class, 'generateAppDataExcel']);
            Route::get('/getNotFound', [PhysicalCountController::class, 'getNotFound']);
            Route::get('/generateNotFound', [PhysicalCountController::class, 'generateNotFound']);
        });
        Route::prefix('damageCount')->group(function () {
            // Route::get('/getResults', [PhysicalCountController::class, 'getResults']);
            Route::get('/generateCountDamages', [PhysicalCountController::class, 'generateCountDamages']);
            Route::get('/generateCountDamagesEXCEL', [PhysicalCountController::class, 'generateCountDamagesEXCEL']);
        });
        Route::prefix('pcount_cost')->group(function () {
            Route::get('/getResultPcountCost', [ReportsController::class, 'getResultPcountCost']);
            Route::get('/generatePcountCost', [ReportsController::class, 'generatePcountCost']);
            Route::get('/generatePcountCostExcel', [ReportsController::class, 'generatePcountCostExcel']);
        });
        Route::prefix('variance_report')->group(function () {
            Route::get('/getResults', [ReportsController::class, 'getResultsVariance']);
            Route::get('/generate', [ReportsController::class, 'generateVariance']);
            Route::post('/export', [ExportsController::class, 'exportVariance']);
        });
        Route::prefix('variance_report_cost')->group(function () {
            Route::get('/getResultVarianceCost', [ReportsController::class, 'getResultVarianceCost']);
            Route::get('/generateVarianceReportCost', [ReportsController::class, 'generateVarianceReportCost']);
        });
        Route::prefix('consolidate_report')->group(function () {
            Route::get('/getResults', [ReportsController::class, 'getResultsConsolidateReport']);
            Route::get('/generate', [ReportsController::class, 'generateConsolidateReport']);
        });
        Route::prefix('consolidation_nav')->group(function () {
            Route::get('/getResults', [ReportsController::class, 'getResults']);
            Route::get('/generate', [ReportsController::class, 'generate']);
        });
    });
});

Route::get('delete-duplicates', function () {
    set_time_limit(0);
    ini_set('memory_limit', '-1');
    // $result = DB::statement("SELECT COUNT( item_code ) AS item_count, item_code FROM tbl_item_masterfile GROUP BY item_code HAVING item_count > 1");
    $result = DB::table('tbl_item_masterfile')->selectRaw("COUNT( item_code ) AS item_count, item_code")->groupBy('item_code')->having('item_count', '>', 1)->get();
    // DB::beginTransaction();
    foreach ($result as $key => $item_masterfile) {
        $result2 = DB::table('tbl_item_masterfile')->where('item_code', $item_masterfile->item_code);

        $result2->where('id', '!=', $result2->max('id'))->delete();

        // dd($query->get(), $result2->get());
    }

    // DB::rollBack();
    return response()->json(['message' => 'Database operation success']);
});




require __DIR__ . '/auth.php';

Route::middleware('auth')->get('{any}', [AppController::class, 'allowWhat'])->where('any', '.*');
