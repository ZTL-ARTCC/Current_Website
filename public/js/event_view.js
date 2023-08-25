window.onload = () => {
    let timezone = document.getElementById("timezone");
    let timedata = document.getElementById("timedata").value;
    let timedata_split = timedata.split(";");
    let start_zulu = timedata_split[0];
    let end_zulu = timedata_split[1];
    let start_local = timedata_split[2];
    let end_local = timedata_split[3];

    let start_time = document.getElementById("start_time1");
    let end_time = document.getElementById("end_time1");

    console.log(`Event info: Zulu ${start_zulu}-${end_zulu} Local ${start_local}-${end_local}`);

    timezone.onchange = () => {
        let newselected = timezone.value;

        console.log(newselected);

        if (newselected == "1") {
            // switch to local time
            start_time.placeholder = start_local;
            end_time.placeholder = end_local;
        } else {
            start_time.placeholder = start_zulu;
            end_time.placeholder = end_zulu;
        }

        start_time.value = "";
        end_time.value = "";
    }
}