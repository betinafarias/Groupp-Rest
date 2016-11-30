<?php

$app->get('/', function () {
	echo "Groupp";
});

// -----------------------------------
// Musician
// -----------------------------------
$app->get('/musicians','getMusicians');
$app->get('/musicians/:id','getMusician');
$app->post('/musicians', 'addMusician');
$app->post('/musicians/:id','saveMusician');
$app->delete('/musicians/:id','deleteMusician');


// -----------------------------------
// Artist
// -----------------------------------
$app->post('/artists/:musicianId','addArtists');


// -----------------------------------
// States
// -----------------------------------
$app->get('/states','getStates');

// -----------------------------------
// Cities
// -----------------------------------
$app->get('/cities/:stateId','getCities');

// -----------------------------------
// Instruments
// -----------------------------------
$app->get('/instruments','getInstruments');

// -----------------------------------
// Login
// -----------------------------------
$app->post('/login','login');


$app->run();