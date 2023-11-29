<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankController;
use App\Http\Controllers\User\BudgetController;
use App\Http\Controllers\User\ExpensesController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\NewColController;
use App\Http\Controllers\User\BtypesController;
use App\Http\Controllers\User\TypesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\User\EncashmentController;
use App\Models\Stypes;
use App\Models\Ysummary;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Route::get('/home', 'App\Http\Controllers\HomeController@index');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Route::get('home', [App\Http\Controllers\HomeController::class, 'home'])->name('home');
Route::prefix('admin')->middleware(['isAdmin'])->group(function () {


    //pie
    Route::get('/dashboard', [App\Http\Controllers\Admin\ChartController::class, 'index']);

    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

    // Route::get('/admin/reset-password/{user}', 'Admin\PasswordResetController@showResetForm')->name('admin.reset-password');
    // Route::post('/admin/reset-password/{user}', 'Admin\PasswordResetController@resetPassword')->name('admin.reset-password.update');

    Route::get('/admin/reset-password/{user}',  [App\Http\Controllers\Admin\PasswordResetController::class, 'showResetForm'])->name('admin.reset-password');
    Route::post('/admin/reset-password/{user}',  [App\Http\Controllers\Admin\PasswordResetController::class, 'resetPassword'])->name('admin.reset-password.update');


    //calendar
    // Route::post('/save-note', 'NoteController@saveNote');
    Route::post('save-note',  [App\Http\Controllers\Admin\DashboardController::class, 'saveNote'])->name('save-note');
    // Route::get('/get-notes', 'NoteController@getNotes');
    Route::get('get-notes',  [App\Http\Controllers\Admin\DashboardController::class, 'getNotes'])->name('get-notes');
    // web.php (for web routes)
    Route::get('get-username/{id}',  [App\Http\Controllers\Admin\DashboardController::class, 'getNotes'])->name('get-username');

    //items
    Route::get('items', [App\Http\Controllers\Admin\ItemsController::class, 'index']);

    Route::get('add-items', [App\Http\Controllers\Admin\ItemsController::class, 'create']);

    Route::post('add-items', [App\Http\Controllers\Admin\ItemsController::class, 'store']);

    Route::get('edit-items/{items_id}', [App\Http\Controllers\Admin\ItemsController::class, 'edit']);

    Route::put('update-items/{items_id}', [App\Http\Controllers\Admin\ItemsController::class, 'update']);

    Route::get('delete-items/{items_id}', [App\Http\Controllers\Admin\ItemsController::class, 'destroy']);


    //repair
    Route::get('repairs/{repairs_id}', [App\Http\Controllers\Admin\RepairsController::class, 'index']);

    Route::get('repairs', [App\Http\Controllers\Admin\RepairsController::class, 'view']);

    Route::get('add-repairs', [App\Http\Controllers\Admin\RepairsController::class, 'create']);

    Route::post('add-repairs', [App\Http\Controllers\Admin\RepairsController::class, 'store']);

    Route::get('edit-repairs/{repairs_id}', [App\Http\Controllers\Admin\RepairsController::class, 'edit']);

    Route::put('update-repairs/{repairs_id}', [App\Http\Controllers\Admin\RepairsController::class, 'update']);

    Route::get('delete-repairs/{repairs_id}', [App\Http\Controllers\Admin\RepairsController::class, 'destroy']);

    //Route::get('repairs/{repairs_id}', [App\Http\Controllers\Admin\RepairsController::class, 'edit']);



    //users
    Route::get('users', [App\Http\Controllers\Admin\UserController::class, 'index']);
    Route::get('edit-user/{user_id}', [App\Http\Controllers\Admin\UserController::class, 'edit']);
    Route::put('update-user/{user_id}', [App\Http\Controllers\Admin\UserController::class, 'update']);
    Route::get('delete-user/{user_id}', [App\Http\Controllers\Admin\UserController::class, 'destroy']);

    Route::get('/users/{user}/summary', [App\Http\Controllers\Admin\UserController::class, 'showUserSummary'])->name('user.summary');
    Route::get('showUserSummary-users/{user}/{year?}', [App\Http\Controllers\Admin\UserController::class, 'showUserSummary'])
        ->name('admin.user.summary');


    Route::get('/admin/users/{id}/summary', [App\Http\Controllers\Admin\UserController::class, 'userSummary'])->name('admin.users.summary');

    //approve
    Route::get('reports', [App\Http\Controllers\Admin\ApproveController::class, 'approve']);
    Route::post('approve-summary/{id}', [App\Http\Controllers\Admin\ApproveController::class, 'approveSummary']);
    //viewing of the approved summary
    Route::get('appreports', [App\Http\Controllers\Admin\ApproveController::class, 'viewapp']);
    Route::post('reverse-approval/{id}', [App\Http\Controllers\Admin\ApproveController::class, 'reverseApproval']);

    // In your routes/web.php file
    Route::get('/summary/show/{user}/{year}/{month}', [App\Http\Controllers\Admin\ApproveController::class, 'show'])->name('summary.show');

    //    Route::get('/summary/show/{user}/{year}/{month}', 'SummaryController@show')->name('summary.show');

    //Route::post('/reverse-approval/{id}', [SummaryController::class, 'reverseApproval']);
    //Route::post('/approve-summary/{id}', [SummaryController::class, 'approveSummary']);
});







//user side
Route::prefix('user')->middleware(['auth', 'isUser'])->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
    Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

    //calendar
    // Route::post('/save-note', 'NoteController@saveNote');
    Route::post('save-note',  [App\Http\Controllers\User\DashboardController::class, 'saveNote'])->name('save-note');
    // Route::get('/get-notes', 'NoteController@getNotes');
    Route::get('get-notes',  [App\Http\Controllers\User\DashboardController::class, 'getNotes'])->name('get-notes');
    // web.php (for web routes)
    Route::get('get-username/{id}',  [App\Http\Controllers\User\DashboardController::class, 'getNotes'])->name('get-username');



    //password
    // Route::get('/change-password', 'Auth\ChangePasswordController@showChangeForm')->name('password.change');
    // Route::post('/change-password', 'Auth\ChangePasswordController@changePassword');

    Route::get('change-password', [App\Http\Controllers\User\ChangePasswordController::class, 'showChangeForm'])->name('password.change');


    Route::post('change-password', [App\Http\Controllers\User\ChangePasswordController::class, 'changePassword'])->name('password.change');




    //items
    Route::get('items', [App\Http\Controllers\User\ItemsController::class, 'index']);

    Route::get('add-items', [App\Http\Controllers\User\ItemsController::class, 'create']);

    Route::post('add-items', [App\Http\Controllers\User\ItemsController::class, 'store']);

    Route::get('edit-items/{items_id}', [App\Http\Controllers\User\ItemsController::class, 'edit']);

    Route::put('update-items/{items_id}', [App\Http\Controllers\User\ItemsController::class, 'update']);

    Route::get('delete-items/{items_id}', [App\Http\Controllers\User\ItemsController::class, 'destroy']);


    //repair
    Route::get('repairs/{repairs_id}', [App\Http\Controllers\User\RepairsController::class, 'index']);

    Route::get('repairs', [App\Http\Controllers\User\RepairsController::class, 'view']);

    Route::get('add-repairs', [App\Http\Controllers\User\RepairsController::class, 'create']);

    Route::post('add-repairs', [App\Http\Controllers\User\RepairsController::class, 'store']);

    Route::get('edit-repairs/{repairs_id}', [App\Http\Controllers\User\RepairsController::class, 'edit']);

    Route::put('update-repairs/{repairs_id}', [App\Http\Controllers\User\RepairsController::class, 'update']);

    Route::get('delete-repairs/{repairs_id}', [App\Http\Controllers\User\RepairsController::class, 'destroy']);

    //expenses
    Route::get('expenses', [App\Http\Controllers\User\ExpensesController::class, 'index']);

    Route::get('add-expenses', [App\Http\Controllers\User\ExpensesController::class, 'create'])->name('add-expenses');

    Route::post('add-expenses', [App\Http\Controllers\User\ExpensesController::class, 'store']);

    Route::get('edit-expenses/{expenses_id}', [App\Http\Controllers\User\ExpensesController::class, 'edit']);

    Route::put('update-expenses/{expenses_id}', [App\Http\Controllers\User\ExpensesController::class, 'update']);

    Route::delete('delete-expenses/{expenses_id}', [App\Http\Controllers\User\ExpensesController::class, 'destroy'])->name('delete-expenses');;

    Route::get('/expenses/create', [ExpensesController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [ExpensesController::class, 'store'])->name('expenses.store');
    Route::put('/expenses/{id}', [ExpensesController::class, 'update'])->name('expenses.update');








    //summary
    // Route::get('summary', [App\Http\Controllers\User\SummaryController::class, 'index']);

    Route::post('submit-summary/{id}',  [App\Http\Controllers\User\SummaryController::class, 'submitSummary'])->name('summary.submit');

    // Route::post('updateSubmittedStatus/{itemId}', [App\Http\Controllers\User\SummaryController::class, 'updateSubmittedStatus']);

    // Route::post('/updateSubmittedStatus/{itemId}', 'YourController@updateSubmittedStatus');


    Route::get('summary/{year?}', [App\Http\Controllers\User\SummaryController::class, 'index'])->name('summary.index');

    Route::get('add-summary', [App\Http\Controllers\User\SummaryController::class, 'create']);

    Route::post('add-summary', [App\Http\Controllers\User\BudgetController::class, 'store']);

    Route::get('edit-addbudget/{summary}', [App\Http\Controllers\User\SummaryController::class, 'edit']);

    Route::put('update-summary/{summary}', [App\Http\Controllers\User\SummaryController::class, 'update']);

    Route::get('delete-summary/{summary}', [App\Http\Controllers\User\SummaryController::class, 'destroy']);


    //budget
    // routes/web.php

    Route::get('budget', [BudgetController::class, 'index']);
    Route::get('/budget/create', [BudgetController::class, 'create'])->name('budget.create');
    Route::post('/budget/store', [BudgetController::class, 'store'])->name('budget.store');




    Route::get('edit-budget/{id}', [App\Http\Controllers\User\BudgetController::class, 'edit'])->name('edit-budget');

    Route::put('update-budget/{id}', [App\Http\Controllers\User\BudgetController::class, 'update'])->name('update-budget');

    Route::delete('delete-budget/{id}', [App\Http\Controllers\User\BudgetController::class, 'destroy'])->name('delete-budget');





    //additional budget

    Route::get('add-summary1', [App\Http\Controllers\User\SummaryController::class, 'make']);

    Route::post('add-summary1', [App\Http\Controllers\User\BudgetController::class, 'additional']);


    Route::get('/budget/make', [BudgetController::class, 'create'])->name('budget.make');

    Route::post('/budget/additional', [BudgetController::class, 'store'])->name('budget.additional');


    //late encashment

    Route::get('lexpenses', [App\Http\Controllers\User\LexpensesController::class, 'index']);

    Route::get('add-lexpenses', [App\Http\Controllers\User\LexpensesController::class, 'create'])->name('add-lexpenses');

    Route::post('add-lexpenses', [App\Http\Controllers\User\LexpensesController::class, 'store'])->name('add-lexpenses');

    Route::get('edit-lexpenses/{lexpenses_id}', [App\Http\Controllers\User\LexpensesController::class, 'edit']);

    Route::put('update-lexpenses/{lexpenses_id}', [App\Http\Controllers\User\LexpensesController::class, 'update']);

    Route::get('delete-lexpenses/{lexpenses_id}', [App\Http\Controllers\User\LexpensesController::class, 'destroy']);


    //adding the late encashment from late table to current table

    Route::post('add-to-expenses/{lexpenses_id}', [App\Http\Controllers\User\LexpensesController::class, 'addToExpenses'])->name('add-to-expenses');


    Route::get('check-is-added/{lexpensesId}', [App\Http\Controllers\User\LexpensesController::class, 'checkIsAddedStatus'])->name('check-is-added');






    //add column

    Route::get('/add-column', [NewColController::class, 'showForm'])->name('addColumnForm');
    Route::post('/add-column', [NewColController::class, 'addColumn'])->name('addColumn');


    //add budget  types
    Route::get('btypes', [App\Http\Controllers\User\BtypesController::class, 'index']);

    Route::get('add-btypes', [App\Http\Controllers\User\BtypesController::class, 'create']);

    Route::post('add-btypes', [App\Http\Controllers\User\BtypesController::class, 'store']);

    Route::get('edit-btypes/{btypes}', [App\Http\Controllers\User\BtypesController::class, 'edit']);

    Route::put('update-btypes/{btypes}', [App\Http\Controllers\User\BtypesController::class, 'update']);

    Route::delete('delete-btypes/{btypes}', [App\Http\Controllers\User\BtypesController::class, 'destroy']);

    Route::get('/btypes', [BtypesController::class, 'index'])->name('btypes.index');


    //add expenses types
    Route::get('types', [App\Http\Controllers\User\TypesController::class, 'index']);

    Route::get('add-types', [App\Http\Controllers\User\TypesController::class, 'create']);

    Route::post('add-types', [App\Http\Controllers\User\TypesController::class, 'store']);

    Route::get('edit-types/{types}', [App\Http\Controllers\User\TypesController::class, 'edit']);

    Route::put('update-types/{types}', [App\Http\Controllers\User\TypesController::class, 'update']);

    Route::delete('delete-types/{types}', [App\Http\Controllers\User\TypesController::class, 'destroy']);


    Route::get('/types', [TypesController::class, 'index'])->name('types.index');


    //stypes
    Route::get('stypes/{stypes_id}', [App\Http\Controllers\User\StypesController::class, 'index']);

    Route::get('stypes', [App\Http\Controllers\User\StypesController::class, 'view']);

    Route::get('add-stypes', [App\Http\Controllers\User\StypesController::class, 'create']);

    Route::post('add-stypes', [App\Http\Controllers\User\StypesController::class, 'store']);

    Route::get('edit-stypes/{stypes}', [App\Http\Controllers\User\StypesController::class, 'edit']);

    Route::put('user/stypes/{id}', [App\Http\Controllers\User\StypesController::class, 'update'])->name('stypes.update');

    Route::delete('delete-stypes/{types_id}/{subtype_id}', [App\Http\Controllers\User\StypesController::class, 'destroy']);



    //getting the subtypes in the add expense form
    Route::post('/get-stypes',  [ExpensesController::class, 'getStypes'])->name('get-stypes');
    Route::get('/get-stypes', 'ExpensesControllerName@getStypes')->name('get-stypes');



    //bstypes
    Route::get('bstypes/{btypes_id}', [App\Http\Controllers\User\BstypesController::class, 'index']);

    Route::get('bstypes', [App\Http\Controllers\User\BstypesController::class, 'view']);

    Route::get('add-bstypes', [App\Http\Controllers\User\BstypesController::class, 'create']);

    Route::post('add-bstypes', [App\Http\Controllers\User\BstypesController::class, 'store']);

    Route::get('edit-bstypes/{bstypes}', [App\Http\Controllers\User\BstypesController::class, 'edit']);

    Route::put('update-bstypes/{bstypes}', [App\Http\Controllers\User\BstypesController::class, 'update'])->name('bstypes.update');

    Route::delete('delete-bstypes/{btypes_id}/{subtype_id}', [App\Http\Controllers\User\BstypesController::class, 'destroy']);

    Route::post('/get-bstypes',  [BudgetController::class, 'getBstypes'])->name('get-bstypes');





    //yearly summary
    // Yearly summary


    Route::get('ysummary/{year?}', [App\Http\Controllers\User\YsummaryController::class, 'index'])->name('ysummary.index');

    // Add yearly summary form
    Route::get('add-ysummary', [App\Http\Controllers\User\YsummaryController::class, 'create']);

    // Store yearly summary and budgets
    Route::post('add-ysummary', [App\Http\Controllers\User\YsummaryController::class, 'store']); // Changed to BudgetController

    // Edit yearly summary
    Route::get('edit-ysummary/{summary}', [App\Http\Controllers\User\SummaryController::class, 'edit']);

    // Update yearly summary
    Route::put('update-ysummary/{summary}', [App\Http\Controllers\User\SummaryController::class, 'update']);

    // Delete yearly summary
    Route::get('delete-ysummary/{summary}', [App\Http\Controllers\User\SummaryController::class, 'destroy']);

    Route::post('ysummary', [App\Http\Controllers\User\YsummaryController::class, 'store'])->name('ysummary.store');
    //ybudget
    // routes/web.php

    Route::get('/ybudget/create', [BudgetController::class, 'create'])->name('ybudget.create');
    Route::post('/ybudget/store', [BudgetController::class, 'store'])->name('ybudget.store');



    //bank
    Route::get('bank', [App\Http\Controllers\User\BankController::class, 'index']);

    Route::get('add-bank', [App\Http\Controllers\User\BankController::class, 'create'])->name('add-bank');

    Route::post('add-bank', [App\Http\Controllers\User\BankController::class, 'store'])->name('add-bank');

    Route::get('edit-bank/{bank_id}', [App\Http\Controllers\User\BankController::class, 'edit'])->name('edit-bank');

    Route::put('update-bank/{bank_id}', [App\Http\Controllers\User\BankController::class, 'update'])->name('update-bank');

    Route::delete('delete-bank/{bank_id}', [App\Http\Controllers\User\BankController::class, 'destroy'])->name('delete-bank');
});
