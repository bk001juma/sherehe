<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\EventGalleryController;
use App\Http\Controllers\Event\EventController as FromWebRouteEventController;
use App\Http\Controllers\API\EventItemController;
use App\Http\Controllers\API\ItemTypeController;
use App\Http\Controllers\API\PledgeCategoryController;
use App\Http\Controllers\API\PledgeController;
use App\Http\Controllers\API\PledgePaymentController;
use App\Http\Controllers\Event\NotificationController;
use App\Http\Controllers\QrCode\QrCodeController;
use App\Http\Controllers\Url\UrlController;
use App\Http\Controllers\WhatsApp\WhatsAppController;
use Illuminate\Support\Facades\Route;


Route::prefix('event')->middleware(['auth:sanctum', 'level:1'])->group(function () {
    Route::post('/create_category', [EventController::class, 'StoreEventCategory']);
    Route::post('/create_event', [EventController::class, 'StoreEvent']);

    Route::get('/{id}/pledge-groups', [EventController::class, 'getPledgeGroups']);
    Route::post('/{id}/send-sms', [NotificationController::class, 'sendSMS']);
    Route::post('/{id}/send-news/image', [NotificationController::class, 'sendWhatssapSMSShareNews']);
    Route::post('/{id}/send-news/sms', [NotificationController::class, 'sendWhatssapSMS']);

    //Invitation cards and tickets
    Route::get('/ticket/sendInvitationTicket/{pledgeId}', [FromWebRouteEventController::class, 'sendInvitationTicket']);
    Route::get('/card/sendInvitationCard/{pledgeId}', [FromWebRouteEventController::class, 'sendInvitationCard']);
    Route::get('/card/sendInvitationCardByName/{pledgeId}', [FromWebRouteEventController::class, 'sendInvitationCardByCardName']);
    Route::get('/card/sendInvitationCardByLink/{pledgeId}', [FromWebRouteEventController::class, 'sendInvitationCardByCardLink']);

    // Bulk send invitation cards/tickets to all pledgers
    Route::get('/card/sendInvitationCard/{eventId}/all', [FromWebRouteEventController::class, 'sendInvitationCardToAll']);
    Route::get('/card/sendInvitationCard/{eventId}/all/name', [FromWebRouteEventController::class, 'sendInvitationCardToAllName']);
    Route::get('/card/sendInvitationCard/{eventId}/all/link', [FromWebRouteEventController::class, 'sendInvitationCardToAllLink']);
    Route::get('/ticket/sendInvitation/{eventId}/all/paid/tickets', [FromWebRouteEventController::class, 'sendInvitationButtonToAllPaidTicket']);

    //Get to day event
    Route::get('/today', [EventController::class, 'todayGetEvent']);







    Route::get('/my_events', [EventController::class, 'MyEvents']);
    Route::post('/card/verifyPledge', [QrCodeController::class, 'verifyPledge']);
    Route::post('/card/pledge', [QrCodeController::class, 'pledge1']);
    Route::post('/card/pledge-user', [QrCodeController::class, 'pledgeForUser']);
    Route::get('/search-pledges-by-event', [EventController::class, 'searchPledgesByEvent']);

    Route::prefix('pledge')->group(function () {
        Route::post('/add', [PledgeController::class, 'addPledge']);         // Create
        Route::get('/list/{eventId}', [PledgeController::class, 'listPledges']);       // Read all
        Route::get('/{id}', [PledgeController::class, 'getPledge']);         // Read one
        Route::post('/update/{id}', [PledgeController::class, 'updatePledge']); // Update
        Route::post('/delete/{id}', [PledgeController::class, 'deletePledge']); // Delete

        Route::post('{id}/payment', [PledgePaymentController::class, 'addPayment']);       // Add payment
        Route::post('payment/{paymentId}', [PledgePaymentController::class, 'updatePayment']); // Update payment
        Route::get('{id}/payments', [PledgePaymentController::class, 'listPayments']);
    });

    Route::prefix('pledge-categories')->group(function () {
        Route::post('/', [PledgeCategoryController::class, 'store']);         // Create
        Route::get('/{eventId}', [PledgeCategoryController::class, 'index']);          // List all
        Route::get('{id}', [PledgeCategoryController::class, 'show']);        // Get single
        Route::post('{id}', [PledgeCategoryController::class, 'update']);      // Update
        Route::post('{id}', [PledgeCategoryController::class, 'destroy']);  // Delete
    });

    Route::prefix('event-items')->group(function () {
        Route::get('/{event_id}', [EventItemController::class, 'index']);       // List by event_id
        Route::post('/', [EventItemController::class, 'store']);                // Create
        Route::post('/{id}', [EventItemController::class, 'update']);            // Update
        Route::post('/{id}', [EventItemController::class, 'destroy']);        // Delete
    });

    Route::prefix('event-gallery')->group(function () {
        Route::post('/store', [EventGalleryController::class, 'store']); // Save image/link
        Route::get('/{event_id}', [EventGalleryController::class, 'index']); // Get all for event
    });
});

Route::post('/send-pdf', [WhatsAppController::class, 'sendPdf']);
Route::post('/whatsapp-template-test', [WhatsAppController::class, 'sendTestWhatsAppMessage']);

Route::post('/shorten', [UrlController::class, 'shorten']);
// Route::post('/insert-attendees', [UrlController::class, 'insertAttendees']);

Route::get('/search-pledges', [EventController::class, 'searchPledges']);
Route::get('/download-images', [EventController::class, 'downloadAllImages']);
Route::get('/download-design-cards', [EventController::class, 'downloadAllDesignCards']);


Route::post('/test-send-image', [EventController::class, 'testSendImage']);


Route::get('/event/packages', [EventController::class, 'Packages']);
Route::get('/event/categories', [EventController::class, 'categories']);
Route::get('/item-types', [ItemTypeController::class, 'index']);

Route::post('/upload-image', [EventGalleryController::class, 'uploadImage']);

Route::post('/test-send-message', [EventController::class, 'testSendMessage']);


