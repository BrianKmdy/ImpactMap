var map;
var pins = [];
var markers = [];
var iWindows = [];
var markerCluster;
var centers;

var projectCenter = -1;
var projectType = -1;
var projectStatus = -1;
var startDate = "1970-01-01";
var endDate = "3000-01-01";

var statuses = ["Planned", "Ongoing", "Completed"]; 
var types = ["Research", "Audit", "Deomonstration", "Others"];

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
			if ($("#searchbar").val().length > 0) {
				// Convert all of the words in the user's search query to their root form (i.e. running -> run)
				var stemmer = new Snowball("english");
				var searchWords = $("#searchbar").val().split(" ");
				searchWords.forEach(function (word, i, words) {
					stemmer.setCurrent(word);
					stemmer.stem();
					words[i] = stemmer.getCurrent();
				});

				// Send the search terms to the server and print any matches
				$.ajax({
					type: "POST",
					url: "../php/map/search.php",
					data: { stemmedSearchText: searchWords.join(" ") },
					data_type: "json",
					success: function (data) {
						showProjects(JSON.parse(data));
					}
				});

				toggleSideBar();
			} else {
				getProjects();
			}
		}
	});

	$.ajax({
		type: 'POST',
		url: '../php/map/centers.php',
		success: function(data){
			centers = JSON.parse(data);

			generatePins();
			
			for(i=0; i<centers.length; ++i){
				var center = centers[i];
				var filterContent = '<option value="' + center.cid + '">'+ center.name + '</option>'; 
				$(filterContent).appendTo('#centerList');

				var navContent = '<li><a href="#" data-toggle="tooltip" style="background-color: ' + center.color + ' !important;" onclick="filterCenter(' + center.cid + ')" title="' + center.name + '">' + center.acronym + '</a></li>';
				$(navContent).appendTo('#centersNav');
			}

			$('[data-toggle="tooltip"]').tooltip(); 

			getProjects();
		}
	});

	$('#filter-button').click(function (event) {
		event.preventDefault();                         //Prevents button default event
		projectCenter = $('#centerList').val();        //Default: -1 (all)
		projectType = $('#typeList').val();     //Default: -1 (all)
		projectStatus = $("#statusList").val(); //Default: -1 (all)
		startDate = $('#datepicker').val();         //Default: empty (all)
		endDate = $('#datepicker2').val();             //Default: empty (all)

		getProjects();
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
		dataType: "json",
		success: function (project) {
			$("#center").html(project.centerName);
			$("#project").html(project.title);
			$("#status").html(statuses[project.status]);
			$("#type").html(types[project.type]);
			$("#date").html("Started on " + project.startDate + ((project.endDate.length > 0) ? "<br>Finished on " + project.endDate : ""));
			$("#location").html(((project.buildingName.length > 0) ? project.buildingName + "<br>" : "") + project.address + ", " + project.zip);
			$("#summary").html(project.summary);
			$("#results").html(project.results);
			$("#learn").html((project.link.length > 0) ? "<b>Learn more</b><br><a href='" + project.link  + "'>" + project.link + "</a><br><br>" : "");
			$("#contact").html(project.contactName + "<br>" + project.email + ((project.phone.length > 0) ? "<br>" + project.phone : ""));
			$("#fundedBy").html(project.fundedBy);
			$("#pic").html((project.pic.length > 0) ? "<img src='" + project.pic  + "'>" : "");

			startLB();
		},
		complete: function () {

		}
	})
}

/* Initialize map elements and markers */
function initMap() {
	map = new google.maps.Map(document.getElementById('map'), {
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

function filterCenter(cid) {
	if (projectCenter == cid)
		projectCenter = -1;
	else
		projectCenter = cid;

	getProjects();
}

function getProjects() {
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
		dataType: "json",
		success: showProjects
	});
}

function showProjects(projects) {
	$('#list').html('');
	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(null);
	}
	markers = [];
	if (markerCluster != null)
		markerCluster.clearMarkers();
	iWindows = [];
	/* Marker creation dependant on json */
	if (projects != null) {
		for (var i = 0; i < projects.length; ++i) {
			var project = projects[i];
			var content = '<a class="mdl-navigation__link" href="">' + project.title + '</a>';
			$(content).appendTo('#list');
			//$('#list').html(content);
			var latLng = new google.maps.LatLng(project.lat,
	            project.lng);
			var iWindow = new google.maps.InfoWindow({ //create infow windows for each marker
				position: latLng,
				pid: project.pid,
				content: "<b>" + project.title + "</b><br>" + types[project.type] + "<br>" + project.address
			});
			iWindows[project.pid] = iWindow;
			var marker = new google.maps.Marker({
				map: map,
				position: latLng,
				pid: project.pid,
				icon: pins[project.cid],
				zIndex: i,
				// icon: project.center // last element of the array
			});
			marker.addListener('click', function () {
				map.setCenter(this.getPosition()); // center map on marker
				lightboxPopup(this.pid);
			});
			marker.addListener('mouseover', function () {
				iWindows[this.pid].open(map, this);
			});
			marker.addListener('mouseout', function () {
				iWindows[this.pid].close();
			})
			markers.push(marker);
		}
	}
	/* Marker cluster (non-standard markers with numbers) */
	markerCluster = new MarkerClusterer(map, markers);
	var projectWord = (markers.length == 1) ? "project" : "projects";
	$("#projectsVisible").html('<a class="navbar-brand" href="#" >' + markers.length + ' ' + projectWord + ' shown</a>');
}

function startLB() {    // start light box on click 
	var lbBG = document.getElementById('lbBackground');
	var lbFG = document.getElementById('lb');

	lbBG.style.display = "block";
	lbFG.style.display = "block";
}

function dismissLB() {  // dismiss light box on clicking outside
	var lbBG = document.getElementById('lbBackground');
	var lbFG = document.getElementById('lb');

	lbBG.style.display = "none";
	lbFG.style.display = "none";

	$("#center").html("");
	$("#project").html("");
	$("#status").html("");
	$("#type").html("");
	$("#date").html("");
	$("#location").html("");
	$("#summary").html("");
	$("#results").html("");
	$("#learn").html("");
	$("#contact").html("");
	$("#fundedBy").html("");
	$("#pic").html("");
}

function generatePins() {
	for (var i = 0; i < centers.length; i++) {
	    pins[centers[i].cid] = {
	        path: "m 322.79414,-542.08289 c -76.38702,0 -138.31093,61.92391 -138.31093,138.31093 -1e-5,76.38702 61.92391,138.31094 138.31093,138.31093 76.38702,0 138.31093,-61.92391 138.31092,-138.31093 0,-76.38701 -61.92391,-138.31093 -138.31092,-138.31093 z M 321.14947,-2.9465644 C 269.12522,-58.395774 172.70959,-161.39301 118.25538,-272.49881 103.02015,-302.46983 81.01559,-363.93459 81.497073,-401.8679 83.177089,-534.22679 184.40448,-642.60421 321.19429,-641.56513 c 137.36688,1.04347 242.10368,116.52782 237.83281,240.54469 -1.165,33.82908 -14.68019,92.32469 -33.63168,125.4493 C 439.2483,-118.31222 383.90747,-74.963164 321.14947,-2.9465644 Z",
	        fillColor: centers[i].color,
	        fillOpacity: 1,
	        strokeWeight: 0,
	        scale: 0.05,
	   };
	}
}

function toggleSideBar() {
    $('.mdl-layout__drawer-button').click();
}
