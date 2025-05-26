<?php

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

/*
*   Front Page Stuff
*/
Route::get('/', 'FrontController@home');
Route::get('/controllers/teamspeak', 'FrontController@teamspeak');
Route::get('/privacy', 'FrontController@privacy');
Route::get('/controllers/stats/{year?}/{month?}', 'FrontController@showStats');
Route::get('/visit', 'FrontController@visit');
Route::post('/visit/save', 'FrontController@storeVisit')->name('storeVisit');
Route::get('/pilots/airports', 'FrontController@airportIndex');
Route::post('/pilots/airports', 'FrontController@searchAirport')->name('searchAirport');
Route::get('/pilots/airports/search', 'FrontController@searchAirportResult');
Route::get('/pilots/airports/view/{id}', 'FrontController@showAirport');
Route::get('/pilots/scenery', 'FrontController@sceneryIndex');
Route::get('/pilots/scenery/view/{id}', 'FrontController@showScenery');
Route::post('/pilots/scenery/search', 'FrontController@searchScenery');
Route::get('/pilots/request-staffing', 'FrontController@showStaffRequest');
Route::post('/pilots/request-staffing', 'FrontController@staffRequest')->name('staffRequest');
Route::get('/pilots/guide/atl', 'FrontController@pilotGuideAtl');
Route::get('/feedback/new', 'FrontController@newFeedback');
Route::get('/feedback/new/{slug}', 'FrontController@newFeedback');
Route::post('/feedback/new', 'FrontController@saveNewFeedback')->name('saveNewFeedback');
Route::get('/trainer_feedback/new', 'FrontController@newTrainerFeedback');
Route::post('/trainer_feedback/new', 'TrainingDash@saveNewTrainerFeedback')->name('saveNewTrainerFeedback');
Route::get('controllers/files', 'FrontController@showFiles');
Route::get('/ramp-status/atl', 'FrontController@showAtlRamp');
Route::get('/ramp-status/clt', 'FrontController@showCltRamp');
Route::get('/asset/{slug}', 'FrontController@showPermalink');
Route::get('/live', 'FrontController@showLiveEventInfo');

Route::prefix('realops')->middleware('toggle:realops')->group(function () {
    Route::get('/', 'RealopsController@index')->name('realopsIndex');
    Route::get('/login', 'Auth\LoginController@realopsLogin')->middleware('guest:realops');
    Route::get('/bid/{id}', 'RealopsController@bid')->middleware('auth:realops')->middleware('toggle:realops_bidding');
    Route::get('/cancel-bid/{id}', 'RealopsController@cancelBid')->middleware('auth:realops');
});

Route::prefix('pilot_passport')->group(function () {
    Route::get('/', 'PilotPassportController@index')->name('pilotPassportIndex');
    Route::post('/', 'PilotPassportController@index')->name('pilotPassportIndex');
    Route::get('/login', 'Auth\LoginController@pilotPassportLogin')->middleware('guest:realops');
    Route::post('/enroll', 'PilotPassportController@enroll')->middleware('auth:realops')->name('pilotPassportEnroll');
    Route::get('/stamp/{id}', 'PilotPassportController@generateStamp')->middleware('auth:realops');
    Route::get('/medal/{id}', 'PilotPassportController@generateMedal')->middleware('auth:realops');
    Route::get('/certificate/{id}', 'PilotPassportController@generateCertificate')->middleware('auth:realops');
    Route::post('/disenroll', 'PilotPassportController@purgeData')->name('pilotPassportPurgeData')->middleware('auth:realops');
    Route::post('/privacy', 'PilotPassportController@setPrivacy')->name('pilotPassportSettings')->middleware('auth:realops');
    Route::get('/passport_book', 'PilotPassportController@tabPassportBook');
    Route::get('/achievements', 'PilotPassportController@tabAchievements');
});

/*
*   End Front Page Stuff
*/

/*
*   Roster, Login/Logout
*/
Route::get('/controllers/roster', 'RosterController@index');
Route::get('/controllers/staff', 'RosterController@staffIndex');
Route::get('/login', 'Auth\LoginController@login')->name('login');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
/*
*   End Roster
*/

/*
*   Controller Dashboard
*/
Route::prefix('dashboard')->middleware('auth')->group(function () {
    Route::get('/', 'ControllerDash@dash');

    Route::prefix('controllers')->group(function () {
        Route::get('/calendar/view/{id}', 'ControllerDash@showCalendarEvent');
        Route::get('/roster', 'ControllerDash@showRoster');
        Route::get('/files', 'ControllerDash@showFiles');
        Route::get('/suggestions', 'ControllerDash@showSuggestions');
        Route::get('/atcast', 'ControllerDash@showatcast');
        Route::get('/stats/{year?}/{month?}', 'ControllerDash@showStats');
        Route::get('/profile', 'ControllerDash@showProfile');
        Route::post('/profile', 'ControllerDash@updateInfo')->name('updateInfo');
        Route::get('/profile/discord', 'ControllerDash@updateDiscordRoles');
        Route::get('/ticket/{id}', 'ControllerDash@showTicket');
        Route::post('/ticket/{id}', 'TrainingDash@addStudentComments')->name('addStudentComments');
        Route::get('/profile/feedback-details/{id}', 'ControllerDash@showFeedbackDetails');
        Route::get('/profile/trainer-feedback-details/{id}', 'ControllerDash@showTrainerFeedbackDetails');
        Route::get('/events', 'ControllerDash@showEvents');
        Route::get('/events/view/{id}', 'ControllerDash@viewEvent');
        Route::post('/events/view/signup', 'ControllerDash@signupForEvent')->name('signupForEvent');
        Route::get('/events/view/{id}/un-signup', 'ControllerDash@unsignupForEvent');
        Route::get('/scenery', 'ControllerDash@sceneryIndex');
        Route::get('/scenery/view/{id}', 'ControllerDash@showScenery');
        Route::post('/scenery/search', 'ControllerDash@searchScenery');
        Route::post('/search-airport', 'ControllerDash@searchAirport')->name('searchAirport');
        Route::get('/search-airport/search', 'ControllerDash@searchAirportResult');
        Route::post('/report-bug', 'ControllerDash@reportBug')->name('reportBug');
        Route::prefix('incident')->group(function () {
            Route::get('/report', 'ControllerDash@incidentReport');
            Route::post('/report', 'ControllerDash@submitIncidentReport')->name('submitIncidentReport');
        });
        Route::prefix('bookings')->group(function () {
            Route::get('/', 'AtcBookingController@viewBookings');
            Route::get('/delete/{id}', 'AtcBookingController@deleteBooking');
            Route::post('/create', 'AtcBookingController@createBooking')->name('createBooking');
        });
        Route::get('/live', 'ControllerDash@showLiveEventInfo');
    });

    Route::prefix('opt')->group(function () {
        Route::post('/in', 'ControllerDash@optIn')->name('optIn');
        Route::get('/out', 'ControllerDash@optOut');
    });

    Route::prefix('training')->group(function () {
        Route::get('atcast', 'TrainingDash@showatcast');
        Route::get('/req', 'TrainingDash@ShowReq');
        Route::get('/schedule', 'TrainingDash@handleSchedule');
        Route::prefix('tickets')->middleware('permission:train')->group(function () {
            Route::get('/', 'TrainingDash@ticketsIndex');
            Route::post('/search', 'TrainingDash@searchTickets');
            Route::get('/new', 'TrainingDash@newTrainingTicket');
            Route::post('/save/{id?}', 'TrainingDash@handleSaveTicket')->name('saveTicket');
            Route::get('/view/{id}', 'TrainingDash@viewTicket');
            Route::get('/edit/{id}', 'TrainingDash@editTicket');
            Route::get('/delete/{id}', 'TrainingDash@deleteTicket');
        });
        Route::prefix('trainer_feedback')->group(function () {
            Route::get('/new', 'TrainingDash@newTrainerFeedback')->name('internalTrainerFeedback');
        });
        Route::prefix('ots-center')->middleware('role:ins|atm|datm|ta|wm')->group(function () {
            Route::get('/', 'TrainingDash@otsCenter');
            Route::get('/accept/{id}', 'TrainingDash@acceptRecommendation');
            Route::get('/reject/{id}', 'TrainingDash@rejectRecommendation')->middleware('permission:snrStaff');
            Route::post('/assign/{id}', 'TrainingDash@assignRecommendation')->middleware('permission:snrStaff')->name('assignRecommendation');
            Route::get('/cancel/{id}', 'TrainingDash@otsCancel');
            Route::post('/complete/{id}', 'TrainingDash@completeOTS')->name('completeOTS');
        });
        Route::prefix('info')->group(function () {
            Route::get('/', 'TrainingDash@trainingInfo');
            Route::post('/add/{section}', 'TrainingDash@addInfo')->middleware('permission:snrStaff')->name('addInfo');
            Route::get('/delete/{id}', 'TrainingDash@deleteInfo')->middleware('permission:snrStaff');
            Route::prefix('public')->middleware('permission:snrStaff')->group(function () {
                Route::post('/new-section', 'TrainingDash@newPublicInfoSection')->name('newPublicInfoSection');
                Route::post('/edit-section/{id}', 'TrainingDash@editPublicSection')->name('editPublicSection');
                Route::get('/remove-section/{id}', 'TrainingDash@removePublicInfoSection');
                Route::post('/add-pdf/{id}', 'TrainingDash@addPublicPdf')->name('addPublicPdf');
                Route::get('/remove-pdf/{id}', 'TrainingDash@removePublicPdf');
            });
        });
        Route::get('/statistics', 'TrainingDash@statistics')->middleware('permission:snrStaff')->name('statistics');
        Route::get('/statistics/graph', 'TrainingDash@generateGraphs')->middleware('permission:snrStaff');
    });

    Route::prefix('admin')->group(function () {
        Route::prefix('announcement')->middleware('permission:staff|contributor')->group(function () {
            Route::get('/', 'AdminDash@setAnnouncement');
            Route::post('/', 'AdminDash@saveAnnouncement')->name('saveAnnouncement');
        });
        Route::prefix('audits')->middleware('permission:snrStaff')->group(function () {
            Route::get('/', 'AdminDash@showAudits');
        });
        Route::prefix('calendar')->middleware('permission:staff|contributor')->group(function () {
            Route::get('/', 'AdminDash@viewCalendar');
            Route::get('/view/{id}', 'AdminDash@viewCalendarEvent');
            Route::get('/new', 'AdminDash@newCalendarEvent');
            Route::post('/new/save', 'AdminDash@storeCalendarEvent')->name('storeCalendarEvent');
            Route::get('/edit/{id}', 'AdminDash@editCalendarEvent');
            Route::post('/edit/{id}/save', 'AdminDash@saveCalendarEvent')->name('saveCalendarEvent');
            Route::delete('/delete/{id}', 'AdminDash@deleteCalendarEvent')->name('deleteCalendarEvent');
            Route::post('/edit/vis/{id}', 'AdminDash@toggleCalendarEventVisibility')->name('toggleCalendarEventVisibility');
        });
        Route::prefix('scenery')->middleware('permission:scenery')->group(function () {
            Route::get('/', 'AdminDash@showScenery');
            Route::get('/view/{id}', 'AdminDash@viewScenery');
            Route::get('/new', 'AdminDash@newScenery');
            Route::post('/new', 'AdminDash@storeScenery')->name('storeScenery');
            Route::get('/edit/{id}', 'AdminDash@editScenery');
            Route::post('/edit/{id}/save', 'AdminDash@saveScenery')->name('saveScenery');
            Route::delete('/delete/{id}', 'AdminDash@deleteScenery')->name('deleteScenery');
        });
        Route::prefix('files')->middleware('permission:files')->group(function () {
            Route::get('/upload', 'AdminDash@uploadFile');
            Route::post('/upload', 'AdminDash@storeFile')->name('storeFile');
            Route::post('/separator', 'AdminDash@fileSeparator')->name('fileSeparator');
            Route::get('/edit/{id}', 'AdminDash@editFile');
            Route::post('/edit/{id}', 'AdminDash@saveFile')->name('saveFile');
            Route::get('/delete/{id}', 'AdminDash@deleteFile');
            Route::get('/disp-order', 'AdminDash@updateFileDispOrder');
        });
        Route::prefix('airports')->middleware('permission:staff')->group(function () {
            Route::get('/', 'AdminDash@showAirports');
            Route::get('/new', 'AdminDash@newAirport');
            Route::post('/new', 'AdminDash@storeAirport')->name('storeAirport');
            Route::get('/add-to-home/{id}', 'AdminDash@addAirportToHome');
            Route::get('/del-from-home/{id}', 'AdminDash@removeAirportFromHome');
            Route::delete('/delete/{id}', 'AdminDash@deleteAirport')->name('deleteAirport');
        });
        Route::prefix('events')->middleware('permission:events')->group(function () {
            Route::get('/new', 'AdminDash@newEvent');
            Route::post('/new', 'AdminDash@saveNewEvent')->name('saveNewEvent');
            Route::post('/positions/add/{id}', 'AdminDash@addPosition')->name('addPosition');
            Route::get('/position/delete/{id}', 'AdminDash@removePosition');
            Route::post('/positions/assign/{id}', 'AdminDash@assignPosition')->name('assignPosition');
            Route::get('/positions/unassign/{id}', 'AdminDash@unassignPosition');
            Route::post('/positions/manual-assign/{id}', 'AdminDash@manualAssign')->name('manualAssign');
            Route::get('/edit/{id}', 'AdminDash@editEvent');
            Route::post('/edit/{id}', 'AdminDash@saveEvent')->name('saveEvent');
            Route::get('/delete/{id}', 'AdminDash@deleteEvent');
            Route::get('/toggle-reg/{id}', 'AdminDash@toggleRegistration');
            Route::get('/toggle-show-assignments/{id}', 'AdminDash@toggleShowAssignments')->middleware('toggle:event_assignment_toggle');
            Route::get('/set-active/{id}', 'AdminDash@setEventActive');
            Route::get('/hide/{id}', 'AdminDash@hideEvent');
            Route::post('/save-preset/{id}', 'AdminDash@setEventPositionPreset')->name('setEventPositionPreset');
            Route::post('/load-preset/{id}', 'AdminDash@retrievePositionPreset')->name('retrievePositionPreset');
            Route::post('/delete-preset', 'AdminDash@deletePositionPreset')->name('deletePositionPreset');
            Route::get('/send-reminder/{id}', 'AdminDash@sendEventReminder');
            Route::get('/noshow/mark/{id}', 'AdminDash@eventMarkNoShow');
            Route::get('/noshow/unmark/{id}', 'AdminDash@eventUnMarkNoShow');
            Route::get('/denylist', 'AdminDash@viewEventDenylist');
            Route::get('/denylist/delete/{id}', 'AdminDash@deleteEventDenylist');
            Route::get('/statistics/{id}', 'AdminDash@viewEventStats');
            Route::get('/statistics/rerun/{id}', 'AdminDash@rerunEventStats');
            Route::post('/statistics/update/{id}', 'AdminDash@updateTrackingAirports')->name('updateEventTrackingAirports');
        });
        Route::prefix('roster')->middleware('permission:roster')->group(function () {
            Route::get('/visit/requests', 'AdminDash@showVisitRequests');
            Route::get('/visit/accept/{id}', 'AdminDash@acceptVisitRequest');
            Route::post('/visit/manual-add/search', 'AdminDash@manualAddVisitor')->name('manualAddVisitor');
            Route::get('/visit/manual-add', 'AdminDash@manualAddVisitorForm');
            Route::post('/visit/accept/save', 'AdminDash@storeVisitor')->name('storeVisitor');
            Route::post('/visit/reject/{id}', 'AdminDash@rejectVisitRequest')->name('rejectVisitRequest');
            Route::get('/visit/requests/view/{id}', 'AdminDash@viewVisitRequest');
            Route::get('/visit/remove/{id}', 'AdminDash@removeVisitor');
            Route::get('/visit-agreement/reject/{id}', 'AdminDash@disallowVisitReq');
            Route::post('/visit-agreement/permit', 'AdminDash@allowVisitReq')->name('allowVisitReq');
            Route::get('/purge-assistant/{year?}/{month?}', 'AdminDash@showRosterPurge');
        });
        Route::prefix('roster')->middleware('permission:roster|train|events')->group(function () {
            Route::get('/edit/{id}', 'AdminDash@editController');
            Route::post('/edit/{id}', 'AdminDash@updateController')->name('updateController');
            Route::get('/solo/{id}', 'AdminDash@removeSoloCertifications')->name('removeSoloCertifications');
        });
        Route::prefix('local-hero')->middleware('permission:snrStaff')->group(function () {
            Route::post('/{year}/{month}/{hours}/{id}', 'AdminDash@setLocalHeroWinner');
            Route::get('/remove/{id}/{year}/{month}', 'AdminDash@removeLocalHeroWinner');
            Route::post('/config-challenge/{id}', 'AdminDash@updateLocalHeroChallenge')->name('updateLocalHeroChallenge');
        });
        Route::prefix('bronze-mic')->middleware('permission:snrStaff')->group(function () {
            Route::get('/{sort?}/{year?}/{month?}/', 'AdminDash@showBronzeMic');
            Route::post('/{year}/{month}/{hours}/{id}', 'AdminDash@setBronzeWinner');
            Route::get('/remove/{id}/{year}/{month}', 'AdminDash@removeBronzeWinner');
        });
        Route::prefix('pyrite-mic')->middleware('permission:snrStaff')->group(function () {
            Route::get('/{year?}', 'AdminDash@showPyriteMic');
            Route::post('/{year}/{hours}/{id}', 'AdminDash@setPyriteWinner');
            Route::get('/remove/{id}/{year}', 'AdminDash@removePyriteWinner');
        });
        Route::prefix('feedback')->middleware('permission:snrStaff')->group(function () {
            Route::get('/', 'AdminDash@showFeedback');
            Route::post('/save/{id}', 'AdminDash@saveFeedback')->name('saveFeedback');
            Route::post('/hide/{id}', 'AdminDash@hideFeedback')->name('hideFeedback');
            Route::post('/update/{id}', 'AdminDash@updateFeedback')->name('updateFeedback');
            Route::post('/email/{id}', 'AdminDash@emailFeedback')->name('emailFeedback');
        });
        Route::prefix('trainer_feedback')->middleware('role:atm|datm|ta|ata|wm')->group(function () {
            Route::get('/', 'AdminDash@showTrainerFeedback');
            Route::post('/save/{id}', 'AdminDash@saveTrainerFeedback')->name('saveTrainerFeedback');
            Route::post('/hide/{id}', 'AdminDash@hideTrainerFeedback')->name('hideTrainerFeedback');
            Route::post('/update/{id}', 'AdminDash@updateTrainerFeedback')->name('updateTrainerFeedback');
            Route::post('/email/{id}', 'AdminDash@emailTrainerFeedback')->name('emailTrainerFeedback');
        });
        Route::prefix('email')->middleware('permission:email')->group(function () {
            Route::get('/send', 'AdminDash@sendNewEmail');
            Route::post('/send', 'AdminDash@sendEmail')->name('sendEmail');
        });
        Route::prefix('incident')->middleware('permission:snrStaff')->group(function () {
            Route::get('/', 'AdminDash@incidentReportIndex');
            Route::get('/view/{id}', 'AdminDash@viewIncidentReport');
            Route::get('/archive/{id}', 'AdminDash@archiveIncident');
            Route::get('/delete/{id}', 'AdminDash@deleteIncident');
        });
        Route::prefix('realops')->middleware('toggle:realops')->middleware('ability:events-team,staff,false')->group(function () {
            Route::get('/', 'RealopsController@adminIndex');
            Route::get('/create', 'RealopsController@showCreateFlight');
            Route::post('/create', 'RealopsController@createFlight')->name('createFlight');
            Route::post('/create/bulk', 'RealopsController@bulkUploadFlights')->name('bulkUploadFlights');
            Route::get('/edit/{id}', 'RealopsController@showEditFlight');
            Route::put('/edit/{id}', 'RealopsController@editFlight')->name('editFlight');
            Route::put('/assign-pilot/{id}', 'RealopsController@assignPilotToFlight')->name('assignPilotToFlight');
            Route::get('/remove-pilot/{id}', 'RealopsController@removePilotFromFlight');
            Route::get('/delete/{id}', 'RealopsController@deleteFlight');
            Route::middleware('permission:staff')->group(function () {
                Route::get('/export', 'RealopsController@exportData');
                Route::post('/dump-data', 'RealopsController@dumpData')->name('dumpData');
            });
        });
        Route::prefix('live')->middleware('ability:events-team,staff,false')->group(function () {
            Route::get('/', 'AdminDash@setLiveEventInfo');
            Route::post('/', 'AdminDash@saveLiveEventInfo')->name('saveLiveEventInfo');
        });
        
        Route::prefix('toggles')->middleware('permission:staff')->group(function () {
            Route::get('/', 'AdminDash@showFeatureToggles');
            Route::get('/create', 'AdminDash@showCreateFeatureToggle');
            Route::post('/create', 'AdminDash@createFeatureToggle')->name('createFeatureToggle');
            Route::get('/delete/{toggle_name}', 'AdminDash@deleteFeatureToggle');
            Route::get('/edit/{toggle_name}', 'AdminDash@showEditFeatureToggle');
            Route::post('/edit', 'AdminDash@editFeatureToggle')->name('editFeatureToggle');
            Route::get('/toggle/{toggle_name}', 'AdminDash@toggleFeatureToggle');
        });
        Route::prefix('monitor')->middleware('permission:staff')->group(function () {
            Route::get('/', 'AdminDash@backgroundMonitor');
        });
    });
});
/*
*   End Controller Dashboard
*/
