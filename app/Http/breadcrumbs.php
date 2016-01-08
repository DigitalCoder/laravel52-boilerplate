<?php

/* Below are a couple of example breadcrumbs, showing inheritance
//Admin Programs
Breadcrumbs::register('admin-programs', function ($breadcrumbs) {
    $breadcrumbs->push('Programs', route('admin-programs'));
});

//Admin Program
Breadcrumbs::register('admin-program', function ($breadcrumbs, $program) {
    $breadcrumbs->parent('admin-programs');
    if ($program) {
        $breadcrumbs->push($program->name, route('admin-program', $program->id));
    } else {
        $breadcrumbs->push("Create a Program");
    }
});

To add these to a layout, you would add this line:
{!! Breadcrumbs::render('admin-program', $program) !!}

*/
