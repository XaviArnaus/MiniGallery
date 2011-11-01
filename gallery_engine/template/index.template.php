
<html>
	<head>
		<title>{{ site_title }}</title>
		{{ head }}
	</head>
	<body>
		<div id="in_body">
			<div id="wrapper">
				<div id="header">
					<div id="logo">
						<h1><a href="{{ site_url }}">{{ site_title }}</a></h1>
						<span>{{ site_slogan }}</span>
					</div>
					<div id="topright">
					</div>  
				</div>
			</div>
		</div>
		<div id="content">
			<div id="main-container">
				{{ content_body }}
			</div>
		</div>
		<br />
		<div id="social-content">
			<div id="social-header">
				<h2>Social Links</h2>
			</div>
			<div class="column">
				<h3>Participo en</h3>
				<ul>
					<li><a href="http://www.moterus.es/usuarios/dracco">Dracco en Moterus</a></li>
					<li><a href="http://debates.motos.coches.net/member.php?u=18988">Dracco en Motos.net</a></li>
					<li><a href="http://www.vtr250.net/foro/profile/?u=2">Xavi en Honda VTR 250 FanSite</a></li>
					<li><a href="http://www.moterus.es/usuarios/dracco">Xavi en Flickr</a> (no s&oacute;lo motos)</li>
				</ul>
			</div>
			<div class="column">
				<h3>Qui&eacute;n soy?</h3>
				<ul>
					<li><a href="http://www.moterus.es/usuarios/dracco">About Me</a></li>
					<li><a href="http://www.moterus.es/usuarios/dracco">Xavier.Arnaus.net</a></li>
					<li><a href="http://www.moterus.es/usuarios/dracco">Blog de Xavi</a></li>
					<li><a href="http://www.flickr.com/photos/pseudoxavi">Xavi en Flickr</a></li>
					<li><a href="http://www.vimeo.com/dracco/videos">Xavi en Vimeo</a></li>
				</ul>
			</div>
			<div class="last column">
				<h3>Recomendaciones</h3>
				<ul>
					<li><a href="http://twitter.com/#!/nazgulbike">NazgulBike en Twitter</a></li>
					<li><a href="http://www.laposadera.com/">LaPosadera</a></li>
					<li><a href="http://www.moterus.es/usuarios/laposadera">LaPosadera en Moterus</a></li>
				</ul>
			</div>
			<div class="clearfix"></div>
		</div>

		<div id="analytics">
			<script type="text/javascript">

			  var _gaq = _gaq || [];
			  _gaq.push(['_setAccount', 'UA-975061-15']);
			  _gaq.push(['_trackPageview']);

			  (function() {
			    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			  })();

			</script>
		</div>
	</body>
</html>