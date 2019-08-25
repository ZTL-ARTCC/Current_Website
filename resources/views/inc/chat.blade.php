<center><i><h4>Controller Chat</h4></i></center>
<div class="card card-body" style="background-color: grey;">
    <form id="new-message">
        <textarea class="form-control" id="message" placeholder="Enter your message"></textarea>
        <br>
        <button class="btn btn-primary btn-sm btn-block" >Submit</button>
    </form>
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

    #delete {
        color: red;
        cursor: pointer;
    }
</style>

<script>
    $.ajax({
        type: 'GET',
        url: '/dashboard/controllers/chat/messages',
        success: function (data) {
            var obj = JSON.parse(data);
            var your_html = "";
            $.each(obj['messages'], function (key, val) {
                if(val.cid == {{ Auth::id() }}) {
                    your_html += "<div class=\"container-chat darker\">" +
                        "<small><p>" + val.message + "</p></small>" +
                        "<span class=\"time-right\"><small>" + val.c_name + " - " + val.cid + "</small></span>" +
                        "<br>" +
                        "<span class=\"time-right\"><i class=\"fa fa-times " + val.id +  "\" id=\"delete\"></i>&nbsp;<small>" + val.format_time + "</small></span>" +
                        "</div>"
                } else {
                    your_html += "@if(Auth::user()->can('snrStaff'))" +
                        "<div class=\"container-chat\">" +
                        "<small><p>" + val.message + "</p></small>" +
                        "<span class=\"time-right\"><small>" + val.c_name + " - " + val.cid + "</small></span>" +
                        "<br>" +
                        "<span class=\"time-right\"><i class=\"fa fa-times " + val.id +  "\" id=\"delete\"></i>&nbsp;<small>" + val.format_time + "</small></span>" +
                        "</div>" +
                        "@else" +
                        "<div class=\"container-chat \">" +
                        "<small><p>" + val.message + "</p></small>" +
                        "<span class=\"time-right\"><small>" + val.c_name + " - " + val.cid + "</small></span>" +
                        "<br>" +
                        "<span class=\"time-right\"><small>" + val.format_time + "</small></span>" +
                        "</div>" +
                        "@endif"
                }
            });
            $("#chat").html(your_html)
        },
        error: function() {
            console.log(data);
        }
    });

    $('#new-message').on('submit', function(e) {
        e.preventDefault();
        var message = $('#message').val();
        $('#message').val("");
        $.ajax({
            type: "POST",
            url: '/dashboard/controllers/chat/messages/new',
            data: {_token: "{{ csrf_token() }}", cid:"{{ Auth::id() }}", message:message},
            success: function() {
                $.ajax({
                    type: 'GET',
                    url: '/dashboard/controllers/chat/messages',
                    success: function (data) {
                        var obj = JSON.parse(data);
                        var your_html = "";
                        var cid = {{ Auth::id() }};
                        $.each(obj['messages'], function (key, val) {
                            if(val.cid == {{ Auth::id() }}) {
                                your_html += "<div class=\"container-chat darker\">" +
                                    "<small><p>" + val.message + "</p></small>" +
                                    "<span class=\"time-right\"><small>" + val.c_name + " - " + val.cid + "</small></span>" +
                                    "<br>" +
                                    "<span class=\"time-right\"><i class=\"fa fa-times " + val.id +  "\" id=\"delete\"></i>&nbsp;<small>" + val.format_time + "</small></span>" +
                                    "</div>"
                            } else {
                                your_html += "@if(Auth::user()->can('snrStaff'))" +
                                    "<div class=\"container-chat\">" +
                                    "<small><p>" + val.message + "</p></small>" +
                                    "<span class=\"time-right\"><small>" + val.c_name + " - " + val.cid + "</small></span>" +
                                    "<br>" +
                                    "<span class=\"time-right\"><i class=\"fa fa-times " + val.id +  "\" id=\"delete\"></i>&nbsp;<small>" + val.format_time + "</small></span>" +
                                    "</div>" +
                                    "@else" +
                                    "<div class=\"container-chat \">" +
                                    "<small><p>" + val.message + "</p></small>" +
                                    "<span class=\"time-right\"><small>" + val.c_name + " - " + val.cid + "</small></span>" +
                                    "<br>" +
                                    "<span class=\"time-right\"><small>" + val.format_time + "</small></span>" +
                                    "</div>" +
                                    "@endif"
                            }
                        });
                        $("#chat").html(your_html)
                    },
                    error: function() {
                        console.log(data);
                    }
                });
            },
            error: function() {
                console.log('Error');
            }
        });
    });

    $(document).on('click', '#delete', function(e) {
        e.preventDefault();
        var id = $(this).attr("class").substring(12);
        $.ajax({
            type: "POST",
            url: '/dashboard/controllers/chat/messages/delete/' + id,
            data: {_token: "{{ csrf_token() }}", cid:"{{ Auth::id() }}"},
            success: function() {
                $.ajax({
                    type: 'GET',
                    url: '/dashboard/controllers/chat/messages',
                    success: function (data) {
                        var obj = JSON.parse(data);
                        var your_html = "";
                        $.each(obj['messages'], function (key, val) {
                            if(val.cid == {{ Auth::id() }}) {
                                your_html += "<div class=\"container-chat darker\">" +
                                    "<small><p>" + val.message + "</p></small>" +
                                    "<span class=\"time-right\"><small>" + val.c_name + " - " + val.cid + "</small></span>" +
                                    "<br>" +
                                    "<span class=\"time-right\"><i class=\"fa fa-times " + val.id +  "\" id=\"delete\"></i>&nbsp;<small>" + val.format_time + "</small></span>" +
                                    "</div>"
                            } else {
                                your_html += "@if(Auth::user()->can('snrStaff'))" +
                                    "<div class=\"container-chat\">" +
                                    "<small><p>" + val.message + "</p></small>" +
                                    "<span class=\"time-right\"><small>" + val.c_name + " - " + val.cid + "</small></span>" +
                                    "<br>" +
                                    "<span class=\"time-right\"><i class=\"fa fa-times " + val.id +  "\" id=\"delete\"></i>&nbsp;<small>" + val.format_time + "</small></span>" +
                                    "</div>" +
                                    "@else" +
                                    "<div class=\"container-chat \">" +
                                    "<small><p>" + val.message + "</p></small>" +
                                    "<span class=\"time-right\"><small>" + val.c_name + " - " + val.cid + "</small></span>" +
                                    "<br>" +
                                    "<span class=\"time-right\"><small>" + val.format_time + "</small></span>" +
                                    "</div>" +
                                    "@endif"
                            }
                        });
                        $("#chat").html(your_html)
                    },
                    error: function() {
                        console.log(data);
                    }
                });
            },
            error: function() {
                console.log('Error');
            }
        });
    });
</script>