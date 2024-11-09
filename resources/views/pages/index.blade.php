<?php

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;

name('dashboard');
middleware('auth');

?>

<x-layouts.app title="Dashboard" page-title="Dashboard">


</x-layouts.app>
