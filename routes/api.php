<?php

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Api\PenerimaanApiController;
    use App\Http\Controllers\Api\PengeluaranApiController;

    Route::middleware('api')->group(function () {
        Route::get('/penerimaan', [PenerimaanApiController::class, 'index'])->name('api.penerimaan');;
        Route::post('/penerimaan', [PenerimaanApiController::class, 'store']);
        Route::put('/penerimaan/{id}', [PenerimaanApiController::class, 'update']);
        Route::delete('/penerimaan/{id}', [PenerimaanApiController::class, 'destroy']);

        Route::get('/pengeluaran', [PengeluaranApiController::class, 'index'])->name('api.pengeluaran');
        Route::post('/pengeluaran', [PengeluaranApiController::class, 'store']);
        Route::put('/pengeluaran/{id}', [PengeluaranApiController::class, 'update']);
        Route::delete('/pengeluaran/{id}', [PengeluaranApiController::class, 'destroy']);
    });

?>