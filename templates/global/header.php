<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="News portal">
    <meta name="author" content="Alexey Shishkin.">
    <title><?=$title?></title>
    <link rel="canonical" href="https://newsPortal.alexeyshishkin.net/">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <script src="/js/jquery-3.6.0.min.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/coreApp.js"></script>
    <?php if($user && $user->hasUserPermission("admin")) { ?>
        <script src="/js/admin.js"></script>
    <?php } ?>
</head>
<body>