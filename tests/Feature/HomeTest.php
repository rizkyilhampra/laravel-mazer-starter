<?php

use function Pest\Laravel\get;

it('guests cannot access the home page', function () {
    $response = get('/');

    $response->assertStatus(302);
    $response->assertRedirect('/login');
});
