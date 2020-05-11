<?php

use App\Models\Country;

$paises = Country::all();

foreach ($paises as $pais){
    echo "$pais->nombre ($pais->iso)\n";
    echo '<br>';
}