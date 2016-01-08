<?php
try {
    // You can test for different user types and update the layout
    //$user = Auth::user();
    $layout = 'layouts.public';
} catch (\Exception $e) {
    $layout = 'layouts.public';
}
$page_title = 'Unauthorized';
?>
@extends($layout)

@section('content')
	<div class="cl-error-page-content">
		<h1>You are not authorized to access this page</h1>
		<p>{{$exception->getMessage()}}</p>
	</div>
@endsection
