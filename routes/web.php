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
Route::post('/visit/save', 'FrontController@storeVisit');
Route::get('/pilots/airports', 'FrontController@airportIndex');
Route::post('/pilots/airports', 'FrontController@searchAirport');
Route::get('/pilots/airports/search', 'FrontController@searchAirportResult');
Route::get('/pilots/airports/view/{id}', 'FrontController@showAirport');
Route::get('/pilots/scenery', 'FrontController@sceneryIndex');
Route::get('/pilots/scenery/view/{id}', 'FrontController@showScenery');
Route::post('/pilots/scenery/search', 'FrontController@searchScenery');
Route::get('/pilots/request-staffing', 'FrontController@showStaffRequest');
Route::post('/pilots/request-staffing', 'FrontController@staffRequest');
Route::get('/feedback/new', 'FrontController@newFeedback');
Route::post('/feedback/new', 'FrontController@saveNewFeedback');
Route::get('controllers/files', 'FrontController@showFiles');
Route::get('/ramp-status/atl', 'FrontController@showAtlRamp');
Route::get('/ramp-status/clt', 'FrontController@showCltRamp');

/*
Route::get('/ssd', function () {
    return '<img src=\'/photos/ross_worm.jpg\' style=\'max-height:100%\' />';
});
*/
/*
*   End Front Page Stuff
*/

/*
*   Roster, Login/Logout
*/
Route::get('/controllers/roster', 'RosterController@index');
Route::get('/controllers/staff', 'RosterController@staffIndex');
Route::get('/login', 'RosterController@login');
Route::get('login', [ 'as' => 'login', 'uses' => 'RosterController@login']);
Route::get('/logout', 'RosterController@logout');
/*
*   End Roster
*/

/*
*   Controller Dashboard
*/
Route::prefix('dashboard')->middleware('auth')->group(function() {
    Route::get('/', 'ControllerDash@dash');

    Route::prefix('controllers')->group(function() {
      
        Route::get('/mentoravi', 'TrainingController@showMentAvail');
        Route::post('/mentoravi', 'TrainingController@saveSession');
        Route::get('/trainingreq', 'TrainingController@showRequests');
        Route::delete('/trainingreq/{id}/delete', 'TrainingController@cancelSession');
        Route::get('/sessions_today', 'TrainingController@showsessions_today');


	    
	    
	    


        Route::get('/teamspeak', 'ControllerDash@showTeamspeak');
        Route::get('/calendar/view/{id}', 'ControllerDash@showCalendarEvent');
        Route::get('/roster', 'ControllerDash@showRoster');
        Route::get('/files', 'ControllerDash@showFiles');
        Route::get('/view-my-tickets', 'ControllerDash@showTickets');
        Route::get('/suggestions', 'ControllerDash@showSuggestions');
        Route::get('/atcast', 'ControllerDash@showatcast');
        Route::get('/stats/{year?}/{month?}', 'ControllerDash@showStats');
        Route::get('/profile', 'ControllerDash@showProfile');
        Route::get('/ticket/{id}', 'ControllerDash@showTicket');
        Route::get('/profile/feedback-details/{id}', 'ControllerDash@showFeedbackDetails');
        Route::get('/events', 'ControllerDash@showEvents');
        Route::get('/events/view/{id}', 'ControllerDash@viewEvent');
        Route::post('/events/view/signup', 'ControllerDash@signupForEvent');
        Route::get('/events/view/{id}/un-signup', 'ControllerDash@unsignupForEvent');
        Route::get('/scenery', 'ControllerDash@sceneryIndex');
        Route::get('/scenery/view/{id}', 'ControllerDash@showScenery');
        Route::post('/scenery/search', 'ControllerDash@searchScenery');
        Route::post('/search-airport', 'ControllerDash@searchAirport');
        Route::get('/search-airport/search', 'ControllerDash@searchAirportResult');
        Route::post('/report-bug', 'ControllerDash@reportBug');
        Route::prefix('incident')->group(function() {
            Route::get('/report', 'ControllerDash@incidentReport');
            Route::post('/report', 'ControllerDash@submitIncidentReport');
        });
        Route::prefix('chat')->group(function() {
            Route::get('/messages', 'ChatController@getMessages');
            Route::post('/messages/new', 'ChatController@newMessage');
            Route::post('/messages/delete/{id}', 'ChatController@deleteMessage');
        });
    });

    Route::prefix('opt')->group(function() {
        Route::post('/in', 'ControllerDash@optIn');
        Route::get('/out', 'ControllerDash@optOut');
    });

    Route::prefix('training')->group(function() {
        Route::get('atcast', 'TrainingDash@showatcast');
        Route::get('moodle/login', 'TrainingDash@moodleLogin');
        Route::get('/req', 'TrainingDash@ShowReq');
        Route::prefix('tickets')->middleware('permission:train')->group(function() {
            Route::get('/', 'TrainingDash@ticketsIndex');
            Route::post('/search', 'TrainingDash@searchTickets');
            Route::get('/new', 'TrainingDash@newTrainingTicket');
            Route::post('/new', 'TrainingDash@saveNewTicket');
            Route::get('/view/{id}', 'TrainingDash@viewTicket');
            Route::get('/edit/{id}', 'TrainingDash@editTicket');
            Route::post('/save/{id}', 'TrainingDash@saveTicket');
            Route::get('/delete/{id}', 'TrainingDash@deleteTicket');
            Route::get('/mentor/avail', 'MentorController@showAvail');
            Route::post('/mentor/avail', 'MentorController@postAvail');
            Route::get('/mentor/requests', 'MentorController@showRequests');
            Route::post('/mentor/requests/{id}/cancel', 'MentorController@cancelSession');  
        
        });
        Route::prefix('ots-center')->middleware('role:ins|atm|datm|ta|wm')->group(function() {
            Route::get('/', 'TrainingDash@otsCenter');
            Route::get('/accept/{id}', 'TrainingDash@acceptRecommendation');
            Route::get('/reject/{id}', 'TrainingDash@rejectRecommendation')->middleware('permission:snrStaff');
            Route::post('/assign/{id}', 'TrainingDash@assignRecommendation')->middleware('permission:snrStaff');
            Route::get('/cancel/{id}', 'TrainingDash@otsCancel');
            Route::post('/complete/{id}', 'TrainingDash@completeOTS');
        });
        Route::prefix('info')->group(function() {
            Route::get('/', 'TrainingDash@trainingInfo');
            Route::post('/add/{section}', 'TrainingDash@addInfo')->middleware('permission:snrStaff');
            Route::get('/delete/{id}', 'TrainingDash@deleteInfo')->middleware('permission:snrStaff');
            Route::prefix('public')->middleware('permission:snrStaff')->group(function() {
                Route::post('/new-section', 'TrainingDash@newPublicInfoSection');
                Route::post('/edit-section/{id}', 'TrainingDash@editPublicSection');
                Route::get('/remove-section/{id}', 'TrainingDash@removePublicInfoSection');
                Route::post('/add-pdf/{id}', 'TrainingDash@addPublicPdf');
                Route::get('/remove-pdf/{id}', 'TrainingDash@removePublicPdf');
            });
        });
    });

    Route::prefix('admin')->group(function() {
        Route::prefix('announcement')->middleware('permission:staff')->group(function() {
            Route::get('/', 'AdminDash@setAnnouncement');
            Route::post('/', 'AdminDash@saveAnnouncement');
        });
        Route::prefix('audits')->middleware('permission:snrStaff')->group(function() {
            Route::get('/', 'AdminDash@showAudits');
        });
        Route::prefix('calendar')->middleware('permission:staff')->group(function() {
            Route::get('/', 'AdminDash@viewCalendar');
            Route::get('/view/{id}', 'AdminDash@viewCalendarEvent');
            Route::get('/new', 'AdminDash@newCalendarEvent');
            Route::post('/new/save', 'AdminDash@storeCalendarEvent');
            Route::get('/edit/{id}', 'AdminDash@editCalendarEvent');
            Route::post('/edit/{id}/save', 'AdminDash@saveCalendarEvent');
            Route::delete('/delete/{id}', 'AdminDash@deleteCalendarEvent');
            Route::post('/edit/vis/{id}', 'AdminDash@toggleCalenderEventVisibilty');
        });
        Route::prefix('scenery')->middleware('permission:scenery')->group(function() {
            Route::get('/', 'AdminDash@showScenery');
            Route::get('/view/{id}', 'AdminDash@viewScenery');
            Route::get('/new', 'AdminDash@newScenery');
            Route::post('/new', 'AdminDash@storeScenery');
            Route::get('/edit/{id}', 'AdminDash@editScenery');
            Route::post('/edit/{id}/save', 'AdminDash@saveScenery');
            Route::delete('/delete/{id}', 'AdminDash@deleteScenery');
        });
        Route::prefix('files')->middleware('permission:files')->group(function() {
            Route::get('/upload', 'AdminDash@uploadFile');
            Route::post('/upload', 'AdminDash@storeFile');
            Route::get('/edit/{id}', 'AdminDash@editFile');
            Route::post('/edit/{id}', 'AdminDash@saveFile');
            Route::get('/delete/{id}', 'AdminDash@deleteFile');
			Route::get('/disp-order', 'AdminDash@updateFileDispOrder');
        });
        Route::prefix('airports')->middleware('permission:staff')->group(function() {
            Route::get('/', 'AdminDash@showAirports');
            Route::get('/new', 'AdminDash@newAirport');
            Route::post('/new', 'AdminDash@storeAirport');
            Route::get('/add-to-home/{id}', 'AdminDash@addAirportToHome');
            Route::get('/del-from-home/{id}', 'AdminDash@removeAirportFromHome');
            Route::delete('/delete/{id}', 'AdminDash@deleteAirport');
        });
        Route::prefix('events')->middleware('permission:events')->group(function() {
            Route::get('/new', 'AdminDash@newEvent');
            Route::post('/new', 'AdminDash@saveNewEvent');
            Route::post('/positions/add/{id}', 'AdminDash@addPosition');
            Route::get('/position/delete/{id}', 'AdminDash@removePosition');
            Route::post('/positions/assign/{id}', 'AdminDash@assignPosition');
            Route::get('/positions/unassign/{id}', 'AdminDash@unassignPosition');
            Route::post('/positions/manual-assign/{id}', 'AdminDash@manualAssign');
            Route::get('/edit/{id}', 'AdminDash@editEvent');
            Route::post('/edit/{id}', 'AdminDash@saveEvent');
            Route::get('/delete/{id}', 'AdminDash@deleteEvent');
            Route::get('/toggle-reg/{id}', 'AdminDash@toggleRegistration');
            Route::get('/set-active/{id}', 'AdminDash@setEventActive');
            Route::get('/hide/{id}', 'AdminDash@hideEvent');
            Route::post('/save-preset/{id}', 'AdminDash@setEventPositionPreset');
            Route::post('/load-preset/{id}', 'AdminDash@retrievePositionPreset');
            Route::post('/delete-preset', 'AdminDash@deletePositionPreset');
        });
        Route::prefix('roster')->middleware('permission:roster')->group(function() {
            Route::get('/visit/requests', 'AdminDash@showVisitRequests');
            Route::get('/visit/accept/{id}', 'AdminDash@acceptVisitRequest');
            Route::post('/visit/manual-add/search', 'AdminDash@manualAddVisitor');
            Route::get('/visit/manual-add', 'AdminDash@manualAddVisitorForm');
            Route::post('/visit/accept/save', 'AdminDash@storeVisitor');
            Route::post('/visit/reject/{id}', 'AdminDash@rejectVisitRequest');
            Route::get('/visit/requests/view/{id}', 'AdminDash@viewVisitRequest');
			Route::get('/visit/remove/{id}', 'AdminDash@removeVisitor');
            Route::get('/visit-agreement/reject/{id}', 'AdminDash@disallowVisitReq');
            Route::post('/visit-agreement/permit', 'AdminDash@allowVisitReq');
            Route::get('/purge-assistant/{year?}/{month?}', 'AdminDash@showRosterPurge');
        });
        Route::prefix('roster')->middleware('permission:roster|train')->group(function() {
            Route::get('/edit/{id}', 'AdminDash@editController');
            Route::post('/edit/{id}', 'AdminDash@updateController');
        });
        Route::prefix('bronze-mic')->middleware('permission:snrStaff')->group(function() {
            Route::get('/{year?}/{month?}', 'AdminDash@showBronzeMic');
            Route::post('/{year}/{month}/{hours}/{id}', 'AdminDash@setBronzeWinner');
            Route::get('/remove/{id}/{year}/{month}', 'AdminDash@removeBronzeWinner');
        });
        Route::prefix('pyrite-mic')->middleware('permission:snrStaff')->group(function() {
            Route::get('/{year?}', 'AdminDash@showPyriteMic');
            Route::post('/{year}/{hours}/{id}', 'AdminDash@setPyriteWinner');
            Route::get('/remove/{id}/{year}', 'AdminDash@removePyriteWinner');
        });
        Route::prefix('feedback')->middleware('permission:snrStaff')->group(function() {
            Route::get('/', 'AdminDash@showFeedback');
            Route::post('/save/{id}', 'AdminDash@saveFeedback');
            Route::post('/hide/{id}', 'AdminDash@hideFeedback');
            Route::post('/update/{id}', 'AdminDash@updateFeedback');
            Route::post('/email/{id}', 'AdminDash@emailFeedback');
        });
        Route::prefix('email')->middleware('permission:email')->group(function() {
            Route::get('/send', 'AdminDash@sendNewEmail');
            Route::post('/send', 'AdminDash@sendEmail');
        });
        Route::prefix('incident')->middleware('permission:snrStaff')->group(function() {
            Route::get('/', 'AdminDash@incidentReportIndex');
            Route::get('/view/{id}', 'AdminDash@viewIncidentReport');
            Route::get('/archive/{id}', 'AdminDash@archiveIncident');
            Route::get('/delete/{id}', 'AdminDash@deleteIncident');
        });
    });
});
/*
*   End Controller Dashboard
*/

/*
*   Discord API
*/
Route::get('/discord-api', 'DiscordController@returnDiscordInfo');
Route::get('/discord/login', 'DiscordController@loginToDiscord');
Route::get('/discord/login/verify', 'DiscordController@completeDiscordLogin');
Route::get('/discord/logout', 'DiscordController@logoutOfDiscord');
/*
*   End Discord API
*/

/*
*   Cron Jobs
*   URL: https://ztlv2.team-stringer.com/cron-job/run?j=[Cron Job]&t=[Token]
*/
//Route::get('/cron-job/run', 'CronController@index');
/*
*   End Cron Job
*/

/*
*	Webmaster Permission Grant
*	Use this to grant yourself webmaster privileges. Should be disabled for security reasons.
*/

Route::get('/laratrust', function() {
/*	$user = App\User::find(1315134);

	$user->attachRole('wm');
 */
});

/*
*	End Webmaster Permission Grant
*/
