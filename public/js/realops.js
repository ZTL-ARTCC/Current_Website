$(function () {
    $('#datetimepicker').datetimepicker({
        format: 'L'
    });
});

function realopsValidateAndSubmit() {
    let valid = true;
    let validateFields = ['realops_add_edit_dep_time','realops_add_edit_arr_time'];
    validateFields.forEach(function(field_id) {
        let dep_time = document.getElementById(field_id).value; 
        if(!dep_time.match(/^\d{2}:\d{2}$/)) {
            if(!fixTime(field_id)) {
                document.getElementById(field_id).classList.add("is-invalid");
                valid = false;
            }
            else {
                document.getElementById(field_id).classList.remove("is-invalid");
            }
        }
    });
    if(valid) {
        document.getElementById('realops_add_edit_flight').submit();
    }
}

function realopsFilterValidateAndSubmit() {
    let valid = true;
    let validateFields = ['time_filter'];
    validateFields.forEach(function(field_id) {
        let dep_time = document.getElementById(field_id).value; 
        if(!dep_time.match(/^\d{2}:\d{2}$/) && dep_time != '') {
            if(!fixTime(field_id)) {
                document.getElementById(field_id).classList.add("is-invalid");
                valid = false;
            }
            else {
                document.getElementById(field_id).classList.remove("is-invalid");
            }
        }
    });
    if(valid) {
        document.getElementById('realops_filter').submit();
    }
}

function fixTime(el) {
    let timeStr = document.getElementById(el).value;
    if(timeStr.match(/^\d{1,2}:\d{2}$/)) { // Format H:MM
        document.getElementById(el).value = '0' + timeStr;
        return true;
    }
    else if(timeStr.match(/^\d{1,2}:\d{2}\D*$/)) { // Format HH:MMz or HH:MM:SS
        document.getElementById(el).value = timeStr.substr(0,5);
        return true;
    }
    else if(timeStr.match(/^\d{3}\D*$/)) { // Format HMM or HMM*
        document.getElementById(el).value = '0' + timeStr.substr(0,1) + ':' + timeStr.substr(1,2);
        return true;
    }
    else if(timeStr.match(/^\d{4}\D*$/)) { // Format HHMM or HHMM*
        document.getElementById(el).value = timeStr.substr(0,2) + ':' + timeStr.substr(2,2);
        return true;
    }
    return false;
}