var j = jQuery.noConflict();

/******************************************************************************************************************/
// Main event

j(document).ready(function () {
    displaySetEvent(-2, -2, '', ''); // -2 because first call => check session variable
});

/******************************************************************************************************************/

// Main function => Display all then set event
function displaySetEvent(curPage, rowPerPage, searchWord, searchWordDisplay) {

    // Contact, Pagination, Search
    j.ajax({
        type: 'POST',
        url: 'view/contact/display.php',
        dataType: 'json',
        data: {
            curPage: curPage,
            rowPerPage: rowPerPage,
            searchWord: searchWord,
            searchWordDisplay: searchWordDisplay
        }
    }).done(function (data) {

        // Print Html
        j('#contactDiv').html(data['htmlContactPattern']);
        j('#contactContentDiv').html(data['htmlContact']);
        j('#paginationDiv').html(data['htmlPagination']);
        j('#searchDiv').html(data['htmlSearch']);

        // Avoid default event
        j('a').on('click', function (e) {
            e.preventDefault();
        });

        // Set customize event
        setContactEvent();
        setSearchEvent();
        setPaginantionEvent();
        setModalEvent();
    })
}

/******************************************************************************************************************/

// Set event

function setContactEvent() {
    j('.contactRow').hover(function () {
        j(this).addClass('contactRowHover')
    }, function () {
        j(this).removeClass('contactRowHover')
    });
}

function setPaginantionEvent() {
    j('#paginationFirstBouton').on('click', paginationAction);
    j('#paginationPrecBouton').on('click', paginationAction);
    j('#paginationNextBouton').on('click', paginationAction);
    j('#paginationLastBouton').on('click', paginationAction);
}

function setSearchEvent() {
    j('#searchReset').on('click', function () {
        j("#searchWordDisplay").val("");
    });
    j('#searchValid').on('click', function () {
        displaySetEvent(1, j('#rowPerPage').val(), j('#searchWordDisplay').val(), j('#searchWordDisplay').val());
    });
    j('#searchWordDisplay').on("keypress", function (e) {
        var touche = e.which ? e.which : e.keyCode;
        if (touche === 13) { // 13 => Enter
            displaySetEvent(1, j('#rowPerPage').val(), j('#searchWordDisplay').val(), j('#searchWordDisplay').val());
        }
    });
    j('#rowPerPage').on('change', function () {
        displaySetEvent(1, j('#rowPerPage').val(), j('#searchWordDisplay').val(), j('#searchWordDisplay').val());
    });
}

function setModalEvent() {
    j('.actionView').on('click', {name: 'view.php', getData: 'uid'}, showModalByEvent);
    j('.actionEdit').on('click', {name: 'edit.php', getData: 'uid'}, showModalByEvent);
    j('.actionRemove').on('click', {name: 'remove.php', getData: 'uid'}, showModalByEvent);
    j('#actionAdd').on('click', {name: 'edit.php'}, showModalByEvent);
}

/******************************************************************************************************************/

// View function

function paginationAction() {
    displaySetEvent(j(this).data('numpage'), j('#rowPerPage').val(), j('#searchWord').val(), j('#searchWordDisplay').val());
}

// Modal call by event
function showModalByEvent(e) {
    var data = e.data.data;
    if (e.data.getData === 'uid') {
        data = {uid: j(this).data('uid')};
    }
    j.ajax({
        type: 'POST',
        url: 'view/modal/' + e.data.name,
        dataType: 'json',
        data: data
    }).done(function (data) {
        j('#modalIndex').html(data['html']);
        j('#modalIndex').modal('show');

        if (data['action'] === 'addModal' || data['action'] === 'editModal') {
            j('#addContactButton').on('click', sendForm);
            j('#editContactButton').on('click', sendForm);
            j('#photoFile').on('change', function () {
                j.ajax({
                    url: 'controller/cont_file.php',
                    data: new FormData(j('form')[0]),
                    mimetype: 'multipart/form-data',
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    type: 'POST'
                }).done(function (data) {
                    j('#photoProfilEdit').html(data['html']);
                });
            });
        }
        else if (data['action'] === 'delModal') {
            j('#removeContactButton').on('click', {
                name: 'cont_bdd.php',
                data: {action: 'delBdd', uid: data['uid']}
            }, sendScript);
        }
    }).fail(function (data, status) {
        alert(status);
    });
}

// Modal call without event
function showModal(name, data) {
    j.ajax({
        type: 'POST',
        url: 'view/modal/' + name,
        dataType: 'json',
        data: data
    }).done(function (data) {
        j('#modalIndex').html(data['html']);
        j('#modalIndex').modal('show');
    }).fail(function (data, status) {
        alert(status);
    });
}

/******************************************************************************************************************/

// Controller function

function sendScript(e) {
    j.ajax({
        type: 'POST',
        url: 'controller/' + e.data.name,
        dataType: 'json',
        data: e.data.data
    }).done(function () {
        displaySetEvent(j('#curPage').val(), j('#rowPerPage').val(), j('#searchWord').val(), j('#searchWordDisplay').val());
    }).fail(function (data, status) {
        alert(status);
    });
}

function sendForm() {
    j.ajax({
        url: 'controller/cont_bdd.php',
        data: new FormData(j('form')[0]),
        mimetype: 'multipart/form-data',
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        type: 'POST'
    }).done(function (data) {
        j('div[id^="unfilledGroup-"]').removeClass('has-error');
        if (data['success'] === true) {
            showModal('view.php', data);
            displaySetEvent(j('#curPage').val(), j('#rowPerPage').val(), j('#searchWord').val(), j('#searchWordDisplay').val());
        } else {
            var i;
            for (i = 0; i < data['inputFail'].length; i++) {
                j('#unfilledGroup-' + data['inputFail'][i]).addClass('has-error');
            }
        }
    }).fail(function (data, status) {
        alert(status);
    });
}
