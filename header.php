<?php /* header.php */ ?>
<!DOCTYPE html>
<html lang="en" manifest="cache-manifest.mf">
<head>
	<meta charset="UTF-8" />
	<title><?php echo $title; ?>Offline Contact App</title>
	<link type="text/css" href="css/stylesheet.css" rel="stylesheet" media="screen" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<script type="text/javascript" src="js/jquery-min.js"></script>
	<script type="text/javascript">
		var appCache = window.applicationCache;

		switch(appCache.status) {
			case appCache.UNCACHED: // UNCACHED == 0
				return 'UNCACHED';
				break;
			case appCache.IDLE: // IDLE == 1
				return 'IDLE';
				break;
			case appCache.CHECKING: // CHECKING == 2
				return 'CHECKING';
				break;
			case appCache.DOWNLOADING: // DOWNLOADING == 3
				return 'DOWNLOADING';
				break;
			case appCache.UPDATEREADY:  // UPDATEREADY == 4
				return 'UPDATEREADY';
				break;
			case appCache.OBSOLETE: // OBSOLETE == 5
				return 'OBSOLETE';
				break;
			default:
				return 'UKNOWN CACHE STATUS';
				break;
		};

		appCache.update(); // Attempt to update the user's cache.

		if(appCache.status == window.applicationCache.UPDATEREADY) {
			appCache.swapCache();  // The fetch was successful, swap in the new cache.
		}

		// Check if a new cache is available on page load.
		window.addEventListener('load', function(e) {
			window.applicationCache.addEventListener('updateready', function(e) {
				if (window.applicationCache.status == window.applicationCache.UPDATEREADY) {
					// Browser downloaded a new app cache.
					// Swap it in and reload the page to get the new hotness.
					window.applicationCache.swapCache();
					if(confirm('A new version of this site is available. Load it?')) {
						window.location.reload();
					}
				} else {
					// Manifest didn't changed. Nothing new to server.
				}
			}, false);
		}, false);

		function handleCacheEvent(e) {
			//...
		}

		function handleCacheError(e) {
			alert('Error: Cache failed to update!');
		};

		// Fired after the first cache of the manifest.
		appCache.addEventListener('cached', handleCacheEvent, false);
		// Checking for an update. Always the first event fired in the sequence.
		appCache.addEventListener('checking', handleCacheEvent, false);
		// An update was found. The browser is fetching resources.
		appCache.addEventListener('downloading', handleCacheEvent, false);
		// The manifest returns 404 or 410, the download failed,
		// or the manifest changed while the download was in progress.
		appCache.addEventListener('error', handleCacheError, false);
		// Fired after the first download of the manifest.
		appCache.addEventListener('noupdate', handleCacheEvent, false);
		// Fired if the manifest file returns a 404 or 410.
		// This results in the application cache being deleted.
		appCache.addEventListener('obsolete', handleCacheEvent, false);
		// Fired for each resource listed in the manifest as it is being fetched.
		appCache.addEventListener('progress', handleCacheEvent, false);
		// Fired when the manifest resources have been newly redownloaded.
		appCache.addEventListener('updateready', handleCacheEvent, false);
	</script>
</head>
<body onload="checkLocalStorage();">
