<?php
use App\Http\Controllers\AdminDash;
use App\Http\Controllers\AtcBookingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ControllerDash;
use App\Http\Controllers\DiscordController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ImpersonationController;
use App\Http\Controllers\MerchStore;
use App\Http\Controllers\PilotPassportController;
use App\Http\Controllers\RealopsController;
use App\Http\Controllers\RosterController;
use App\Http\Controllers\TrainingDash;
use Illuminate\Support\Facades\Route;

/*
*   Front Page Stuff
*/
Route::get('/', [FrontController::class, 'home']);
Route::get('/controllers/teamspeak', [FrontController::class, 'teamspeak']);
Route::get('/privacy', [FrontController::class, 'privacy']);
Route::view('/privacy-embed', 'site.privacy_policy');
Route::get('/controllers/stats/{year?}/{month?}', [FrontController::class, 'showStats']);
Route::get('/visit', [FrontController::class, 'visit']);
Route::post('/visit/save', [FrontController::class, 'storeVisit'])->name('storeVisit');
Route::get('/pilots/airports', [FrontController::class, 'airportIndex']);
Route::get('/pilots/airports/view/{id}', [FrontController::class, 'showAirport']);
Route::get('/pilots/scenery', [FrontController::class, 'sceneryIndex']);
Route::get('/pilots/scenery/view/{id}', [FrontController::class, 'showScenery']);
Route::post('/pilots/scenery/search', [FrontController::class, 'searchScenery']);
Route::get('/pilots/request-staffing', [FrontController::class, 'showStaffRequest']);
Route::post('/pilots/request-staffing', [FrontController::class, 'staffRequest'])->name('staffRequest');
Route::get('/pilots/guide/atl', [FrontController::class, 'pilotGuideAtl']);
Route::get('/feedback/new', [FrontController::class, 'newFeedback']);
Route::get('/feedback/new/{slug}', [FrontController::class, 'newFeedback']);
Route::post('/feedback/new', [FrontController::class, 'saveNewFeedback'])->name('saveNewFeedback');
Route::get('/trainer_feedback/new', [FrontController::class, 'newTrainerFeedback']);
Route::post('/trainer_feedback/new', [TrainingDash::class, 'saveNewTrainerFeedback'])->name('saveNewTrainerFeedback');
Route::get('controllers/files', [FrontController::class, 'showFiles']);
Route::get('/ramp-status/atl', [FrontController::class, 'showAtlRamp']);
Route::get('/ramp-status/clt', [FrontController::class, 'showCltRamp']);
Route::get('/asset/{slug}', [FrontController::class, 'showPermalink']);
Route::get('/live', [FrontController::class, 'showLiveEventInfo']);

Route::prefix('realops')->middleware('toggle:realops')->group(function () {
    Route::get('/', [RealopsController::class, 'index'])->name('realopsIndex');
    Route::get('/login', [LoginController::class, 'realopsLogin'])->middleware('guest:realops');
    Route::get('/bid/{id}', [RealopsController::class, 'bid'])->middleware('auth:realops')->middleware('toggle:realops_bidding');
    Route::get('/cancel-bid/{id}', [RealopsController::class, 'cancelBid'])->middleware('auth:realops');
});

Route::prefix('pilot_passport')->group(function () {
    Route::get('/', [PilotPassportController::class, 'index'])->name('pilotPassportIndex');
    Route::post('/', [PilotPassportController::class, 'index'])->name('pilotPassportIndex');
    Route::get('/login', [LoginController::class, 'pilotPassportLogin'])->middleware('guest:realops');
    Route::post('/enroll', [PilotPassportController::class, 'enroll'])->middleware('auth:realops')->name('pilotPassportEnroll');
    Route::get('/stamp/{id}', [PilotPassportController::class, 'generateStamp'])->middleware('auth:realops');
    Route::get('/medal/{id}', [PilotPassportController::class, 'generateMedal'])->middleware('auth:realops');
    Route::get('/certificate/{id}', [PilotPassportController::class, 'generateCertificate'])->middleware('auth:realops');
    Route::post('/disenroll', [PilotPassportController::class, 'purgeData'])->name('pilotPassportPurgeData')->middleware('auth:realops');
    Route::post('/privacy', [PilotPassportController::class, 'setPrivacy'])->name('pilotPassportSettings')->middleware('auth:realops');
    Route::get('/passport_book', [PilotPassportController::class, 'tabPassportBook']);
    Route::get('/achievements', [PilotPassportController::class, 'tabAchievements']);
});

/*
*   End Front Page Stuff
*/

/*
*   Roster, Login/Logout
*/
Route::get('/controllers/roster', [RosterController::class, 'index']);
Route::get('/controllers/staff', [RosterController::class, 'staffIndex']);
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
/*
*   End Roster
*/

/*
*   Controller Dashboard
*/
Route::prefix('dashboard')->middleware('auth:web')->group(function () {
    Route::get('/', [ControllerDash::class, 'dash']);

    Route::prefix('controllers')->group(function () {
        Route::get('/calendar/view/{id}', [ControllerDash::class, 'showCalendarEvent']);
        Route::get('/roster', [ControllerDash::class, 'showRoster']);
        Route::get('/files', [ControllerDash::class, 'showFiles']);
        Route::get('/suggestions', [ControllerDash::class, 'showSuggestions']);
        Route::get('/atcast', [ControllerDash::class, 'showatcast']);
        Route::get('/stats/{year?}/{month?}', [ControllerDash::class, 'showStats']);
        Route::get('/profile', [ControllerDash::class, 'showProfile']);
        Route::post('/profile', [ControllerDash::class, 'updateInfo'])->name('updateInfo');
        Route::get('/profile/discord', [ControllerDash::class, 'updateDiscordRoles']);
        Route::get('/profile/discord-staffup', [DiscordController::class, 'toggleStaffUpRole']);
        Route::get('/ticket/{id}', [ControllerDash::class, 'showTicket']);
        Route::post('/ticket/{id}', [TrainingDash::class, 'addStudentComments'])->name('addStudentComments');
        Route::get('/profile/feedback-details/{id}', [ControllerDash::class, 'showFeedbackDetails']);
        Route::get('/profile/trainer-feedback-details/{id}', [ControllerDash::class, 'showTrainerFeedbackDetails']);
        Route::prefix('events')->group(function () {
            Route::get('/', [ControllerDash::class, 'showEvents']);
            Route::prefix('view')->middleware('event_visibility')->group(function () {
                Route::get('/{id}', [ControllerDash::class, 'viewEvent'])->name('viewEvent');
                Route::post('/signup', [ControllerDash::class, 'signupForEvent'])->name('signupForEvent');
                Route::get('/{id}/un-signup', [ControllerDash::class, 'unsignupForEvent'])->name('unSignupForEvent');
            });
        });
        Route::get('/scenery', [ControllerDash::class, 'sceneryIndex']);
        Route::get('/scenery/view/{id}', [ControllerDash::class, 'showScenery']);
        Route::post('/scenery/search', [ControllerDash::class, 'searchScenery']);
        Route::get('/merch', [MerchStore::class, 'viewStore']);
        Route::post('/search-airport', [ControllerDash::class, 'searchAirport'])->name('searchAirport');
        Route::get('/search-airport/search', [ControllerDash::class, 'searchAirportResult']);
        Route::post('/report-bug', [ControllerDash::class, 'reportBug'])->name('reportBug');
        Route::prefix('incident')->group(function () {
            Route::get('/report', [ControllerDash::class, 'incidentReport']);
            Route::post('/report', [ControllerDash::class, 'submitIncidentReport'])->name('submitIncidentReport');
        });
        Route::prefix('bookings')->group(function () {
            Route::get('/', [AtcBookingController::class, 'viewBookings']);
            Route::get('/delete/{id}', [AtcBookingController::class, 'deleteBooking']);
            Route::post('/create', [AtcBookingController::class, 'createBooking'])->name('createBooking');
        });
        Route::get('/live', [ControllerDash::class, 'showLiveEventInfo']);
    });

    Route::prefix('opt')->group(function () {
        Route::post('/in', [ControllerDash::class, 'optIn'])->name('optIn');
        Route::get('/out', [ControllerDash::class, 'optOut']);
    });

    Route::prefix('training')->group(function () {
        Route::get('atcast', [TrainingDash::class, 'showatcast']);
        Route::get('/req', [TrainingDash::class, 'ShowReq']);
        Route::get('/schedule', [TrainingDash::class, 'handleSchedule']);
        Route::post('/upload-image', [TrainingDash::class, 'imageUpload'])->name('imageUpload');
        Route::prefix('tickets')->middleware(['permission:train', 'no_back'])->group(function () {
            Route::get('/', [TrainingDash::class, 'ticketsIndex']);
            Route::post('/search', [TrainingDash::class, 'searchTickets']);
            Route::get('/new', [TrainingDash::class, 'newTrainingTicket']);
            Route::post('/save/{id?}', [TrainingDash::class, 'handleSaveTicket'])->name('saveTicket');
            Route::get('/view/{id}', [TrainingDash::class, 'viewTicket']);
            Route::get('/edit/{id}', [TrainingDash::class, 'editTicket']);
            Route::get('/delete/{id}', [TrainingDash::class, 'deleteTicket']);
            Route::post('/get-rating/{cid}', [TrainingDash::class, 'getRating']);
        });
        Route::prefix('trainer_feedback')->group(function () {
            Route::get('/new', [TrainingDash::class, 'newTrainerFeedback'])->name('internalTrainerFeedback');
        });
        Route::prefix('ots-center')->middleware('role:ins|atm|datm|ta|wm')->group(function () {
            Route::get('/', [TrainingDash::class, 'otsCenter']);
            Route::get('/accept/{id}', [TrainingDash::class, 'acceptRecommendation']);
            Route::get('/reject/{id}', [TrainingDash::class, 'rejectRecommendation'])->middleware('permission:snrStaff');
            Route::post('/assign/{id}', [TrainingDash::class, 'assignRecommendation'])->middleware('permission:snrStaff')->name('assignRecommendation');
            Route::get('/cancel/{id}', [TrainingDash::class, 'otsCancel']);
            Route::post('/complete/{id}', [TrainingDash::class, 'completeOTS'])->name('completeOTS');
        });
        Route::prefix('info')->group(function () {
            Route::get('/', [TrainingDash::class, 'trainingInfo']);
            Route::post('/add/{section}', [TrainingDash::class, 'addInfo'])->middleware('permission:snrStaff')->name('addInfo');
            Route::get('/delete/{id}', [TrainingDash::class, 'deleteInfo'])->middleware('permission:snrStaff');
            Route::prefix('public')->middleware('permission:snrStaff')->group(function () {
                Route::post('/new-section', [TrainingDash::class, 'newPublicInfoSection'])->name('newPublicInfoSection');
                Route::post('/edit-section/{id}', [TrainingDash::class, 'editPublicSection'])->name('editPublicSection');
                Route::get('/remove-section/{id}', [TrainingDash::class, 'removePublicInfoSection']);
                Route::post('/add-pdf/{id}', [TrainingDash::class, 'addPublicPdf'])->name('addPublicPdf');
                Route::get('/remove-pdf/{id}', [TrainingDash::class, 'removePublicPdf']);
            });
        });
        Route::get('/statistics', [TrainingDash::class, 'statistics'])->middleware('permission:snrStaff')->name('statistics');
    });

    Route::prefix('admin')->group(function () {
        Route::prefix('announcement')->middleware('permission:staff|contributor')->group(function () {
            Route::get('/', [AdminDash::class, 'setAnnouncement']);
            Route::post('/', [AdminDash::class, 'saveAnnouncement'])->name('saveAnnouncement');
        });
        Route::prefix('audits')->middleware('permission:snrStaff')->group(function () {
            Route::get('/', [AdminDash::class, 'showAudits']);
        });
        Route::prefix('calendar')->middleware('permission:staff|contributor')->group(function () {
            Route::get('/', [AdminDash::class, 'viewCalendar']);
            Route::get('/view/{id}', [AdminDash::class, 'viewCalendarEvent']);
            Route::get('/new', [AdminDash::class, 'newCalendarEvent']);
            Route::post('/new/save', [AdminDash::class, 'storeCalendarEvent'])->name('storeCalendarEvent');
            Route::get('/edit/{id}', [AdminDash::class, 'editCalendarEvent']);
            Route::post('/edit/{id}/save', [AdminDash::class, 'saveCalendarEvent'])->name('saveCalendarEvent');
            Route::delete('/delete/{id}', [AdminDash::class, 'deleteCalendarEvent'])->name('deleteCalendarEvent');
            Route::post('/edit/vis/{id}', [AdminDash::class, 'toggleCalendarEventVisibility'])->name('toggleCalendarEventVisibility');
        });
        Route::prefix('scenery')->middleware('permission:scenery')->group(function () {
            Route::get('/', [AdminDash::class, 'showScenery']);
            Route::get('/view/{id}', [AdminDash::class, 'viewScenery']);
            Route::get('/new', [AdminDash::class, 'newScenery']);
            Route::post('/new', [AdminDash::class, 'storeScenery'])->name('storeScenery');
            Route::get('/edit/{id}', [AdminDash::class, 'editScenery']);
            Route::post('/edit/{id}/save', [AdminDash::class, 'saveScenery'])->name('saveScenery');
            Route::delete('/delete/{id}', [AdminDash::class, 'deleteScenery'])->name('deleteScenery');
        });
        Route::prefix('store')->middleware('permission:staff|permission:contributor')->group(function () {
            Route::get('/', [MerchStore::class, 'adminStore']);
            Route::get('/view/{id}', [MerchStore::class, 'viewItem']);
            Route::get('/new', [MerchStore::class, 'newItem']);
            Route::post('/new', [MerchStore::class, 'saveItem'])->name('saveNewStoreItem');
            Route::get('/edit/{id}', [MerchStore::class, 'editItem'])->name('editStoreItem');
            Route::post('/edit/{id}/save', [MerchStore::class, 'saveItem'])->name('saveStoreItem');
            Route::get('/delete/{id}', [MerchStore::class, 'deleteItem'])->name('deleteStoreItem');
        });
        Route::prefix('files')->middleware('permission:files')->group(function () {
            Route::get('/upload', [AdminDash::class, 'uploadFile']);
            Route::post('/upload', [AdminDash::class, 'storeFile'])->name('storeFile');
            Route::post('/separator', [AdminDash::class, 'fileSeparator'])->name('fileSeparator');
            Route::get('/edit/{id}', [AdminDash::class, 'editFile']);
            Route::post('/edit/{id}', [AdminDash::class, 'saveFile'])->name('saveFile');
            Route::get('/delete/{id}', [AdminDash::class, 'deleteFile']);
            Route::get('/disp-order', [AdminDash::class, 'updateFileDispOrder']);
        });
        Route::prefix('airports')->middleware('permission:staff')->group(function () {
            Route::get('/', [AdminDash::class, 'showAirports']);
            Route::get('/new', [AdminDash::class, 'newAirport']);
            Route::post('/new', [AdminDash::class, 'storeAirport'])->name('storeAirport');
            Route::get('/add-to-home/{id}', [AdminDash::class, 'addAirportToHome']);
            Route::get('/del-from-home/{id}', [AdminDash::class, 'removeAirportFromHome']);
            Route::delete('/delete/{id}', [AdminDash::class, 'deleteAirport'])->name('deleteAirport');
        });
        Route::prefix('events')->middleware('permission:events')->group(function () {
            Route::get('/new', [AdminDash::class, 'newEvent']);
            Route::post('/new', [AdminDash::class, 'saveNewEvent'])->name('saveNewEvent');
            Route::post('/positions/add/{id}', [AdminDash::class, 'addPosition'])->name('addPosition');
            Route::get('/position/delete/{id}', [AdminDash::class, 'removePosition']);
            Route::post('/positions/assign/{id}', [AdminDash::class, 'assignPosition'])->name('assignPosition');
            Route::get('/positions/unassign/{id}', [AdminDash::class, 'unassignPosition']);
            Route::post('/positions/manual-assign/{id}', [AdminDash::class, 'manualAssign'])->name('manualAssign');
            Route::get('/edit/{id}', [AdminDash::class, 'editEvent']);
            Route::post('/edit/{id}', [AdminDash::class, 'saveEvent'])->name('saveEvent');
            Route::get('/delete/{id}', [AdminDash::class, 'deleteEvent']);
            Route::get('/toggle-reg/{id}', [AdminDash::class, 'toggleRegistration']);
            Route::get('/toggle-show-assignments/{id}', [AdminDash::class, 'toggleShowAssignments']);
            Route::get('/set-active/{id}', [AdminDash::class, 'setEventActive']);
            Route::get('/hide/{id}', [AdminDash::class, 'hideEvent']);
            Route::post('/save-preset/{id}', [AdminDash::class, 'setEventPositionPreset'])->name('setEventPositionPreset');
            Route::post('/load-preset/{id}', [AdminDash::class, 'retrievePositionPreset'])->name('retrievePositionPreset');
            Route::post('/delete-preset', [AdminDash::class, 'deletePositionPreset'])->name('deletePositionPreset');
            Route::get('/send-reminder/{id}', [AdminDash::class, 'sendEventReminder']);
            Route::get('/noshow/mark/{id}', [AdminDash::class, 'eventMarkNoShow']);
            Route::get('/noshow/unmark/{id}', [AdminDash::class, 'eventUnMarkNoShow']);
            Route::get('/denylist', [AdminDash::class, 'viewEventDenylist']);
            Route::get('/denylist/delete/{id}', [AdminDash::class, 'deleteEventDenylist']);
            Route::get('/statistics/{id}', [AdminDash::class, 'viewEventStats']);
            Route::get('/statistics/rerun/{id}', [AdminDash::class, 'rerunEventStats']);
            Route::post('/statistics/update/{id}', [AdminDash::class, 'updateTrackingAirports'])->name('updateEventTrackingAirports');
            Route::get('/discord-role/{id}', [DiscordController::class, 'toggleEventRole']);
        });
        Route::prefix('roster')->middleware('permission:roster')->group(function () {
            Route::get('/visit/requests', [AdminDash::class, 'showVisitRequests']);
            Route::get('/visit/accept/{id}', [AdminDash::class, 'acceptVisitRequest']);
            Route::post('/visit/manual-add/search', [AdminDash::class, 'manualAddVisitor'])->name('manualAddVisitor');
            Route::get('/visit/manual-add', [AdminDash::class, 'manualAddVisitorForm']);
            Route::post('/visit/accept/save', [AdminDash::class, 'storeVisitor'])->name('storeVisitor');
            Route::post('/visit/reject/{id}', [AdminDash::class, 'rejectVisitRequest'])->name('rejectVisitRequest');
            Route::get('/visit/requests/view/{id}', [AdminDash::class, 'viewVisitRequest']);
            Route::get('/visit/remove/{id}', [AdminDash::class, 'removeVisitor']);
            Route::get('/visit-agreement/reject/{id}', [AdminDash::class, 'disallowVisitReq']);
            Route::post('/visit-agreement/permit', [AdminDash::class, 'allowVisitReq'])->name('allowVisitReq');
            Route::get('/purge-assistant/{year?}/{month?}', [AdminDash::class, 'showRosterPurge']);
        });
        Route::prefix('roster')->middleware('permission:roster|train|events')->group(function () {
            Route::get('/edit/{id}', [AdminDash::class, 'editController']);
            Route::post('/edit/{id}', [AdminDash::class, 'updateController'])->name('updateController');
            Route::get('/solo/{id}', [AdminDash::class, 'removeSoloCertifications'])->name('removeSoloCertifications');
        });
        Route::prefix('local-hero')->middleware('permission:snrStaff')->group(function () {
            Route::post('/{year}/{month}/{hours}/{id}', [AdminDash::class, 'setLocalHeroWinner']);
            Route::get('/remove/{id}/{year}/{month}', [AdminDash::class, 'removeLocalHeroWinner']);
            Route::post('/config-challenge/{id}', [AdminDash::class, 'updateLocalHeroChallenge'])->name('updateLocalHeroChallenge');
        });
        Route::prefix('bronze-mic')->middleware('permission:snrStaff')->group(function () {
            Route::get('/{sort?}/{year?}/{month?}/', [AdminDash::class, 'showBronzeMic']);
            Route::post('/{year}/{month}/{hours}/{id}', [AdminDash::class, 'setBronzeWinner']);
            Route::get('/remove/{id}/{year}/{month}', [AdminDash::class, 'removeBronzeWinner']);
        });
        Route::prefix('pyrite-mic')->middleware('permission:snrStaff')->group(function () {
            Route::get('/{year?}', [AdminDash::class, 'showPyriteMic']);
            Route::post('/{year}/{hours}/{id}', [AdminDash::class, 'setPyriteWinner']);
            Route::get('/remove/{id}/{year}', [AdminDash::class, 'removePyriteWinner']);
        });
        Route::prefix('feedback')->middleware('permission:snrStaff')->group(function () {
            Route::get('/', [AdminDash::class, 'showFeedback']);
            Route::post('/save/{id}', [AdminDash::class, 'saveFeedback'])->name('saveFeedback');
            Route::post('/hide/{id}', [AdminDash::class, 'hideFeedback'])->name('hideFeedback');
            Route::post('/update/{id}', [AdminDash::class, 'updateFeedback'])->name('updateFeedback');
            Route::post('/email/{id}', [AdminDash::class, 'emailFeedback'])->name('emailFeedback');
        });
        Route::prefix('trainer_feedback')->middleware('role:atm|datm|ta|ata|wm')->group(function () {
            Route::get('/', [AdminDash::class, 'showTrainerFeedback']);
            Route::post('/save/{id}', [AdminDash::class, 'saveTrainerFeedback'])->name('saveTrainerFeedback');
            Route::post('/hide/{id}', [AdminDash::class, 'hideTrainerFeedback'])->name('hideTrainerFeedback');
            Route::post('/update/{id}', [AdminDash::class, 'updateTrainerFeedback'])->name('updateTrainerFeedback');
            Route::post('/email/{id}', [AdminDash::class, 'emailTrainerFeedback'])->name('emailTrainerFeedback');
        });
        Route::prefix('email')->middleware('permission:email')->group(function () {
            Route::get('/send', [AdminDash::class, 'sendNewEmail']);
            Route::post('/send', [AdminDash::class, 'sendEmail'])->name('sendEmail');
        });
        Route::prefix('incident')->middleware('permission:snrStaff')->group(function () {
            Route::get('/', [AdminDash::class, 'incidentReportIndex']);
            Route::get('/view/{id}', [AdminDash::class, 'viewIncidentReport']);
            Route::get('/archive/{id}', [AdminDash::class, 'archiveIncident']);
            Route::get('/delete/{id}', [AdminDash::class, 'deleteIncident']);
        });
        Route::prefix('realops')->middleware('toggle:realops')->middleware('ability:events-team,staff,false')->group(function () {
            Route::get('/', [RealopsController::class, 'adminIndex']);
            Route::get('/create', [RealopsController::class, 'showCreateFlight']);
            Route::post('/create', [RealopsController::class, 'createFlight'])->name('createFlight');
            Route::post('/create/bulk', [RealopsController::class, 'bulkUploadFlights'])->name('bulkUploadFlights');
            Route::get('/edit/{id}', [RealopsController::class, 'showEditFlight']);
            Route::put('/edit/{id}', [RealopsController::class, 'editFlight'])->name('editFlight');
            Route::put('/assign-pilot/{id}', [RealopsController::class, 'assignPilotToFlight'])->name('assignPilotToFlight');
            Route::get('/remove-pilot/{id}', [RealopsController::class, 'removePilotFromFlight']);
            Route::get('/delete/{id}', [RealopsController::class, 'deleteFlight']);
            Route::middleware('permission:staff')->group(function () {
                Route::get('/export', [RealopsController::class, 'exportData']);
                Route::post('/dump-data', [RealopsController::class, 'dumpData'])->name('dumpData');
            });
        });
        Route::prefix('live')->middleware('ability:events-team,staff,false')->group(function () {
            Route::get('/', [AdminDash::class, 'setLiveEventInfo']);
            Route::post('/', [AdminDash::class, 'saveLiveEventInfo'])->name('saveLiveEventInfo');
        });
        
        Route::prefix('toggles')->middleware('permission:staff')->group(function () {
            Route::get('/', [AdminDash::class, 'showFeatureToggles']);
            Route::get('/create', [AdminDash::class, 'showCreateFeatureToggle']);
            Route::post('/create', [AdminDash::class, 'createFeatureToggle'])->name('createFeatureToggle');
            Route::get('/delete/{toggle_name}', [AdminDash::class, 'deleteFeatureToggle']);
            Route::get('/edit/{toggle_name}', [AdminDash::class, 'showEditFeatureToggle']);
            Route::post('/edit', [AdminDash::class, 'editFeatureToggle'])->name('editFeatureToggle');
            Route::get('/toggle/{toggle_name}', [AdminDash::class, 'toggleFeatureToggle']);
        });
        Route::prefix('monitor')->middleware('permission:staff')->group(function () {
            Route::get('/', [AdminDash::class, 'backgroundMonitor']);
        });

        Route::prefix('impersonation')->middleware('toggle:impersonation')->group(function () {
            Route::post('/', [ImpersonationController::class, 'start'])->middleware('role:wm|awm')->name('startImpersonation');
            Route::get('/stop', [ImpersonationController::class, 'stop'])->name('stopImpersonation');
        });
    });
});
/*
*   End Controller Dashboard
*/
