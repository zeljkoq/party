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
        if (typeof data.names[i] !== 'undefined') {
            $('#'+names[i]+'Mess').text(data.success);
            $('#'+names[i]+'Mess').css('display', 'block');
        }
    }
}