<?php

use App\Http\Controllers\ActionController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\AutoFill;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Contact;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\Delivery;
use App\Http\Controllers\EncomendaController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\Flip;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\MediaUploader;
use App\Http\Controllers\NotaFiscalController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PopUpLeadsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TotalExpress;
use App\Http\Controllers\UserController;
use Illuminate\Mail\Markdown;
// use App\Mail\CupomSend;
use Illuminate\Support\Facades\Route;
// use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\File;

// Route::get('pre_cadastrar_users', [PopUpLeadsController::class, 'pre_cadastrar_users'])->name('pop_up_leads.pre_cadastrar');

// Route::get('teste-safra', function() {
//     return view('safrapay.teste-checkout');
// });

Route::get('encomenda-personalizada', function() {
    return view('personal');
})->name('personalizada');

Route::get('sobre-a-callore-aquecedores-de-toalhas', function() {
    return view('sobre-callore');
})->name('about');

Route::get('teste', function() {
    return view('teste');
});

Route::get('contato-callore-aquecedores-de-toalhas', [Contact::class, 'index'])->name('contact.index');
Route::post('contato-callore-aquecedores-de-toalhas', [Contact::class, 'send'])->name('contact.send');
Route::post('personalizar-aquecedores-de-toalhas', [Contact::class, 'personal'])->name('contact.personal');
Route::get('orcamento-enviado', [Contact::class, 'thanks'])->name('contact.personal.thanks');
Route::get('revenda-aquecedores-de-toalhas-callore', [Contact::class, 'revenda'])->name('contact.revenda');

Route::get('unscribe/{email}', [HomeController::class, 'unsubscribe'])->name('user.unsubscribe');

Route::get('teste-markdown', function () {
    $markdown = new Markdown(view(), config('mail.markdown'));

    return $markdown->render('emails.inverno_2025.index', ['id' => 1]);
});

Route::get('/blog', [PostController::class, 'posts'])->name('posts');
Route::get('/blog/{slug}', [PostController::class, 'single'])->name('posts.show');
Route::get('/blog/categoria/{category}', [PostController::class, 'postsByCat'])->name('posts.category');
Route::get('/blog/tag/{tag}', [PostController::class, 'postsByTag'])->name('posts.tag');
Route::get('/autores/{slug}', [AuthorController::class, 'front_show'])->name('authors.single');

// Route::get('mes-consumidor-2024', function () {
//     return view('e-mail-mkt.consumidor-2024');
// });

// Route::get('/email', function () {
//     return new CupomSend([
//         'name' => 'Teste Nome',
//         'phone' => '+5511999999999',
//         'email' => 'teste@teste.com.br',
//     ]);
// });

Route::get('aquecedores-de-toalhas-callore', function () {
    return view('aquecedores-de-toalhas-callore');
})->name('aquecedores-de-toalhas-callore');

// Route::get('regulamento-promocao-inverno-2023-0608-23', function () {
//     return view('regulamento-promocao-0608-23');
// })->name('regulamento-promocao-0608-23');

// Route::get('get-public-key', [PagSeguro::class, 'getPublicKey'])->name('get-public-key');

Route::get('contato-links', function () {
    return view('contacts');
});

// Rotas abertas
Route::post('pop-up-leave', [PopUpLeadsController::class, 'cadastrar'])->name('pop-up-leave');

// Route::get('blog', function () {
//     return view('blog.index');
// })->name('blog');

Route::get('aquecedor-de-toalhas-callore-tem-garantia-weco', function () {
    return view('guaranteed.guaranteed');
})->name('guaranteed');

Route::get('termos', function () {
    return view('guaranteed.terms');
})->name('terms');

Route::get('privacidade', function () {
    return view('guaranteed.privacy');
})->name('privacy');

Route::get('politica-de-entrega', function () {
    return view('guaranteed.delivery-term');
})->name('delivery-term');

Route::get('politica-de-devolucoes', function () {
    return view('guaranteed.return-term');
})->name('return-term');

Route::get('politica-de-privacidade', function () {
    return view('guaranteed.privacy');
})->name('privacity-polices');

Route::get('cupons', function () {
    return 'página de promoções e cupons';
})->name('cupons');

Route::get('/', [HomeController::class, 'index'])->name('home');
// Route::get('/home-video', [HomeController::class, 'homeVideo'])->name('home-video');

// Route::get('notification', [NotificationController::class, 'index'])->name('webhook-tela');

// Route::get('session', [CheckoutController::class, 'generate_session']);

Route::get('/produtos', [ProductController::class, 'list'])->name('products.list');
Route::get('/produto/{slug}', [ProductController::class, 'single'])->name('products.single');

Route::get('/contato', function () {
    return view('contact');
})->name('contact');

Route::get('catalogo', [Flip::class, 'index'])->name('flip');

Route::put('/carts/add/{product}', [CartController::class, 'add'])->name('carts.add');
Route::put('/carts/remove/{product}', [CartController::class, 'remove'])->name('carts.remove');
Route::get('/carts/list', [CartController::class, 'cart_list'])->name('carts.list');
Route::put('/carts/reduce', [CartController::class, 'cart_reduce'])->name('carts.reduce');
Route::put('/carts/plus', [CartController::class, 'cart_plus'])->name('carts.plus');

Route::post('/calcular-frete', [Delivery::class, 'simulateDeliveryAjax'])->name('delivery-calculator');

// Rotas com autenticação
Route::middleware(['auth', 'verified'])->group(function () {
    // Safrapay
    Route::get('pagamento-credito-safra/{order}', [PaymentController::class, 'payCreditSafra'])->name('safra.credit');
    Route::post('processamento-credito-safra', [PaymentController::class, 'processCreditSafra'])->name('safra.credit.process');
    
    Route::get('pagamento-pix-safra/{order}', [PaymentController::class, 'payPixSafra'])->name('safra.pix');
    Route::post('processamento-pix-safra', [PaymentController::class, 'processPixSafra'])->name('safra.pix.process');

    Route::get('novo-qr-code-safra/{order}', [PaymentController::class, 'gerNewCodePix'])->name('safra.pix.reload');
    Route::get('remover-pix-do-pagamento/{order}', [PaymentController::class, 'remPixPay'])->name('safra.pix.remove');
    Route::get('remover-credit-do-pagamento/{order}', [PaymentController::class, 'remCreditPay'])->name('safra.credit.remove');

    Route::get('cancelar-pedido/{order_id}', [OrderController::class, 'pagarmeCancelCharge'])->name('orders.cancel');

    Route::get('manual-de-instrucoes-callore', function () {
        $file = storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'documents' . DIRECTORY_SEPARATOR . 'manual-de-instrucoes-callore-revisao-05.pdf';
        $file = File::get($file);
        $response = Response::make($file, 200);
        $response->header('Content-Type', 'application/pdf');
        return $response;
    })->name('manual');

    Route::post('salvar-nfe', [NotaFiscalController::class, 'saveFile'])->name('nfe.save-file');
    Route::get('etiqueta/{order_id}/{volumes}', [EncomendaController::class, 'printLabelTela'])->name('etiqueta.label.print');

    Route::get('leads', [LeadController::class, 'index'])->name('leads.index');
    Route::get('leads/gerenciar/{id}', [LeadController::class, 'manager'])->name('leads.manager');
    Route::delete('leads/acao/{action}', [LeadController::class, 'remove_action'])->name('leads.action.remove');
    Route::get('leads/acao/pendente/{id}', [LeadController::class, 'action_pending'])->name('leads.action.pending');
    Route::get('leads/acao/feita/{id}', [LeadController::class, 'action_done'])->name('leads.action.done');
    Route::post('leads/acao/atribuir-adicionar', [LeadController::class, 'setActionOnLead'])->name('leads.action.atribuir-adicionar');
    Route::post('leads/note/adicionar', [LeadController::class, 'createNote'])->name('leads.note.add');

    Route::get('autofill/{table}/{search}', [AutoFill::class, 'getList'])->name('autofill');

    Route::resource('notes', NotesController::class);

    Route::prefix('profile')->group(function () {
        Route::get('', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::get('upload-media', [MediaUploader::class, 'index'])->name('uploader.index');
    Route::get('upload-media-by-ajax', [MediaUploader::class, 'byAjax'])->name('uploader.by-ajax');
    Route::post('upload-media-by-ajax', [MediaUploader::class, 'upByAjax'])->name('upload.by-ajax');
    Route::post('cropped-media-by-ajax', [MediaUploader::class, 'cropped'])->name('cropped.by-ajax');
    Route::post('upload-media', [MediaUploader::class, 'upload'])->name('uploader.upload');
    Route::post('upload-pdf', [MediaUploader::class, 'uploadPdf'])->name('uploader.upload-pdf');
    Route::post('upload-image', [MediaUploader::class, 'uploadImage'])->name('uploader.upload-image');

    Route::prefix('orders')->group(function () {
        Route::get('', [OrderController::class, 'list'])->name('orders.list');
        Route::get('/show_order/{order}', [OrderController::class, 'show_order'])->name('orders.show_order');
        Route::get('/cancel/{id}', [OrderController::class, 'cancel'])->name('orders.cancel_order');
        Route::post('/add', [OrderController::class, 'add'])->name('orders.add');
        Route::post('/complete', [OrderController::class, 'complete'])->name('orders.complete');
        Route::post('/update_status', [OrderController::class, 'updateStatus'])->name('orders.up_status');
        Route::post('/add_coupon', [OrderController::class, 'add_coupon'])->name('orders.add_coupon');
        Route::get('change_payment/{order}', [OrderController::class, 'changePayment'])->name('orders.change_payment');
    });

    Route::get('pagamento-credito-pagarme/{order}', [PaymentController::class, 'payCreditPagarme'])->name('pagarme.credit');

    Route::get('pay_pix_pagarme/{order}', [CheckoutController::class, 'pay_pix_pagarme'])->name('pay.pix_pagarme');
    Route::post('pay_credit_card_pagarme', [CheckoutController::class, 'pay_credit_card_pagarme'])->name('pay.credit_card_pagarme');
    Route::post('pay_credit_card', [CheckoutController::class, 'pay_credit_card'])->name('pay.credit_card');
    Route::post('pay_boleto', [CheckoutController::class, 'pay_boleto'])->name('pay.boleto');
    Route::post('pay_pix', [CheckoutController::class, 'pay_pix'])->name('pay.pix');
});

// Rotas com autenticação
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::resource('painel/perfil', ProfileController::class);
});

// Rotas com autenticação de superusuário
Route::middleware([
    'auth', 'role:super', 'verified'
])->group(function () {
    Route::get('exemplo-checkout-safrapay-credit', function() {
        return view('safrapay.exemplo-checkout');
    });
    Route::get('total-express-registra-coleta', [TotalExpress::class, 'registraColeta']);
    Route::get('total-express-previsao', [TotalExpress::class, 'previsao']);

    Route::prefix('painel')->group(function () {
        Route::resource('roles', RoleController::class);

        Route::delete('roles/{role}/permissions/{permission}', [RoleController::class, 'revokePermission'])->name('roles.permissions.revoke');
        Route::post('roles/{role}/permissions', [RoleController::class, 'givePermission'])->name('roles.permissions');

        Route::resource('permissions', PermissionController::class);
    });

    Route::delete('/users/{user}/roles/{role}', [UserController::class, 'removeRole'])->name('users.roles.remove');
    Route::post('/users/{user}/roles', [UserController::class, 'assignRole'])->name('users.roles.add');
});

// Rotas com autenticação de admin ou superusuário
Route::middleware([
    'auth', 'role:admin|super', 'verified'
])->group(function () {

    Route::get('teste-tk', [PaymentController::class, 'testeTk']);

    Route::resource('actions', ActionController::class);

    Route::post('/search', [PostController::class, 'search'])->name('search');

    Route::prefix('painel')->group(function () {
        Route::get('media/nova', [FileUploadController::class, 'index'])->name('dashboard-media-create');
        Route::post('media/store', [FileUploadController::class, 'store'])->name('dashboard-media-store');

        Route::get('publicacoes', [PostController::class, 'index'])->name('dashboard-posts');
        Route::get('publicacoes/lixeira', [PostController::class, 'trash'])->name('dashboard-posts-trash');
        Route::get('publicacoes/nova', [PostController::class, 'create'])->name('dashboard-posts-create');
        Route::post('publicacoes/store', [PostController::class, 'store'])->name('dashboard-posts-store');
        Route::get('publicacoes/{id}', [PostController::class, 'show'])->name('dashboard-posts.show');
        Route::get('publicacoes/edit/{id}', [PostController::class, 'edit'])->name('dashboard-posts-edit');
        Route::post('publicacoes/atualizar', [PostController::class, 'update'])->name('dashboard-posts-update');
        Route::get('publicacoes/delete/{id}', [PostController::class, 'destroy'])->name('dashboard-posts-destroy');
        Route::get('publicacoes/restaurar/{id}', [PostController::class, 'restore'])->name('dashboard-posts-restore');
        Route::get('publicacoes/force-delete/{id}', [PostController::class, 'forceDelete'])->name('dashboard-posts-force-delete');
        // Route::get('publicacoes/autofill/{cat}', [PostController::class, 'autofill'])->name('dashboard-posts-autofill');
    
        Route::get('categorias', [CategoryController::class, 'index'])->name('dashboard-categories');
        Route::get('categorias/nova', [CategoryController::class, 'create'])->name('dashboard-categories-create');
        Route::post('categorias/store', [CategoryController::class, 'store'])->name('dashboard-categories-store');
        Route::get('categorias/{id}', [CategoryController::class, 'show'])->name('dashboard-categories-show');
        Route::get('categorias/edit/{id}', [CategoryController::class, 'edit'])->name('dashboard-categories-edit');
        Route::post('categorias/atualizar', [CategoryController::class, 'update'])->name('dashboard-categories-update');
        Route::get('categorias/delete/{id}', [CategoryController::class, 'destroy'])->name('dashboard-categories-destroy');
        Route::get('categorias/search/{search}', [CategoryController::class, 'search']);
        Route::post('categorias/search/add', [CategoryController::class, 'addBySearch']);
    
        Route::get('tags', [TagController::class, 'index'])->name('dashboard-tags');
        Route::get('tags/nova', [TagController::class, 'create'])->name('dashboard-tags-create');
        Route::post('tags/store', [TagController::class, 'store'])->name('dashboard-tags-store');
        Route::get('tags/{id}', [TagController::class, 'show'])->name('dashboard-tags-show');
        Route::get('tags/edit/{id}', [TagController::class, 'edit'])->name('dashboard-tags-edit');
        Route::post('tags/atualizar', [TagController::class, 'update'])->name('dashboard-tags-update');
        Route::get('tags/delete/{id}', [TagController::class, 'destroy'])->name('dashboard-tags-destroy');
        Route::get('tags/search/{search}', [TagController::class, 'search']);
        Route::post('tags/search/add', [TagController::class, 'addBySearch']);
    
        Route::get('authors', [AuthorController::class, 'index'])->name('dashboard-authors');
        Route::get('authors/nova', [AuthorController::class, 'create'])->name('dashboard-authors-create');
        Route::post('authors/store', [AuthorController::class, 'store'])->name('dashboard-authors-store');
        Route::get('authors/{id}', [AuthorController::class, 'show'])->name('dashboard-authors.single');
        Route::get('authors/edit/{id}', [AuthorController::class, 'edit'])->name('dashboard-authors-edit');
        Route::post('authors/atualizar', [AuthorController::class, 'update'])->name('dashboard-authors-update');
        Route::get('authors/delete/{id}', [AuthorController::class, 'destroy'])->name('dashboard-authors-destroy');
        Route::get('authors/search/{search}', [AuthorController::class, 'search']);
    });


    // Route::get('enviar-email-confirmacao-total', [TestarViewsEmail::class, 'confirmacaoTotal']);
    // Route::get('testar-email/{id}', [TestarViewsEmail::class, 'index']);

    Route::get('download-pdf-teste/{order_id}/{volumes}', [EncomendaController::class, 'printLabel']);

    Route::get('/dashboard', function () {
        return view('dashboard.home');
    })->name('dashboard');

    Route::prefix('painel')->group(function () {
        Route::get('/', function () {
            return view('dashboard.home');
        })->name('painel');

        Route::resource('users', UserController::class);

        Route::resource('products', ProductController::class);
        Route::put('products/restore/{product}', [ProductController::class, 'restore'])->name('products.restore');
        Route::delete('products/delete-trash/{product}', [ProductController::class, 'removeTrash'])->name('products.delete-trash');

        Route::resource('orders', OrderController::class);
        Route::put('orders/restore/{order}', [OrderController::class, 'restore'])->name('orders.restore');

        Route::resource('carts', CartController::class);
        Route::put('carts/restore/{cart}', [CartController::class, 'restore'])->name('carts.restore');

        Route::resource('coupons', CouponController::class);
        Route::put('coupons/restore/{coupon}', [CouponController::class, 'restore'])->name('coupons.restore');

        Route::get('orders/payed/{id}', [OrderController::class, 'payed'])->name('orders.payed');
        Route::post('orders/updelivery', [OrderController::class, 'upDelivery'])->name('orders.updelivery');
        Route::post('orders/storedelivery', [OrderController::class, 'storeDelivery'])->name('orders.storedelivery');
    });
});

require __DIR__ . '/auth.php';
