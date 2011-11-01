
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
		{{ footer }}

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