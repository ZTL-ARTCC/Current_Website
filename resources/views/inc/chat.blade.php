<center><i><h4>Controller Chat</h4></i></center>
<div class="card card-body" style="background-color: grey;">
    <form>
        <textarea class="form-control" placeholder="Enter your message"></textarea>
    </form>
    <br>
    <button class="btn btn-primary btn-sm btn-block">Submit</button>
</div>
<br>
<div class="card card-body" id="chat" style="background-color: grey; height:500px; overflow: auto;">
</div>

<style>
    /* Chat containers */
    .container-chat {
        border: 2px solid #dedede;
        background-color: #f1f1f1;
        border-radius: 5px;
        padding: 10px;
        margin: 10px 0;
    }

    /* Darker chat container */
    .darker {
        border-color: #ccc;
        background-color: #ddd;
    }

    /* Clear floats */
    .container-chat::after {
        content: "";
        clear: both;
        display: table;
    }

    /* Style time text */
    .time-right {
        float: right;
        color: #aaa;
    }

    /* Style time text */
    .time-left {
        float: left;
        color: #999;
    }
</style>

<script>
    $.ajax({
        type: 'GET', //THIS NEEDS TO BE GET
        url: '/dashboard/controllers/chat/messages',
        success: function (data) {
            var obj = JSON.parse(data);
            var your_html = "";
            $.each(obj['messages'], function (key, val) {
                your_html += "<div class=\"container-chat\"><small><p>" + val.message + "</p></small><span class=\"time-right\"><small>" + val.c_name + " - " + val.cid + "</small></span><br><span class=\"time-right\"><small>" + val.format_time + "</small></span> </div>"
            });
            $("#chat").append(your_html); //// For Append
            $("#chat").html(your_html)   //// For replace with previous one
        },
        error: function() {
            console.log(data);
        }
    });
</script>