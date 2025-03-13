// Theme Settings Routes
Route::group([
    'prefix' => 'admin',
    'middleware' => [
        'auth',
        'admin',
        'throttle:60,1',
        'cache.headers:public;max_age=3600;etag',
        'theme.settings.access'
    ]
], function () {
    Route::get('/theme-settings', 'Admin\ThemeSettingsController@getSettings')
        ->middleware('throttle:120,1');
    Route::post('/theme-settings', 'Admin\ThemeSettingsController@saveSettings')
        ->middleware('throttle:30,1');
    Route::post('/theme-settings/reset', 'Admin\ThemeSettingsController@resetSettings')
        ->middleware('throttle:10,1');
    Route::get('/theme-settings/preset/{theme}', 'Admin\ThemeSettingsController@getPresetTheme')
        ->middleware('throttle:60,1');
    Route::post('/theme-settings/clear-cache', 'Admin\ThemeSettingsController@clearCache')
        ->middleware(['throttle:5,1', 'admin.super']);
}); 