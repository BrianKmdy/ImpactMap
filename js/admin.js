/*
    This file allows admins to interact with the system to add or edit projects,
    centers, project managers, or other users of the system (Only the root user
    can do this).

    It utlizies JQuery to make AJAX requests for PHP files from the server to
    update the information displayed on the page. These functions are called
    by the other PHP files that make up the admin pages.
*/

// Holds a reference to a google map to specifying the exact location of a project site when adding or editing a project
var projectPickerMap;
// Holds a google maps geocoder object to convert addresses into coordinates, used when a user adds or edits a project
var geocoder;
// Holds a reference to a google maps marker for the position of an individual project when adding or editting a project
var marker;
// This position is what the above marker will be set to when editing a project. The value is fetched from the server since a project being edited will already have a location.
var position;

var davis = {lat: 38.5449, lng: -121.7405};

/**
* Called when opening the add/edit project or view history dialog to show a small map that will show the location of a project.
*
* @param draggable A boolean value to specifiy whether or not the marker on the map will be draggable. Will be 'true' for add/edit of a project
*                  or 'false' when viewing history. This way you can specifiy a project's exact location by dragging the marker but you can't
*                  alter the history of a project.
*/

function initMap(draggable) {
    if (position == null)
        position = davis;

    projectPickerMap = new google.maps.Map($("#projectPickerMap").get(0), {
        center: position,
        zoom: 8,
        disableDefaultUI: true
    });

    marker = new google.maps.Marker({
        map: projectPickerMap,
        position: position,
        draggable: draggable,
        title: "Drag me!"
    });

    projectPickerMap.setOptions({styles: [ { featureType: "road", stylers: [ { visibility: "off" } ] },{ } ]});

    geocoder = new google.maps.Geocoder();
}

/**
* Called when the web page is loaded. Currently it initializes the dialog windows.
*/
$(document).ready( function() {
    if ($("#projects").hasClass("active"))
        loadProjects();
    else
        loadProfile();
    $('#impactModal').on('hidden.bs.modal', function () {
        marker = null;
        position = null;
    })
});

function showDateTimePicker() {
    $("#datetimepicker").datetimepicker({
        format: 'Y-m-d H:i',
        inline: true,
        onSelectTime:function(dp,$input){
            loadHistory();
            $('.dropdown.open .dropdown-toggle').dropdown('toggle');
        }
    });
}

/**
* When a new admin page is loaded it fills the content area, this is the callback that handles that. The requests for new pages are sent in other functions using ajax.
*
* @param data The html content returned from the server
*/
function contentCallback(data) {
	$("#content").html(data);
}

/** 
* Called when the dialog for adding/editing projects has been retrieved from the server and is ready to be shown
*
* @param data The html content returned from the server
*/
function projectPopupCallback(data) {
    //$("#popup").html(data);
    $("#impactModal").on("shown.bs.modal", function () {
        //google.maps.event.trigger(projectPickerMap, "resize");
        initMap(true);
    });
    $("#impactModal").html(data);
    $("#impactModal").modal('show');
    //initMap(true);
    $("#address").keyup(function() {
        geocoder.geocode({
            address: $("#address").val()
        }, geocodeCallback);
    });
    //$("#popup").scrollTop(0);
}

/**
* Called when the view history dialog is ready to be shown
*
* @param data The html content returned from the server
*/
function historyPopupCallback(data) {
    //$("#popup").html(data);
    $("#impactModal").on("shown.bs.modal", function () {
        //google.maps.event.trigger(projectPickerMap, "resize");
        initMap(false);
    });
    $("#impactModal").html(data);
    $("#impactModal").modal('show');
    //initMap(false);
    //$("#popup").scrollTop(0);
}

/**
* Called when any other content is ready to be shown in a popup dialog
*
* @param data The html content returned from the server
*/
function popupCallback(data) {
    //$("#popup").html(data);
    $("#impactModal").html(data);
    $("#impactModal").modal('show');
}

function centerCallback(data) {
    $("#impactModal").html(data);
    $("#impactModal").modal('show');
    $('#cp').colorpicker({
        customClass: 'colorpicker-2x', 
        sliders: {
            saturation: {
                maxLeft: 150,
                maxTop: 150
            },
            hue: {
                maxTop: 150
            },
            alpha: {
                maxTop: 150
            }
        }
    }); 

}

/**
* Called when the google maps API has retrieved GPS coordinates for a given address and the map is ready to be updated
*
* For more information on google maps geocoding see https://developers.google.com/maps/documentation/javascript/geocoding#Geocoding
*
* @param results An array of matches for the address.
* @param status Status code of the geocoding request.
*/
function geocodeCallback(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
        projectPickerMap.setCenter(results[0].geometry.location);
        if (marker == null) {
            marker = new google.maps.Marker({
                map: projectPickerMap,
                position: results[0].geometry.location,
                draggable: true,
                title: "Drag me!"
            });
        } else {
            marker.setPosition(results[0].geometry.location);
        }
    }
}

/**
* Called by admin.php when the user wants to view the table of projects
*/
function loadProjects() {
    $(".active").removeClass("active");
    $("#projects").addClass("active");
    $.ajax({
        type: "POST",
        url: "php/admin/projects/project_table.php",
        success: contentCallback
    });
}

/**
* Called by project_table.php when the user clicks on a project to edit it or when they click add project
*
* @param pid The id of the project to be edited. -1 if adding a project.
*/
function editProject(pid) {
    $.ajax({
        type: "POST",
        data: {pid: pid},
        data_type: "json",
        url: "php/admin/projects/edit_project.php",
        success: projectPopupCallback
    });
}

function validateProjectData(){
    var validInput = true;
    var string = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Invalid input:<br><ul> ";

    $("#titleGroup").removeClass("has-error");
    $("#startDateGroup").removeClass("has-error");
    $("#addressGroup").removeClass("has-error");
    $("#zipGroup").removeClass("has-error");
    $("#summaryGroup").removeClass("has-error");
    $("#resultsGroup").removeClass("has-error");
    $("#fundedByGroup").removeClass("has-error");

    var dateRegex = /^(20[0-9]{2})-(0[1-9]{1}|[1-9]{1}|1[1-2]{1})-([1-9]{1}|[1-3]{1}[1-9]{1})$/;

    if ($("#title").val().length < 1) 
    {
        $("#titleGroup").addClass("has-error");
        validInput = false;
        string = string.concat("<li><b>Title</b> cannot be empty</li>");
    }
    
    if($("#startDate").val().length < 1 || !dateRegex.test($("#startDate").val()))
    {
        $("#startDateGroup").addClass("has-error");
        validInput = false;
        if($("#startDate").val().length < 1)
            string = string.concat("<li><b>Start Date</b> cannot be empty</li>");
        else
            string = string.concat("<li><b>Start Date</b> must be of the form yyyy-mm-dd</li>");
    }

    if($("#address").val().length < 1)
    {
        $("#addressGroup").addClass("has-error");
        validInput = false;
        string = string.concat("<li><b>Address</b> cannot be empty</li>");
    }   

    if($("#zip").val().length < 1)
    {
        $("#zipGroup").addClass("has-error");
        validInput = false;
        string = string.concat("<li><b>Zip</b> cannot be empty</li>");
    }   

    if($("#summary").val().length < 1)
    {
        $("#summaryGroup").addClass("has-error");
        validInput = false;
        string = string.concat("<li><b>Summary</b> cannot be empty</li>");
    }

    if($("#results").val().length < 1)
    {
        $("#resultsGroup").addClass("has-error");
        validInput = false;
        string = string.concat("<li><b>results</b> cannot be empty</li>");
    }

    if($("#fundedBy").val().length < 1)
    {    
        $("#fundedByGroup").addClass("has-error");
        validInput = false;
        string = string.concat("<li><b>Funded By</b> cannot be empty</li>");
    }

    string = string.concat("</ul></div>");

    if (!validInput) {
        $("#invalidInputWarning").html(string);

        return false;
    }
    else
        return true;


}

function isValidDate(date) {
    if (Date.parse(date) == NaN)
        return false;
    else
        return true;
}

/**
* Called when a user is done making changes to a project and wishes to submit it. All the data is captured from the form and sent to submit_project_edit.php
*
* @param pid The id of the project being submitted
*/
function submitEditProject(pid) {
    //$("#impactModal").scrollTop(0);
    //$("html, body").scrollTop($("#impactModal").offset().top);
    //$(window).scrollTop(-15);
    if (!validateProjectData())
        return;

    // When a user submits a project certain fields are combined and then "stemmed", meaning the words are reduced to their roots (i.e. running -> run), and these are then stored as stemmedSearchText to be searched later
    var stemmer = new Snowball("english");
    var searchWords = ($("#title").val() + " " +  $("#buildingName").val() + " " + $("#address").val() + " " + $("#zip").val() + " " + $("#conid option:selected").text() + " " + $("#keywords").val()).split(/[,. ;\-:\\\/?+!]/);
    searchWords.forEach(function (word, i, words) {
        stemmer.setCurrent(word);
        stemmer.stem();
        words[i] = stemmer.getCurrent();
    });
    console.log(searchWords);

    // Ajax request to submit the data to the server
    $.ajax({
        type: "POST",
        url: "php/admin/projects/submit_project_edit.php",
        data: {pid: pid,
               cid: $("#cid").val(),
               title: $("#title").val(),
               status: $("#status").val(),
               startDate: $("#startDate").val(),
               endDate: $("#endDate").val(),
               buildingName: $("#buildingName").val(),
               address: $("#address").val(),
               zip: $("#zip").val(),
               type: $("#type").val(),
               summary: $("#summary").val(),
               results: $("#results").val(),
               link: $("#link").val(),
               pic: $("#pic").val(),
               conid: $("#conid").val(),
               fundedBy: $("#fundedBy").val(),
               keywords: $("#keywords").val(),
               stemmedSearchText: searchWords.join(" "),
               visible: $("#visible").val(),
               lat: marker.getPosition().lat(),
               lng: marker.getPosition().lng()},
        data_type: "json",
        success: function (data) {
            printCallback(data);
            loadProjects();
        }
    });

    $("#impactModal").modal('hide');
}

/**
* Called by project_table.php when the user wishes to delete projects from the table. Jquery is used to get the project ids of all checked checkboxes, these ids are then sent to delete_projects.php for deletion
*/
function updateProjects(func) {
    var projects = $('.delete:checkbox:checked').map(function () {
        return this.id;
    }).get();

    $.ajax({
        type: "POST",
        url: "php/admin/projects/update_projects.php",
        data: {func: func,
               data: JSON.stringify(projects)}, 
        success: function (data) {
            console.log(data);
            loadProjects();
        }
    });
}

function warnProjects() {
    $.ajax({
        type: "POST",
        url: "php/admin/warning.php",
        data: {title: "Delete projects",
               body: "Are you sure you wish to delete the selected projects?",
               func: "updateProjects('delete')",
               button: "Delete"}, 
        success: popupCallback
    });
}

function warnCenters() {
    $.ajax({
        type: "POST",
        url: "php/admin/warning.php",
        data: {title: "Delete centers",
               body: "Are you sure you wish to delete the selected centers?",
               func: "deleteCenters()",
               button: "Delete"}, 
        success: popupCallback
    });
}

function warnContacts() {
    $.ajax({
        type: "POST",
        url: "php/admin/warning.php",
        data: {title: "Delete contacts",
               body: "Are you sure you wish to delete the selected contacts?",
               func: "deleteContacts()",
               button: "Delete"}, 
        success: popupCallback
    });
}

function warnUsers() {
    $.ajax({
        type: "POST",
        url: "php/admin/warning.php",
        data: {title: "Delete users",
               body: "Are you sure you wish to delete the selected users?",
               func: "deleteUsers()",
               button: "Delete"}, 
        success: popupCallback
    });
}

function warnPromotion(uid) {
    $.ajax({
        type: "POST",
        url: "php/admin/warning.php",
        data: {title: "Promote to admin",
               body: "<h4>Are you sure you wish to promote this user to admin?</h4>Once a user is promoted they will replace you as admin and you will lose your power",
               func: "promoteUser(" + uid + ")",
               button: "Proceed"}, 
        success: popupCallback
    });
}

function selectAll() {
    $('#content input:checkbox').each(function () {
        $(this).prop('checked', true);
    });
}

function unselectAll() {
    $('#content input:checkbox').each(function () {
        $(this).prop('checked', false);
    });
}

/**
* Called by admin.php to load history_table.php. A datetimepicker is created and its value is initialized to the current date and time
*/
function loadHistory() {
    $(".active").removeClass("active");
    $("#history").addClass("active");
    var ts = ($("#datetimepicker").val() != null) ? $("#datetimepicker").val() : formattedDateTime();
    $.ajax({
        type: "POST",
        url: "php/admin/history/history_table.php",
        data: {timestamp: ts},
        success: contentCallback
    });
}

/**
* Called when a user clicks on a history entry in history_table.php, this will open a popup dialog showing the detailed information of that history information by fetching view_project_history.php
*
* @param hid The id of the entry in the history table the user wishes to view
*/
function viewHistory(hid) {
    //openPopup();
    $.ajax({
        type: "POST",
        data: {hid: hid},
        data_type: "json",
        url: "php/admin/history/view_project_history.php",
        success: historyPopupCallback
    });
}

/**
* Called when the root user selects history items and clicks restore history in history_table.php. This function calls restore_history.php and sends the history ids (hid) of each
* checked item in the list.
*/
function restoreHistory() {
    // Store the ids of all checked history items in a list
    var projects = $('.delete:checkbox:checked').map(function () {
        return this.id;
    }).get();

    $.ajax({
        type: "POST",
        url: "php/admin/history/restore_history.php",
        data: {data: JSON.stringify(projects)},
        success: printCallback
    });
}

/** 
* Called from history_table.php when the root user wants to restore the whole project table to a certain time. The timestamp is taken from the datetimepicker input field
* and sent to restore_all_history.php
*/
function restoreWholeTable() {
    $.ajax({
        type: "POST",
        url: "php/admin/history/restore_all_history.php",
        data: {data: $("#datetimepicker").val()},
        success: printCallback
    });
}

/**
* Called from admin.php to load the content div with the table of centers from center_table.php
*/
function loadCenters() {
    $(".active").removeClass("active");
    $("#centers").addClass("active");
    $.ajax({
        type: "POST",
        url: "php/admin/centers/center_table.php",
        success: contentCallback
    });
}

/**
* Called from center_table.php when a user clicks on a center in the list or add center. The popup dialog is opened and populated with data from edit_center.php
*
* @param cid The id of the center to edit
*/
function editCenter(cid) {
    $.ajax({
        type: "POST",
        data: {cid: cid},
        data_type: "json",
        url: "php/admin/centers/edit_center.php",
        success: centerCallback
    });
}

/**
* Called when a user submits their changes on the edit center popup dialog. Data is sent to submit_center_edit.php
*
* @param cid The id of the center to edit
*/
function submitEditCenter(cid) {
    if (!validateCenterData())
        return;

    $.ajax({
        type: "POST",
        url: "php/admin/centers/submit_center_edit.php",
        data: {cid: cid,
               name: $("#name").val(),
               acronym: $("#acronym").val(),
               color: $("#color").val()},
        data_type: "json",
        success: function (data) {
            loadCenters();
        }
    });

    $("#impactModal").modal('hide');
}

function validateCenterData(){
    var validInput = true;
    var string = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Invalid input:<br><ul> ";

    $("#nameGroup").removeClass("has-error");
    $("#acronymGroup").removeClass("has-error");

    if ($("#name").val().length < 1) 
    {
        $("#nameGroup").addClass("has-error");
        validInput = false;
        string = string.concat("<li><b>Name</b> cannot be empty</li>");
    }
    
    if ($("#acronym").val().length < 2) 
    {
        $("#acronymGroup").addClass("has-error");
        validInput = false;
        if ($("#acronym").val().length < 1)
            string = string.concat("<li><b>Acronym</b> cannot be empty</li>");
        else
            string = string.concat("<li><b>Acronym</b> is too short</li>"); 
    }

    string = string.concat("</ul></div>");

    if (!validInput) {
        $("#invalidInputWarning").html(string);

        return false;
    }
    else
        return true;
}

/**
* Called when a user wants to delete centers from center_table.php. Center ids are taken from checked checkboxes and sent to delete_centers.php
*/
function deleteCenters() {
    var centers = $('.delete:checkbox:checked').map(function () {
        return this.id;
    }).get();

    $.ajax({
        type: "POST",
        url: "php/admin/centers/delete_centers.php",
        data: {data: JSON.stringify(centers)}, 
        success: function (data) {
            loadCenters();
        }
    });
}

/**
* Called from admin.php to load the content div with the table of contacts from contact_table.php
*/
function loadContacts() {
    $(".active").removeClass("active");
    $("#contacts").addClass("active");
    $.ajax({
        type: "POST",
        url: "php/admin/contacts/contact_table.php",
        success: contentCallback
    });
}

/**
* Called from contact_table.php when a user clicks on a contact in the list or add contact. The popup dialog is opened and populated with data from edit_contact.php
*
* @param conid The id of the contact to edit
*/
function editContact(conid) {
    //openPopup();
    $.ajax({
        type: "POST",
        data: {conid: conid},
        data_type: "json",
        url: "php/admin/contacts/edit_contact.php",
        success: popupCallback
    });
}

/**
* Called when a user submits their changes on the edit contact popup dialog. Data is sent to submit_contact_edit.php
*
* @param conid The id of the contact to edit
*/
function submitEditContact(conid) {
    if (!validateContactData())
        return;


    console.log('test');
    $.ajax({
        type: "POST",
        url: "php/admin/contacts/submit_contact_edit.php",
        data: {conid: conid,
               name: $("#name").val(),
               email: $("#email").val(),
               phone: $("#phone").val()},
        data_type: "json",
        success: function (data) {
            loadContacts();
        }
    });

    $("#impactModal").modal('hide');
}

function validateContactData(){
    var validInput = true;
    var string = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Invalid input:<br><ul> ";

    $("#nameGroup").removeClass("has-error");
    $("#emailGroup").removeClass("has-error");
    $("#phoneGroup").removeClass("has-error");

    if ($("#name").val().length < 1) 
    {
        $("#nameGroup").addClass("has-error");
        validInput = false;
        string = string.concat("<li><b>Name</b> cannot be empty</li>");
    }
    
    if ($("#email").val().length < 1) 
    {
        $("#emailGroup").addClass("has-error");
        validInput = false;
        string = string.concat("<li><b>Email</b> cannot be empty</li>");
    }

    var phoneRegex = /^(([0-9]{10})|([0-9]{11})|([0-9]{12}))/;

    if ($("#phone").val().length < 1 || !phoneRegex.test($("#phone").val())) 
    {
        $("#phoneGroup").addClass("has-error");
        validInput = false;
        if($("#phone").val().length < 1)
            string = string.concat("<li><b>Phone</b> cannot be empty</li>");
        else
            string = string.concat("<li><b>Phone</b> must have atleast 10 digits</li>");
    }

    string = string.concat("</ul></div>");

    if (!validInput) {
        $("#invalidInputWarning").html(string);

        return false;
    }
    else
        return true;
}

/**
* Called when a user wants to delete contacts from contact_table.php. Contact ids are taken from checked checkboxes and sent to delete_contacts.php
*/
function deleteContacts() {
    var contacts = $('.delete:checkbox:checked').map(function () {
        return this.id;
    }).get();

    $.ajax({
        type: "POST",
        url: "php/admin/contacts/delete_contacts.php",
        data: {data: JSON.stringify(contacts)}, 
        success: function (data) {
            loadContacts();
        }
    });
}


/**
* Called from admin.php when the root user wants to edit the user table. Loads the content div with data from user_table.php
*/
function loadUsers() {
    $(".active").removeClass("active");
    $("#users").addClass("active");
    $.ajax({
        type: "POST",
        url: "php/admin/users/user_table.php",
        success: contentCallback
    });
}

/** 
* Called from the user table when the root user clicks on an entry in the table or clicks add user, the popup dialog is loaded with content from edit_user.php
*
* @param uid The id of the user to edit, -1 if adding a user
*/
function editUser(uid) {
    //openPopup();
    $.ajax({
        type: "POST",
        data: {uid: uid},
        data_type: "json",
        url: "php/admin/users/edit_user.php",
        success: popupCallback
    });
}

/**
* Submit the changes to the user to the server at submit_user_edit.php
*
* @param uid The id of the user to edit, -1 if adding a user
*/
function submitEditUser(uid) {
    $.ajax({
        type: "POST",
        url: "php/admin/users/submit_user_edit.php",
        data: {uid: uid,
               admin: ($("#admin").is(":checked")) ? 1 : 0,
               cas: ($("#cas").is(":checked")) ? 1 : 0},
        data_type: "json",
        success: function (data) {
            loadUsers();
        }
    });
}

/**
* Called when the root user wants to delete users from user_table.php. User ids are taken from checked checkboxes and sent to delete_users.php
*/
function deleteUsers() {
    var users = $('.delete:checkbox:checked').map(function () {
        return this.id;
    }).get();

    $.ajax({
        type: "POST",
        url: "php/admin/users/delete_users.php",
        data: {data: JSON.stringify(users)}, 
        success: function (data) {
            loadUsers();
        }
    });
}

function updateProfile() {
    $.ajax({
        url: "php/admin/update_profile.php",
        success: popupCallback
    });
}

function submitUpdateProfile() {
    console.log("tacos: " + $("#phone").val());
    // Ajax request to submit the data to the server
    $.ajax({
        type: "POST",
        url: "php/admin/submit_update_profile.php",
        data: {email: $("#email").val(),
               phone: $("#phone").val(),
               newPassword1: $("#newPassword1").val(),
               newPassword2: $("#newPassword2").val()},
        data_type: "json",
        success: function (data) {
            printCallback(data);
            loadProfile();
        }
    });
}

/**
* Load the change password page from change_password.php
*/
function loadProfile() {
    $(".active").removeClass("active");
    $("#profile").addClass("active");
    $.ajax({
        type: "POST",
        url: "php/admin/profile.php",
        success: contentCallback
    });
}

function authenticateUser(uid) {
    $.ajax({
        type: "POST",
        data: {authenticate: true,
               uid: uid}, 
        url: "php/admin/users/submit_user_edit.php",
        success: loadUsers
    });
}

function promoteUser(uid) {
    $.ajax({
        type: "POST",
        data: {promote: true,
               uid: uid}, 
        url: "php/admin/users/submit_user_edit.php",
        success: function (data) {
            window.location.href = "/admin.php";
        }
    });
}


/**
* Print any callback data passed after an ajax call
*
* @param data The data to be printed
*/
function printCallback(data) {
    console.log(data);
}

/**
* Format the current date and time in a MySQL friendly way
*/
function formattedDateTime() {
    var d = new Date();
    return d.getFullYear() + "-" + timeStampValue((d.getMonth() + 1)) + "-" + timeStampValue(d.getDate()) + " " + timeStampValue(d.getHours()) + ":" + timeStampValue(d.getMinutes());
}

/**
* Add a leading "0" to a number if it's less than 10 (i.e. "1" -> "01"), convenient for formatted dates and times
*/
function timeStampValue(num) {
   return (num < 10) ? "0" + num : num;
}
