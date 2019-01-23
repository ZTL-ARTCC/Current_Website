<footer id="myFooter">
    <div class="footer-social">
        <a href="http://www.vatstar.com/" target="_blank"><img width="200" src="/photos/vatstar.png"></a> &nbsp;&nbsp;&nbsp; <a style="position: relative; top: -10px" href="https://www.teamspeak.com/?tour=yes" target="_blank"><img width="200" src="/photos/ts_inline.png"></a>
        <br><br>
        <p><i>For entertainment purposes only. Do not use for real world purposes. Part of the VATSIM Network.</i></p>
        @if(Carbon\Carbon::now()->month == 12)
            <button class="btn btn-secondary btn-sm" onclick="snowStorm.stop();return false">Stop Snow</button>
        @endif
    </div>
    <div class="container">
        <p class="footer-copyright">
            © 2018-2019 vZTL ARTCC
        </p>
    </div>
</footer>
