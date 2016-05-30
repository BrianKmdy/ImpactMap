var map;
var markers = [];
var markerCluster;
var test = false;

/** 
* Called when the web page is loaded. Initializes the search suggestion engine and readies the search bar for queries.
*/
$(document).ready(function () {
	// Create the Bloodhound suggestion engine object
	projects = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		prefetch: {
			url: '../json/search.json',
			filter: function (projects) {
				return $.map(projects, function (project) {
					return {
						name: project
					};
				});
			}
		}
	});

	// Clear the prefetch cache in case it's been updated (projects added/edited/deleted)
	projects.clearPrefetchCache();

	// Initialize the Bloodhound suggestion engine
	projects.initialize();

	// Instantiate the Typeahead UI
	$('#searchbar').typeahead(null, {
		displayKey: 'name',
		source: projects.ttAdapter()
	});

	// When the user presses enter in the search bar send a search request to the server
	$('#searchbar').keypress(function (event) {
		if (event.which == 13) {
			// Convert all of the words in the user's search query to their root form (i.e. running -> run)
			var stemmer = new Snowball("english");
			var searchWords = $("#searchbar").val().split(" ");
			searchWords.forEach(function (word, i, words) {
				stemmer.setCurrent(word);
				stemmer.stem();
				words[i] = stemmer.getCurrent();
			});
			console.log(searchWords.join(" "));

			// Send the search terms to the server and print any matches
			$.ajax({
				type: "POST",
				url: "../php/map/search.php",
				data: { stemmedSearchText: searchWords.join(" ") },
				data_type: "json",
				success: function (data) {
					console.log(data);
				}
			});
		}
	});

	$('#filter-button').click(function (event) {
		event.preventDefault();                         //Prevents button default event
		var projectCenter = $('#centers').val();        //Default: -1 (all)
		var projectType = $('#project-type').val();     //Default: -1 (all)
		var projectStatus = $("#project-status").val(); //Default: -1 (all)
		var startDate = $('#start-date').val();         //Default: empty (all)
		var endDate = $('#end-date').val();             //Default: empty (all)


	});
});

//Shows lightbox, call this function when map marker is clicked. Parameters is the project ID
function lightboxPopup(pid) {
	$.ajax({
		type: 'POST',
		url: '../php/map/get_project.php',
		data: {
			pid: pid
		},
		dataType: json,
		success: function (data) {
			//Do stuff here... data is in JSON with same column names as Projects table

		},
		complete: function () {

		}
	})
}

/* Initialize map elements and markers */
function initMap() {
	var map = new google.maps.Map(document.getElementById('map'), {
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		center: {
			lat: 38.5420697,
			lng: -121.7731997
		},
		zoom: 7,
		zoomControl: true,
		zoomControlOptions: {
			position: google.maps.ControlPosition.RIGHT_CENTER
		},
		mapTypeControl: false,
		scaleControl: true,
		streetViewControl: false,
		rotateControl: true,
		fullscreenControl: false,
		minZoom: 3,
		styles: [{
			stylers: [{
				visibility: "off"
			}, {
				hue: "#bdbdbd"
			}]
		}, {
			featureType: "water",
			elementType: "geometry",
			stylers: [{
				visibility: "on"
			}, {
				color: "#616161"
			}]
		}, {
			featureType: "administrative.province",
			elementType: "labels",
			stylers: [{
				visibility: "on"
			}, {
				saturation: -100
			}]
		}, {
			featureType: "administrative.locality",
			elementType: "labels",
			stylers: [{
				visibility: "on"
			}, {
				saturation: -100
			}]
		}, {
			featureType: "administrative",
			elementType: "labels.text.fill",
			stylers: [{
				visibility: "on"
			}, {
				color: "#444444"
			}, {
				saturation: -100
			}]
		}, {
			featureType: "landscape",
			elementType: "all",
			stylers: [{
				visibility: "on"
			}, {
				color: "#f2f2f2"
			}]
		}, {
			featureType: "landscape.man_made",
			elementType: "geometry",
			stylers: [{
				visibility: "off"
			}]
		}, {
			featureType: "landscape.man_made",
			elementType: "labels",
			stylers: [{
				visibility: "off"
			}]
		}, {
			featureType: "landscape.natural.landcover",
			elementType: "geometry",
			stylers: [{
				visibility: "off"
			}]
		}, {
			featureType: "landscape.natural.landcover",
			elementType: "labels",
			stylers: [{
				visibility: "off"
			}]
		}, {
			featureType: "landscape.natural.terrain",
			elementType: "geometry",
			stylers: [{
				visibility: "off"
			}]
		}, {
			featureType: "landscape.natural.terrain",
			elementType: "labels",
			stylers: [{
				visibility: "off"
			}]
		}]
	});
	/* End of var map */
}

function loadProjects() {
	$.ajax({
		type: 'POST',
		url: '../php/map/filter.php',
		data: {
			center: projectCenter,
			type: projectType,
			status: projectStatus,
			start: startDate,
			end: endDate
		},
		dataType: json,
		success: function (data) {
			//Do stuff here... data is JSON with keys: 'pid', 'title', 'lat', 'lng'

		},
		complete: function () {
			$('#filter-modal').hide();          //Close the filter modal after code runs
		}
	});

	var projects;
	if (test)
		projects = data.photos;
	else
		projects = loadProjects();

	/* Marker creation dependant on json */
	for (var i = 0; i < projects.length; ++i) {
		var project = projects[i];
		var projectInfo = "Basic project info here";
		var latLng = new google.maps.LatLng(project.latitude,
            project.longitude);
		var iWindow = new google.maps.InfoWindow({ //create infow windows for each marker
			position: latLng,
			content: projectInfo
		});
		var marker = new google.maps.Marker({
			map: map,
			position: latLng,
			zIndex: i,
			// icon: project.center // last element of the array
		});
		marker.addListener('click', function () {
			map.setZoom(12); // zoom into marker position
			map.setCenter(this.getPosition()); // center map on marker
			startLB();
		});
		marker.addListener('mouseover', function () {
			iWindow.open(map, this);
		});
		marker.addListener('mouseout', function () {
			iWindow.close();
		})
		markers.push(marker);
	}
	/* Marker cluster (non-standard markers with numbers) */
	var markerCluster = new MarkerClusterer(map, markers);
}


var contentString = "HELLO";

function startLB() {    // start light box on click 
	var lbBG = document.getElementById('lbBackground');
	var lbFG = document.getElementById('lb');

	lbBG.style.display = "block";
	lbFG.style.display = "block";

	var info = contentString;

	document.getElementById('info1').innerHTML = info;
}

function dismissLB() {  // dismiss light box on clicking outside
	var lbBG = document.getElementById('lbBackground');
	var lbFG = document.getElementById('lb');

	lbBG.style.display = "none";
	lbFG.style.display = "none";

	document.getElementById('info1').innerHTML = "";
}