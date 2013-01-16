# Offline-Contact-Us #

A proof of concept for a contact form that accepts and saves input offline.

## Technologies ##

HTML5
	localStorage API
	Cache Manifest
	New Form Attributes &amp; Types
CSS3
PHP 5.2.17+
JavaScript
	jQuery 1.7.1

## How It Works ##

When the form is loaded (index.php), the page submits the manifest to be cached. This allows the page to be viewed offline as if it was online. jQuery is downloaded and cached to make sure all the JavaScript on the page works both online and offline. (jQuery and JavaScript will both be referred to as JS from herein for simplicity). The JS checks to see if the browser has internet connection or not. If it is not online, the JS prevents the submit button from submitting the data (process.php).

Once the form has been filled out, clicking the submit button checks the internet connection. If it is offline, it checks to see if the browser supports localStorage and either alerts the user that the contact form data will be saved for later submission or that it cannot save the data. Then, obviously, if it supports localStorage, it saves the submission data.

The next time the browser has an internet connection and the user goes to the contact form, an AJAX script is run (process_offline.php) to process the stored message and the user is alerted to the result of the new submission attempt.

This proof of concept uses a MySQL database to store the information as well as track visitors to the site using a simple cookie method. This is extra fluff and is no necessary for the application to work.

## Example In Action ##

http://cache.macmannis.com/
2013/01/16 15:42 EST