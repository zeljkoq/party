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

function hideMessages(){
    $('#success').css('display', 'none');
    $('#error').css('display', 'none');
}

function showMessages(data){
    if (typeof data.success !== 'undefined') {
        $('#success').text(data.success);
        $('#success').css('display', 'block');
    }
    if (typeof data.error !== 'undefined') {
        $('#error').text(data.error);
        $('#error').css('display', 'block');
    }
}