<footer id="myFooter">
    <div class="footer-social">
        <p>Visit our partners:</p>
        <a href="http://www.vatstar.com/" target="_blank"><img width="200" src="/photos/partner-vatstar.png"></a> &nbsp;&nbsp;&nbsp; <a style="position: relative; top: -10px" href="https://www.teamspeak.com/?tour=yes" target="_blank"><img width="200" src="/photos/partner-ts.png"></a> &nbsp;&nbsp;&nbsp; <a style="position: relative; top: -10px" href="https://www.thepilotclub.org/" target="_blank"><img height="100" src="/photos/partner-tpc.png"></a>
        <br><br>
        <p><i>For entertainment purposes only. Do not use for real world purposes. Part of the VATSIM Network.</i></p>
        @if(Carbon\Carbon::now()->month == 12)
            <button class="btn btn-secondary btn-sm" onclick="snowStorm.stop();return false">Stop Snow</button>
        @endif
    </div>
    <div class="container">
        <p><a href="https://www.ztlartcc.org/privacy" target="_blank">Privacy Policy, Terms and Conditions</a></p>
        <p class="footer-copyright">
            © 2021 vZTL ARTCC
        </p>
    </div>
</footer>