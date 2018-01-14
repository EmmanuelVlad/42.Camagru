<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    
	<title><?= (isset($data->title)) ? $data->title : SITE_TITLE ?></title>
	<meta name="description" content="<?= (isset($data->description)) ? htmlspecialchars($data->description) : htmlspecialchars(SITE_DESCRIPTION) ?>">

	<link rel="icon" type="image/png" href="<?=URL?>public/images/favicon.png" sizes="16x16">
	<link rel="stylesheet" href="<?=URL?>public/css/bulma.css">
	<link rel="stylesheet" href="<?=URL?>public/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?=URL?>public/css/main.css">
	<link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
	<link href="https://fonts.googleapis.com/css?family=Oswald:300,400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
	<script>
		const url = "<?=URL?>";
		const connected = <?=(Session::get('user')) ? "true" : "false"?>;
		const user = <?=(Session::get('user')) ? Session::get('user') : 0?>;
	</script>
	<script src="<?=URL?>public/js/main.js"></script>

	<?= (isset($data->custom_meta)) ? $data->custom_meta : '' ?>

</head>

<body hreflang="en">