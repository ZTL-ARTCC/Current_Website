<footer id="myFooter" class="bg-secondary">
    <div class="container footer-social bg-secondary">
        <p>Visit our partners:</p>
        <a href="http://www.vatstar.com/" target="_blank"><img width="200" src="/photos/partner-vatstar.png"></a> &nbsp;&nbsp;&nbsp; <a style="position: relative; top: -10px" href="https://www.teamspeak.com/?tour=yes" target="_blank"><img width="200" src="/photos/partner-ts.png"></a> &nbsp;&nbsp;&nbsp; <a style="position: relative; top: -10px" href="https://www.thepilotclub.org/" target="_blank"><img height="100" src="/photos/partner-tpc.png"></a>
        <br><br>
        <p class="px-5 text-white border border-dark rounded">Thank you for visiting our website! The Virtual Atlanta ARTCC (vZTL) is a community of flight simulation enthusiasts providing air traffic control services on the VATSIM network. We take pride in fostering an immersive and enjoyable environment for controllers and pilots. While we occupy the same geographic area in our network coverage, we are not affiliated with the real-world ZTL ARTCC or FAA.</p>
        @if(Carbon\Carbon::now()->month == 12)
            <button class="btn btn-dark btn-sm" onclick="snowStorm.stop();return false">Stop Snow</button>
        @endif

    </div>
    <div class="container bg-secondary">
		<p>vZTL is an affiliate of the <a href="http://www.vatsim.net" target="_blank"><img src="/photos/vatsim_logo.png" style="height:20px; width:59px;" alt="VATSIM"></a> network and the <a href="http://www.vatusa.net" target="_blank"><img src="/photos/vatusa_logo.png" style="margin-top: -5px; height:20px; width:86px;" alt="VATUSA"></a> division</p>
        <p><a href="/privacy" target="_blank">Privacy Policy, Terms and Conditions</a></p>
        <p class="footer-copyright text-dark">
            © {{ Carbon\Carbon::now()->year }} vZTL ARTCC
        </p>
    </div>
</footer>