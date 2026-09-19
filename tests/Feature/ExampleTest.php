<?php

use Database\Seeders\OlaraDatabaseSeeder;

test('the application returns a successful response', function () {
    $this->seed(OlaraDatabaseSeeder::class);
    $response = $this->get('/');

    $response->assertStatus(200);
});
