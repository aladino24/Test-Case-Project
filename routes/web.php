<?php

use App\Http\Controllers\Apps\DashboardController;
use App\Http\Controllers\Apps\ProjectSettingController;
use App\Http\Controllers\Apps\ProductController;
use App\Http\Controllers\Apps\RoleController;
use App\Http\Controllers\Apps\UserController;
use App\Http\Controllers\Apps\PenjualanController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\SessionToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use RealRashid\SweetAlert\Facades\Alert;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  return view('welcome');
});

// Start Authentication
Route::get("/login", [LoginController::class, "index"])->name("login");
Route::post("/login", [LoginController::class, "store"])->name("login.store");

Route::get("/logout", function () {
  Auth::logout();
  Alert::success("Sukses!", "Berhasil logout!");
  SessionToken::query()->where("session_token", session("session_token"))->update(["is_login" => 0]);

  return redirect()->route("login");
});
// End Authentication

Route::middleware(["auth", "check_maintanance", "check_session_token"])->group(function () {
  Route::get("/app/dashboard", DashboardController::class)->name("app.dashboard")->middleware("check_authorized:002D");

  Route::post("/app/users/get", [UserController::class, "get"])->name("app.users.get")->middleware("check_authorized:003U");
  Route::resource("/app/users", UserController::class, ["as" => "app"])->middleware("check_authorized:003U|004R");

  Route::resource("/app/roles", RoleController::class, ["as" => "app"])->middleware("check_authorized:004R");
  Route::post("/app/roles/{role}/assign-user", [RoleController::class, "assign_user"])->name("app.roles.assign_user")->middleware("check_authorized:004R");

  Route::get("/app/product/create", [ProductController::class, "create"])->name("app.products.create")->middleware("check_authorized:005PS");
  Route::post("/app/product/store", [ProductController::class, "store"])->name("app.products.store")->middleware("check_authorized:005PS");
  Route::get("/app/product", [ProductController::class, "index"])->name("app.products.index")->middleware("check_authorized:005PS");
  Route::get("/app/product/{id}", [ProductController::class, "show"])->name("app.product.show")->middleware("check_authorized:005PS");
  Route::delete("/app/product/{id}", [ProductController::class, "destroy"])->name("app.product.destroy")->middleware("check_authorized:005PS");
  Route::get("/app/product/{id}/edit", [ProductController::class, "edit"])->name("app.product.edit")->middleware("check_authorized:005PS");
  Route::put("/app/product/{id}", [ProductController::class, "update"])->name("app.products.update")->middleware("check_authorized:005PS");
  Route::post("/app/product/get", [ProductController::class, "get"])->name("app.products.get")->middleware("check_authorized:005PS");

  Route::get("/app/transaksi", [PenjualanController::class, "index"])->name("app.transaksi.index")->middleware("check_authorized:005PS");
  Route::post("/app/transaksi/get-product", [PenjualanController::class, "getProduct"])->name("app.transaksi.getproduct")->middleware("check_authorized:005PS");
  Route::post("/app/transaksi/store", [PenjualanController::class, "order"])->name("app.transaksi.store")->middleware("check_authorized:005PS");
  Route::get("/app/riwayat-transaksi", [PenjualanController::class, "detail"])->name("app.transaksi.get")->middleware("check_authorized:005PS");
  Route::post("/app/riwayat-transaksi/list", [PenjualanController::class, "list"])->name("app.transaksi.list")->middleware("check_authorized:005PS");
  Route::get('/app/riwayat-transaksi/generate-pdf/{id}', [PenjualanController::class, 'generatePDF'])->name('generate-pdf')->middleware("check_authorized:005PS");



  Route::get("/app/settings", [ProjectSettingController::class, "index"])->name("app.settings.index")->middleware("check_authorized:005S");
  Route::put("/app/settings", [ProjectSettingController::class, "update"])->name("app.settings.update")->middleware("check_authorized:005S");
});

Route::get("/maintenance", function() {
    auth()->logout();
    return view("admin.maintenance");
})->name("maintenance")->middleware("check_maintanance");
