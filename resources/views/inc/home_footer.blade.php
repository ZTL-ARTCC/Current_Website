<footer id="myFooter" class="bg-secondary">
    <div class="footer-social bg-secondary">
        <p>Visit our partners:</p>
        <a href="http://www.vatstar.com/" target="_blank"><img width="200" src="/photos/partner-vatstar.png"></a> &nbsp;&nbsp;&nbsp; <a style="position: relative; top: -10px" href="https://www.teamspeak.com/?tour=yes" target="_blank"><img width="200" src="/photos/partner-ts.png"></a> &nbsp;&nbsp;&nbsp; <a style="position: relative; top: -10px" href="https://www.thepilotclub.org/" target="_blank"><img height="100" src="/photos/partner-tpc.png"></a>
        <br><br>
        <p><i>For entertainment purposes only. Do not use for real world purposes. Part of the VATSIM Network.</i></p>
		<style>
		.pride {
			width: 100px;
			height: 30px;
			
		}
		.lgbt {
			background: linear-gradient(180deg, #FE0000 16.66%,
			#FD8C00 16.66%, 33.32%,
			#FFE500 33.32%, 49.98%,
			#119F0B 49.98%, 66.64%,
			#0644B3 66.64%, 83.3%,
			#C22EDC 83.3%);
		}
		.transgender {
			background: linear-gradient(180deg, #5BCEFA 20%, #F5A9B8 20%, 40%, #FFFFFF 40%, 60%, #F5A9B8 60%, 80%, #5BCEFA 80%);
		}
		.bisexual {
			background: linear-gradient(180deg, #D60270 40%, #9B4F96 40%, 60%, #0038A8 60%);
		}
		.pansexual {
			background: linear-gradient(180deg, #FF218C 33.33%, #FFD800 33.33%, 66.66%, #21B1FF 66.66%);
		}
		span.pride + span.pride {
			margin-left: 10px;
		}
		</style>
		<div><div><span class="pride lgbt"></span><span class="pride transgender"></span><span class="pride bisexual"></span><span class="pride pansexual"></span></div><p>The Atlanta ARTCC stands with the LGBTQIA+ community on VATSIM</p></div>
    </div>
    <div class="container bg-secondary">
        <p><a href="/privacy" target="_blank">Privacy Policy, Terms and Conditions</a></p>
        <p class="footer-copyright text-dark">
            © {{ Carbon\Carbon::now()->year }} vZTL ARTCC
        </p>
    </div>
</footer>