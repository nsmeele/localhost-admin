function updateClock () {

	var currentTime = new Date ( );

	// Get time details
	var currentHours = currentTime.getHours ( );
	var currentMinutes = currentTime.getMinutes ( );
	var currentSeconds = currentTime.getSeconds ( );

	// Pad the minutes and seconds with leading zeros, if required
	currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
	currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;
	// Choose either "AM" or "PM" as appropriate
	var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";
	// // Convert the hours component to 12-hour format if needed
	currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;
	// // Convert an hours component of "0" to "12"
	currentHours = ( currentHours == 0 ) ? 12 : currentHours;
	// Compose the string for display
	var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;

	// Update the time display
	document.getElementById("clock").firstChild.nodeValue = currentTimeString;

	// See the console log
	// console.log( "Updated at:"  + currentTimeString );


}


function updateDate () {

	var currentTime = new Date ( );

	// Get date details
	var currentYear = currentTime.getFullYear ( );
	// var currentMonth = currentTime.getMonth( ) + 1 ;
	var currentDate = currentTime.getDate( );
	var currentDay = currentTime.getDay( );

	var weekday = new Array(7);
		weekday[0] = "Zondag";
		weekday[1] = "Maandag";
		weekday[2] = "Dinsdag";
		weekday[3] = "Woensdag";
		weekday[4] = "Donderdag";
		weekday[5] = "Vrijdag";
		weekday[6] = "Zaterdag";
	var currentDay = weekday[currentTime.getDay()];

	var month = new Array(12);
		month[1] = "januari";
		month[2] = "februari";
		month[3] = "maart";
		month[4] = "april";
		month[5] = "mei";
		month[6] = "juni";
		month[7] = "juli";
		month[8] = "augustus";
		month[9] = "september";
		month[10] = "oktober";
		month[11] = "november";
		month[12] = "december";
	var currentMonth = month[currentTime.getMonth() + 1];


	var currentDateString = currentDay + ", " + currentDate + " " + currentMonth + " " + currentYear;

	// Update the date
	document.getElementById("date").firstChild.nodeValue = currentDateString;


}



$(document).ready(function($) {

	updateClock();
	updateDate();

	setClock = setInterval( updateClock, 1000 );
	setDate = setInterval( updateDate, 1000 * 60 )	

}) 