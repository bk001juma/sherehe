<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Event\AttendeeController;
use App\Http\Controllers\Event\AttendeeResponseController;
use App\Http\Controllers\Event\BulkSMSController;
use App\Http\Controllers\Event\CardController;
use App\Http\Controllers\Event\EventController;
use App\Http\Controllers\Event\EventItemController;
use App\Http\Controllers\Event\EventTabController;
use App\Http\Controllers\Event\NotificationController;
use App\Http\Controllers\Event\PaymentController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\QrCode\QrCodeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Url\UrlController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WhatsApp\WhatsAppController;
use Illuminate\Support\Facades\Route;


Route::prefix('home')->middleware(['auth', 'activated', 'currentUser', 'activity', 'twostep', 'checkblocked'])->group(function () {

    Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('dashboard');


    Route::prefix('events')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('dash.events');
        Route::get('/all', [EventController::class, 'index'])->name('dash.events.all');

        Route::get('/crete/{selected_package}', [EventController::class, 'create'])->name('dash.create_event');
        Route::post('/crete/store',             [EventController::class, 'store'])->name('dash.store_event');

        Route::get('/orders/check/{order_id}',  [EventController::class, 'order'])->name('dash.event.order');
        Route::get('/orders/activate/{order_id}',  [EventController::class, 'activateEvent'])->name('dash.event.activate');

        //        Event Management
        Route::get('/{id}/manage',              [EventController::class, 'show'])->name('dash.event');
        Route::post('/{id}/update',              [EventController::class, 'update'])->name('dash.event.update');
        Route::get('/{id}/delete',              [EventController::class, 'destroy'])->name('dash.event.delete');

        Route::post('/item/add',                [EventItemController::class, 'addItem'])->name('dash.event.add_item');
        Route::post('/item/{id}/update',             [EventItemController::class, 'updateItem'])->name('dash.event.item_update');
        Route::get('/item/{id}/delete',           [EventItemController::class, 'destroyItem'])->name('dash.event.item_destroy');
        Route::post('/item/add/payment',                [EventItemController::class, 'addPayment'])->name('dash.event.add.item.payment');

        Route::post('/pledge/add',                [AttendeeController::class, 'addPledge'])->name('dash.event.add_pledge');
        Route::post('/pledge/add/category',                [AttendeeController::class, 'addPledgeCategory'])->name('dash.event.add_pledge_category');

        Route::post('/pledge/{id}/update',                [AttendeeController::class, 'update'])->name('dash.event.pledge.update');
        Route::post('/pledge/category/{id}/update',                [AttendeeController::class, 'updateCategory'])->name('dash.event.pledge.category.update');

        Route::post('/pledge/{id}/payment',                [AttendeeController::class, 'addPayment'])->name('dash.event.pledge.pay');
        Route::get('/pledge/{id}/destroy',                [AttendeeController::class, 'destroy'])->name('dash.event.pledge.destroy');
        Route::get('/pledge/{id}/category/destroy',                [AttendeeController::class, 'destroyCategory'])->name('dash.event.pledge.category.destroy');


        Route::post('/card/type/{id}/update',                [EventController::class, 'updateCard'])->name('dash.event.card_type.update');
        Route::post('/card/{id}/upload_design',                [EventController::class, 'uploadCardDesign'])->name('dash.event.card_type.upload_design');
        Route::post('/{id}/upload_welcome_note',                [EventController::class, 'uploadWelcomeNote'])->name('dash.event.upload_welcome_note');


        Route::post('/card/sendCard', [EventController::class, 'sendCard'])->name('dash.event.card.send');
        Route::post('/card/sendCard/Link', [EventController::class, 'sendCardLink'])->name('dash.event.card.send.link');
        Route::post('/card/sendTicketName', [EventController::class, 'sendTicketName'])->name('dash.event.ticket.send.name');


        Route::get('/card/sendInvitationCard/{pledgeId}', [EventController::class, 'sendInvitationCard'])->name('dash.event.card.send.invitation');
        Route::get('/card/sendInvitationCard/{eventId}/all', [EventController::class, 'sendInvitationCardToAll'])->name('dash.event.card.send.invitation.all');

        Route::get('/card/sendInvitationCardByName/{pledgeId}', [EventController::class, 'sendInvitationCardByCardName'])->name('dash.event.card.send.invitation.by.name');
        Route::get('/card/sendInvitationCard/{eventId}/all/name', [EventController::class, 'sendInvitationCardToAllName'])->name('dash.event.card.send.invitation.all.name');

        Route::get('/card/sendInvitationCardByLink/{pledgeId}', [EventController::class, 'sendInvitationCardByCardLink'])->name('dash.event.card.send.invitation.by.link');
        Route::get('/card/sendInvitationCard/{eventId}/all/link', [EventController::class, 'sendInvitationCardToAllLink'])->name('dash.event.card.send.invitation.all.link');

        Route::get('/ticket/sendInvitationTicket/{pledgeId}', [EventController::class, 'sendInvitationTicket'])->name('dash.event.ticket.send.invitation');
        Route::get('/ticket/sendInvitation/{eventId}/all/paid/tickets', [EventController::class, 'sendInvitationButtonToAllPaidTicket'])->name('dash.event.ticket.send.invitation.all.paid');

        // Delete All Pledges
        Route::get('/delete/all/pledges/{eventId}', [EventController::class, 'deleteAllPledges'])->name('dash.event.delete.all.pledges');





        //        Cards
        Route::get('/card/show',                [CardController::class, 'image'])->name('dash.card.show');

        //  Bulk SMS
        Route::post('/sms/purchase',                        [BulkSMSController::class, 'createOrder'])->name('sms.purchase');
        Route::get('/sms/purchase/{id}/order',              [BulkSMSController::class, 'orderStatus'])->name('sms.purchase.order');
        Route::get('/sms/purchase/{id}/order/activate',     [BulkSMSController::class, 'orderActivate'])->name('sms.purchase.activate');

        // WhatsApp
        Route::post('/whatsaPP/sms/purchase',   [BulkSMSController::class, 'createWhatsAppOrderSMS'])->name('whatsapp.purchase');
        Route::get('/whatsaPP/sms/purchase/{id}/order',              [BulkSMSController::class, 'orderWhatsAppStatus'])->name('whatsapp.purchase.order');
        Route::get('/whatsaPP/sms/purchase/{id}/order/activate',     [BulkSMSController::class, 'orderWhatsAppActivate'])->name('whatsapp.purchase.activate');


        //        Notifications
        Route::post('/notification/{id}/create/sms',        [NotificationController::class, 'createSMS'])->name('dash.notification.sms.create');
        Route::get('/notification/{id}/create/sms1',        [NotificationController::class, 'createSMS1'])->name('dash.notification.sms.create1');

        Route::post('/notification/{id}/create/whatsAppsms',        [NotificationController::class, 'createWhatsAppSMS'])->name('dash.notification.whatsAppsms.create');
        Route::get('/notification/{id}/create/whatsAppsms',        [NotificationController::class, 'createWhatsAppSMS1'])->name('dash.notification.whatsAppsms.create1');
        Route::get('/notification/{id}/create/whatsAppsms/sendImage',        [NotificationController::class, 'shareNews'])->name('dash.notification.whatsAppsms.share.news');
        Route::get('/event/{id}/video/upload',        [NotificationController::class, 'videoUpload'])->name('dash.video.upload');
        Route::post('/event/{id}/video/upload',           [NotificationController::class, 'sendVideotoUpload'])->name('dash.video.upload.send');




        Route::get('/notification/{id}/show/sms',           [NotificationController::class, 'showNotificationSMS'])->name('dash.notification.sms.show');
        Route::post('/notification/{id}/sens/sms',           [NotificationController::class, 'sendSMS'])->name('dash.notification.sms.send');

        Route::get('/notification/{id}/show/whassap/sms',           [NotificationController::class, 'showNotificationWhassapSMS'])->name('dash.notification.whassap.show');
        Route::post('/notification/{id}/send/whassap/sms',           [NotificationController::class, 'sendWhatssapSMS'])->name('dash.notification.whassap.send');
        Route::post('/notification/{id}/send/whassap/share/news',           [NotificationController::class, 'sendWhatssapSMSShareNews'])->name('dash.notification.whassap.send.share.news');

        // Event Tab Management
        Route::get('/{id}/items',              [EventTabController::class, 'items'])->name('dash.event.items');
        Route::get('/{id}/pledger/categories',              [EventTabController::class, 'pledgerCategories'])->name('dash.event.pledger.categories');
        Route::get('/{id}/pledges',              [EventTabController::class, 'pledges'])->name('dash.event.pledges');
        Route::get('/{id}/pledges/name',              [EventTabController::class, 'pledgesName'])->name('dash.event.pledges.name');
        Route::get('/{id}/pledges/link',              [EventTabController::class, 'pledgesLink'])->name('dash.event.pledges.link');
        Route::get('/{id}/cards',              [EventTabController::class, 'cards'])->name('dash.event.cards');
        Route::get('/{id}/paid/tickets',              [EventTabController::class, 'paidTickets'])->name('dash.event.paid.tickets');
        Route::get('/{id}/tickets',              [EventTabController::class, 'tickets'])->name('dash.event.tickets');
        Route::get('/{id}/sms/notifications',              [EventTabController::class, 'smsNotifications'])->name('dash.event.sms.notifications');
        Route::get('/{id}/whatsApp/notifications',              [EventTabController::class, 'whatsappNotifications'])->name('dash.event.whatsapp.notifications');
        Route::get('/{id}/card/name/position',              [EventTabController::class, 'cardNamePosition'])->name('card.name.position');
        Route::get('/{id}/card/link/position',              [EventTabController::class, 'cardLinkPosition'])->name('card.link.position');
        Route::get('/{id}/ticket/name/position',              [EventTabController::class, 'ticketNamePosition'])->name('ticket.name.position');

        Route::get('/{id}/report',              [EventTabController::class, 'report'])->name('dash.event.report');
        Route::post('/{id}/report/filter',              [EventTabController::class, 'reportFilter'])->name('dash.event.report.filter');
    });

    Route::get('/business/order/is_paid/{id}', [PaymentController::class, 'isPaid'])->name('is_order_paid');
    Route::prefix('transactions')->group(function () {
        Route::get('/',              [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/events',              [TransactionController::class, 'index'])->name('transactions.events');
        Route::get('/sms',              [TransactionController::class, 'sms'])->name('transactions.sms');

        Route::post('/event/payment/initial', [TransactionController::class, 'storeInitial'])->name('event.payment.initial');
        Route::post('/event/payment/final', [TransactionController::class, 'storeFinal'])->name('event.payment.final');
    });

    // Users Managements
    Route::prefix('users')->group(function () {
        Route::get('/',              [UserController::class, 'users'])->name('users.index');
        Route::post('/update',              [UserController::class, 'updateUser'])->name('users.update');
        // Route::get('/sms',              [TransactionController::class, 'sms'])->name('transactions.sms');

        // Route::post('/event/payment/initial', [TransactionController::class, 'storeInitial'])->name('event.payment.initial');
        // Route::post('/event/payment/final', [TransactionController::class, 'storeFinal'])->name('event.payment.final');
    });
});

Route::prefix('qr')->group(function () {
    Route::get('/card/event', [QrCodeController::class, 'index'])->name('qr_card');
    Route::get('/card/event/pledge', [QrCodeController::class, 'pledge'])->name('qr_pledge')->middleware('auth:api');
});

Route::get('/card/single/pledge/qrcode/{id}', [EventController::class, 'pledgeQrCode'])->name('dash.event.card.single_pledge.qrcode');

Route::get('/card/show/update/{id}',                [EventController::class, 'getCard'])->name('dash.event.card.show');
Route::get('/card/download/{id}', [EventController::class, 'downloadCard'])->name('dash.event.card.download');

Route::get('/card/show/update1/{id}',                [EventController::class, 'getCard1'])->name('dash.event.card.show1');
// Route::get('/card/download1/{id}', [EventController::class, 'downloadCard1'])->name('dash.event.card.download1');

Route::get('/card/show/update2/{id}',                [EventController::class, 'getCard2'])->name('dash.event.card.show2');
// Route::get('/card/download2/{id}', [EventController::class, 'downloadCard2'])->name('dash.event.card.download2');

Route::get('/card/show/update3/{id}',                [EventController::class, 'getCard3'])->name('dash.event.card.show3');
// Route::get('/card/download3/{id}', [EventController::class, 'downloadCard3'])->name('dash.event.card.download3');

Route::get('/{shortCode}', [UrlController::class, 'redirect']);

Route::get('/attendee/response/{event_id}/{attendee_id}', [AttendeeResponseController::class, 'showResponseForm'])
    ->name('attendee.response.form');

// Route::post('/attendee/response/submit', [AttendeeResponseController::class, 'submitResponse'])
//     ->name('attendee.response.submit');

Route::get('/attendee/response/submit/{event_id}/{attendee_id}', [AttendeeResponseController::class, 'submitResponse'])->name('attendee.response.submit');

Route::get('/attend_yes/{attendee_id}', [AttendeeResponseController::class, 'attendYes'])->name('attendee.response.yes');
Route::get('/attend_no/{attendee_id}', [AttendeeResponseController::class, 'attendNo'])->name('attendee.response.no');


// https://sherehe.co.tz/attend_yes/{{16}}} attendee_id
// https://sherehe.co.tz/attend_no/{{16}}} attendee_id
