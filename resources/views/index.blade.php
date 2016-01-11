@extends('layouts.public')

@section('content')
<div class="jumbotron">
	<h1>Laravel 5.2 Boilerplate</h1>
	<p>Congratulations! Your site is up and running!</p>
</div>
<hr>
<h2>Feature Samples</h2>
<p>Below are some samples of the features included in this package</p>

<h3>Bootbox</h3>
<button class="btn" id="sample-bootbox-alert">Alert</button>
<button class="btn" id="sample-bootbox-confirm">Confirmation</button>

<h3>Blade Extensions</h3>
@define $thisDate = new \DateTime
<p>datetime(): @datetime($thisDate)</p>
<p>isodate(): @isodate($thisDate)</p>
<p>verbosedate(): @verbosedate($thisDate)</p>
<p>verboseshortdate(): @verboseshortdate($thisDate)</p>

<h3>Loading Overlay</h3>
<button class="btn" id="show-loading-overlay">Show Overlay</button>

<h3>Link Buttons</h3>
<button class="btn" class="cl-js-link-button" data-href="http://www.cnn.com">Open CNN in this tab</button>
<button class="btn" class="cl-js-link-button-new" data-href="http://www.cnn.com">Open CNN in a new tab</button>

<p>&nbsp;</p>
@endsection

@section('script')
<script type="text/javascript">
$(document).ready(function () {
	$("#sample-bootbox-alert").click(function () {
		bootbox.alert("You clicked the alert button!");
	});
	$("#sample-bootbox-confirm").click(function () {
		bootbox.confirm("Are you sure that you clicked confirm?", function (result) {
            if (result === true) {
                bootbox.alert("You clicked yes.")
            } else {
                bootbox.alert("You clicked no.")
            }
        });
	});
	$("#show-loading-overlay").click(function () {
		clShowLoading();
		setTimeout(clHideLoading, 4000);
	});
});
</script>
@endsection