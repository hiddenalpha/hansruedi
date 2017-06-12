<!DOCTYPE html>
<html>

<head>
    <title>Bilder</title>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fonts from Google -->
    <link href="https://fonts.googleapis.com/css?family=Slabo+27px|Source+Sans+Pro" rel="stylesheet">

    <!-- Stylesheets -->
    <link href="client/assets/app.css" rel="stylesheet">

    <!-- jquery-3 - library -->
    <script type="text/javascript" src="client/assets/libs/jquery/jquery.min.js"></script>

    <!-- EXIF img information - library -->
    <script type="text/javascript" src="client/assets/libs/exif/exif.js"></script>

    <!-- module (must be first include) -->
    <script type="text/javascript" src="client/app.module.js"></script>

    <!-- app components -->
    <script type="text/javascript" src="client/large-image/large-image.js"></script>
    <script type="text/javascript" src="client/services/imageService.js"></script>
    <script type="text/javascript" src="client/services/rest-service.js"></script>
    <script type="text/javascript" src="client/services/video-service.js"></script>
    <script type="text/javascript" src="client/test/test.js"></script>
    <script type="text/javascript" src="client/video-list/video-list-type.js"></script>
    <script type="text/javascript" src="client/video-list/video-list.js"></script>

    <!-- entry point (must be the last include) -->
    <script type="text/javascript" src="client/app.main.js"></script>

</head>

<body>
    <div id="allContent">

        <noscript>
				<p>Sorry. Diese App läuft ohne Javascript nicht :(</p>
			</noscript>

        <section>
            <h1>Gruppe Hansruedi 152</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
        </section>

        <section>
            <div id="imgContainer"></div>
        </section>

        <div id="largeImagePlaceholder" style="display:none;">DevHint: This element gets replaced later by js.</div>

    </div>

	<footer>
		<p>Lorem ipsum</p>
	</footer>

</body>

</html>
