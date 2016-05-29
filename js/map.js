var map;
var markers = [];

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
            data: {stemmedSearchText: searchWords.join(" ")},
            data_type: "json",
            success: function(data) {
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

        $.ajax ({
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
            success: function(data) {
                //Do stuff here... data is JSON with keys: 'pid', 'title', 'lat', 'lng'

            },
            complete: function() {
                $('#filter-modal').hide();          //Close the filter modal after code runs
            }
        });
    });
});

//Shows lightbox, call this function when map marker is clicked. Parameters is the project ID
function lightboxPopup(pid) {
    $.ajax ({
        type: 'POST',
        url: '../php/map/get_project.php',
        data: {
            pid: pid
        },
        dataType: json,
        success: function(data) {
            //Do stuff here... data is in JSON with same column names as Projects table

        },
        complete: function() {

        }
    })
}


function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    center: { lat: 38.5420697, lng: -121.7731997 },
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
    styles: [{ "featureType": "administrative", "elementType": "labels.text.fill", "stylers": [{ "color": "#444444" }] },
              { "featureType": "administrative.country", "elementType": "labels", "stylers": [{ "visibility": "off" }] },
              { "featureType": "administrative.province", "elementType": "labels", "stylers": [{ "visibility": "on" }] },
              { "featureType": "administrative.locality", "elementType": "geometry", "stylers": [{ "visibility": "off" }] },
              { "featureType": "administrative.locality", "elementType": "labels", "stylers": [{ "visibility": "on" }] },
              { "featureType": "administrative.neighborhood", "elementType": "geometry", "stylers": [{ "visibility": "off" }] },
              { "featureType": "administrative.neighborhood", "elementType": "labels", "stylers": [{ "visibility": "off" }] },
              { "featureType": "administrative.land_parcel", "elementType": "geometry", "stylers": [{ "visibility": "off" }] },
              { "featureType": "administrative.land_parcel", "elementType": "labels", "stylers": [{ "visibility": "off" }] },
              { "featureType": "landscape", "elementType": "all", "stylers": [{ "color": "#f2f2f2" }] },
              { "featureType": "landscape.man_made", "elementType": "geometry", "stylers": [{ "visibility": "off" }] },
              { "featureType": "landscape.man_made", "elementType": "labels", "stylers": [{ "visibility": "off" }] },
              { "featureType": "landscape.natural.landcover", "elementType": "geometry", "stylers": [{ "visibility": "off" }] },
              { "featureType": "landscape.natural.landcover", "elementType": "labels", "stylers": [{ "visibility": "off" }] },
              { "featureType": "landscape.natural.terrain", "elementType": "geometry", "stylers": [{ "visibility": "off" }] },
              { "featureType": "landscape.natural.terrain", "elementType": "labels", "stylers": [{ "visibility": "off" }] },
              { "featureType": "poi", "elementType": "all", "stylers": [{ "visibility": "off" }] },
              { "featureType": "poi", "elementType": "labels.text", "stylers": [{ "visibility": "off" }] },
              { "featureType": "road", "elementType": "all", "stylers": [{ "saturation": -100 }, { "lightness": 45 }] },
              { "featureType": "road.highway", "elementType": "all", "stylers": [{ "visibility": "simplified" }] },
              { "featureType": "road.highway", "elementType": "geometry", "stylers": [{ "visibility": "off" }] },
              { "featureType": "road.highway", "elementType": "labels", "stylers": [{ "visibility": "off" }] },
              { "featureType": "road.highway.controlled_access", "elementType": "geometry", "stylers": [{ "visibility": "off" }] },
              { "featureType": "road.arterial", "elementType": "geometry", "stylers": [{ "visibility": "on" }] },
              { "featureType": "road.arterial", "elementType": "labels", "stylers": [{ "visibility": "off" }] },
              { "featureType": "road.arterial", "elementType": "labels.icon", "stylers": [{ "visibility": "off" }] },
              { "featureType": "road.local", "elementType": "geometry", "stylers": [{ "visibility": "off" }] },
              { "featureType": "road.local", "elementType": "labels", "stylers": [{ "visibility": "off" }] },
              { "featureType": "transit", "elementType": "all", "stylers": [{ "visibility": "off" }] },
              { "featureType": "transit.station", "elementType": "all", "stylers": [{ "visibility": "off" }] },
              { "featureType": "water", "elementType": "all", "stylers": [{ "color": "#616161" }, { "visibility": "on" }] }]
      });

  if (test)
    loadTestProjects();
  else
    loadProjects();

  /* For iOS and Android */
  var useragent = navigator.userAgent;
  if (useragent.indexOf('iPhone') != -1 || useragent.indexOf('Android') != -1) {
    map.style.width = '100%';
    map.style.height = '100%';
  } else {
    map.style.width = '600px';
    map.style.height = '800px';
  }
}

function loadTestProjects() {
  for (var i = 0; i < 1000; i++) {
      var dataPhoto = data.photos[i];
      var latLng = new google.maps.LatLng(dataPhoto.latitude,
          dataPhoto.longitude);
      var marker = new google.maps.Marker({
        position: latLng
      });
      markers.push(marker);
  }
  clusterMarkers();
}

function loadProjects() {
  // Request all the projects that meet the filters
  $.ajax({
    type: "POST",
    url: "php/admin/projects/load_projects.php",
    data: {},
    data_type: "json",
    // On a successful request, populate the map with markers for each project and add user click interactions
    success: function(data) {
      var projects = JSON.parse(data);
      projects.forEach(function(project) {
        var marker = new google.maps.Marker({
                              position: {lat: project.lat, lng: project.lng},
                              map: map,
                              title: project.title
        });
        markers.push(marker);
      });
      clusterMarkers();
    }
  });
}

function clusterMarkers() {
   var markerCluster = new MarkerClusterer(map, markers, {imagePath: "../img/map/m"});
}


function openSearch() {
  document.getElementById("search").style.width = "100px";
}

function closeSearch() {
  document.getElementById("search").style.width = "0%";
}