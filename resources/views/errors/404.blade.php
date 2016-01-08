<?php
try {
    // You can test for different user types and update the layout
    //$user = Auth::user();
    $layout = 'layouts.public';
} catch (\Exception $e) {
    $layout = 'layouts.public';
}
$page_title = 'Not Found';
?>
@extends($layout)

@section('content')
    <div class="cl-error-page-content">
        <h1>Whoops!</h1>
        <p>We could not find the page you requested.</p>
    </div>
@endsection
