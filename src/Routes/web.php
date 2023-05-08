<?php

Route::prefix('unusual-form')->name('unusual_form.')->group(function() {
    Route::get('/filepond/{id}', 'FilePondController@preview')->name('filepond.preview');
    Route::post('/filepond', 'FilePondController@upload')->name('filepond.upload');
    Route::delete('/filepond', 'FilePondController@delete')->name('filepond.delete');
});
