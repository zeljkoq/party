function addInStorage(name, value) {
    localStorage.setItem(name, value);
}

function getFromStorage(name) {
    return localStorage.getItem(name)
}

function checkInStorage(name) {
    var value= getFromStorage(name);
    if (value != 'undefined' && value != null) {
        return true;
    }
    $('#unauthorizeModal').css("display", "block");
}

function deleteFromStorage(name) {
    localStorage.removeItem(name);
}

function logoutUser() {
    deleteFromStorage('Authorization');
    window.location = "/";
}

function hideMessages(elementIDs) {
    for(var i=0;i<elementIDs.length;i++){
        $('#'+elementIDs[i]+'Mess').css('display', 'none');
    }
}

function showMessages(data, names) {
    for(var i=0;i<names.length;i++){
        if (typeof data[names[i]] !== 'undefined') {
            $('#'+names[i]+'Mess').text(data[names[i]]);
            $('#'+names[i]+'Mess').css('display', 'block');
        }
    }
}

var crud = {
    modelArr: [],

    checkIfUnauthorized: function (data) {
        if (data.statusText == "Unauthorized") {
            window.location = "/";
        }
    },

    fillTable: function () {
        $('#tableBody').html('');
        for (i = 0; i < crud.modelArr.length; i++) {
            $('#tableBody').append(crud.createTr(crud.modelArr[i], i));
        }
        $('#dataTable').dataTable();
        crud.resetForm();
    },

    resetForm: function () {
        for (i = 0; i < allFormFields.length; i++) {
            $('#' + allFormFields[i]).val('').change();
        }
    },

    createTr: function (model, index) {
        return generateRow(model, index);
    },

    request: function (url, method, data, successCallback, errorCallback) {
        var ajax = {
            url: url,
            type: method,
            headers: {
                "Authorization": getFromStorage('Authorization')
            },
            data: data,
            success: function (data) {
                successCallback(data);
            },
            error: function (data) {
                errorCallback(data);
            }
        };

        if(method == "POST"){
            ajax.contentType = false;
            ajax.processData = false;
        }

        $.ajax(ajax);
    },

    getAll: function () {
        function successCallback(data) {
            crud.modelArr = data.data;
            crud.fillTable();
            onLoad(data);
        }

        function errorCallback(data) {
            crud.checkIfUnauthorized(data);
        }

        crud.request(links.get, 'GET', null, function (data) {
            successCallback(data);
        }, function (data) {
            errorCallback(data);
        })
    },

    create: function () {
        function successCallback(data) {
            showMessages(data, getElementsForMessages());
            crud.modelArr.push(data.data);
            crud.fillTable();
        }

        function errorCallback(data) {
            crud.checkIfUnauthorized(data);
            showMessages(data.responseJSON.errors, getElementsForMessages());
        }

        var data = new FormData(document.forms[0]);
        if (formFiles.length > 0) {
            for (i = 0; i < formFiles.length; i++) {
                data.append(formFiles[i], $('#' + formFiles[i])[0].files[0]);
            }
        }

        crud.request(links.create, 'POST', data, function (data) {
            successCallback(data);
        }, function (data) {
            errorCallback(data);
        })
    },

    fillEditForm: function (index) {
        for (i = 0; i < fields.length; i++) {
            $('#' + fields[i]).val(crud.modelArr[index][fields[i]]);
        }
        $('#editIndex').val(index);
        $('#submitAdd').css('display', 'none');
        $('#submitEdit').css('display', 'block');
        $('#cancelEdit').css('display', 'block');
        if (hideElements.length > 0) {
            for (i = 0; i < hideElements.length; i++) {
                $('#' + hideElements[i]).parent().css('display', 'none');
            }
        }
    },

    emptyEditForm: function () {
        for (i = 0; i < fields.length; i++) {
            $('#' + fields[i]).val('');
        }
        $('#submitAdd').css('display', 'block');
        $('#submitEdit').css('display', 'none');
        $('#cancelEdit').css('display', 'none');
        if (hideElements.length > 0) {
            for (i = 0; i < hideElements.length; i++) {
                $('#' + hideElements[i]).parent().css('display', 'block');
            }
        }
    },

    edit: function () {
        function successCallback(data) {
            showMessages(data, getElementsForMessages());
            var index = $('#editIndex').val();
            crud.modelArr[index] = data.data;
            crud.fillTable();
        }

        function errorCallback(data) {
            crud.checkIfUnauthorized(data);
            showMessages(data.responseJSON.errors, getElementsForMessages());
        }

        var data = {};
        for (i = 0; i < fields.length; i++) {
            data[fields[i]] = $('#' + fields[i]).val()
        }

        crud.request(links.update, 'PUT', data, function (data) {
            successCallback(data);
        }, function (data) {
            errorCallback(data);
        })
    },

    delete: function (url) {
        function successCallback(data) {
            var index = $('#deleteIndex').val();
            crud.modelArr.splice(index, 1);
            crud.fillTable();
            showMessages(data, getElementsForMessages());
        }

        function errorCallback(data) {
            crud.checkIfUnauthorized(data);
        }

        crud.request(url, 'DELETE', null, function (data) {
            successCallback(data);
        }, function (data) {
            errorCallback(data);
        })
    }
};

$('#submitAdd').on('click', function () {
    hideMessages(getElementsForMessages());
    crud.create();
});

$('#submitEdit').on('click', function () {
    hideMessages(getElementsForMessages());
    crud.edit();
});

function deleteModal(message, button, url, index) {
    $('#deleteIndex').val(index);
    $('#conf-message').text(message);
    $('#button').text(button);
    $('#confirmationModal').css('display', 'block');
    $('#button').attr('onclick', 'deleteItem(\'' + url + '\')');
}

function deleteItem(url) {
    $('#confirmationModal').css('display', 'none');
    hideMessages(getElementsForMessages());
    crud.delete(url);
}
