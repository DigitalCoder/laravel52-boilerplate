<script>
	var analyticsPropertyId = "{{ $analyticsPropertyId }}";
	@if(!is_null($analyticsPropertyId))
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		// Send the page view
		ga('create', '{{ $analyticsPropertyId }}', 'auto');
		<?php
			/* To send custom dimensions, add lines like the following:
			// Custom dimensions
			@if(!is_null($analyticsUserType))
			ga('set', 'dimension1', '{{ $analyticsUserType }}');
			@endif
			*/
		?>

		ga('send', 'pageview');
	@endif

</script>