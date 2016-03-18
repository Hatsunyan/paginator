<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Paginator</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <link href="paginator.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=PT+Sans&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
</head>
<body>
    <?
        require_once '../paginator.php';
    ?>
    <h1>few pages</h1>
    <?
        $paginator = new \Hatsunyan\Paginator();
        $paginator->setPages(30,10,2);
        $paginator->render();
    ?>
    <h1>On fist page</h1>
    <?
        $paginator = new \Hatsunyan\Paginator();
        $paginator->setPages(300,10,1);
        $paginator->render();
    ?>
    <h1>On last page</h1>
    <?
        $paginator = new \Hatsunyan\Paginator();
        $paginator->setPages(300,10,30);
        $paginator->render();
    ?>
    <h1>See first page</h1>
    <?
        $paginator = new \Hatsunyan\Paginator();
        $paginator->setPages(300,10,4);
        $paginator->render();
    ?>
    <h1>See last page</h1>
    <?
        $paginator = new \Hatsunyan\Paginator();
        $paginator->setPages(300,10,27);
        $paginator->render();
    ?>
    <h1>Middle</h1>
    <?
        $paginator = new \Hatsunyan\Paginator();
        $paginator->setUrlPattern('/page={p}&type=5');
        $paginator->setCustomLang('en','...');
        $paginator->setPages(300,10,15);
        $paginator->render();
    ?>
    <h1>Disable steps</h1>
    <?
        $paginator = new \Hatsunyan\Paginator();
        $paginator->showNextPrev(false);
        $paginator->setLang('ru');
        $paginator->setPages(300,10,15);
        $paginator->render();
    ?>
    <h1>Disable first and last pages</h1>
    <?
        $paginator = new \Hatsunyan\Paginator();
        $paginator->showLastFirst(false);
        $paginator->setPages(300,10,15);
        $paginator->render();
    ?>
</body>
</html>