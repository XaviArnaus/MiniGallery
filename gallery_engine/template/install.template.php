<html>
	<head>
		<title>MiniGallery Installation</title>
		<style type="text/css">
			* { margin: 0; padding: 0; font-family: Verdana; }
			h1, p { margin: 0; padding: 10px 0 10px 0; }
			pre { height: auto; overflow-x:scroll; }
			p { font-size: 10pt; }
			i { color: blue; }
			table.check-results { background-color: grey; margin:10px; width: 800px }
			table.check-results td { padding:5px; }
			table.check-results tr.resolve { background-color: lightgrey; font-size: 9pt; }
			table.check-results td.result { width: 50px; height: 50px; }
			.content {text-align: center; width:800px;}
			.content-desc { margin: 0 auto; }
		</style>
	</head>
	<body>
		<div class="content">
			<h1>MiniGallery Installation</h1>
			<p>
				These are the checks we performed over the application and their results.
			</p>
			<p>
				Please follow the instructions to resolve the problems.
			</p>
		</div>
		<br />
		<div class="content-desc">
			<table class="check-results">
				<tr>
					<td class="desc">
						Presence of <i><b>.htaccess</b></i> file
					</td>
					<td class="result" style="background-color:{{ result_htaccess }};"></td>
				</tr>
				<tr class="resolve">
					<td colspan="2">
						<p>
							Could not find file <i>.htaccess</i> in your <i>{{ htaccess_subdir }}</i> folder.<br />
							This is needed for routing correctly all the requests to the application.
						</p>
						<p>
							Please create a file called literally <i>.htaccess</i> (without extension and starting with a dot)<br />
							and place inside the following content:
						</p>
						<pre style='padding:10px; background-color:lightgrey; border:1px solid black'>
{{ htaccess_content }}
						</pre>
					</td>
				</tr>
				<tr>
					<td class="desc">
						Presence of the config file <i><b>{{ config_file }}.config.php</b></i>
					</td>
					<td class="result" style="background-color:{{ result_config }};"></td>
				</tr>
				<tr class="resolve">
					<td colspan="2">
						<p>
							Even the gallery can work with the default parameters, it is highly recomendable<br />
							to create a config file inside <i>{{ config_dir }}</i>.
						</p>
						<p>
							Please create a called literally <i>{{ config_file }}.config.php</i> (yes, with <i>php</i> extension)<br />
							and place inside the following content:
						</p>
						<pre style='padding:10px; background-color:lightgrey; border:1px solid black'>
&lt;?php
{{ config_content }}
?&gt;
						</pre>
						<p>
							Then you can edit some params there to adjust the gallery to your needs.<br /><br />
							Remember that some of these params are critical and editing them could make the Gallery work wrong.
						</p>
					</td>
				</tr>
				<tr>
					<td class="desc">
						Presence of the Image Thumbnail <i><b>{{ thumbs_dir }}</b></i> directory
					</td>
					<td class="result" style="background-color:{{ result_thumbs }};"></td>
				</tr>
				<tr class="resolve">
					<td colspan="2">
						<p>
							The Thumbnail directory <i><b>{{ thumbs_dir }}</b></i> is needed for the thumbnail generation process.<br />
							This directory is automatically created if you have writing rights on the gallery engine directory:<br /><br />
							<i>{{ gallery_dir }}</i>: The directory has {{ gallery_has_rights }} writable rights.
						</p>
					</td>
				</tr>
				<tr>
					<td class="desc">
						Presence of the Image Cache <i><b>{{ cache_dir }}</b></i> directory
					</td>
					<td class="result" style="background-color:{{ result_cached }};"></td>
				</tr>
				<tr class="resolve">
					<td colspan="2">
						<p>
							The Cache directory <i><b>{{ cache_dir }}</b></i> is needed for the cache image generation process,<br />
							which will boost the Gallery when loading the picture page<br />
							This directory is automatically created if you have writing rights on the gallery engine directory:<br /><br />
							<i>{{ gallery_dir }}</i>: The directory has {{ gallery_has_rights }} writable rights.
						</p>
					</td>
				</tr>
				<tr>
					<td class="desc" colspan="2">
						Thumbnail and Cache image generation.
					</td>
				</tr>
				<tr class="resolve">
					<td colspan="2">
						<p>
							The first time an user accesses to every folder on your gallery, the Gallery Engine will automatically
							generate a thumbnail and a cache version in the <i>{{ thumbs_dir }}</i> and the <i>{{ cache_dir }}</i>
							respectively.
						</p>
						<p>
							As said before, the gallery should have write rights on the gallery directory <i>{{ gallery_dir }}</i>,
							which currently has {{ gallery_has_rights }} writable rights.
						</p>
						<p>
							Another option is to create these directories manually under <i>{{ gallery_dir }}</i> and give them
							write rights to the internet user. It will work as well.
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>