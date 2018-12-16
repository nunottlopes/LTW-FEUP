<?php
require_once __DIR__ . '/../../../api/api.php';
$auth = Auth::authenticate();
?>
<!DOCTYPE html>
<html>
    <head>
        <script type="text/javascript" src="/feup_books/javascript/api-ajax.js"></script>
        <script type="text/javascript" src="/api/test.js"></script>
        <script type="text/javascript">
            window.FEUPNEWS_CSRF_TOKEN = "<?= $_SESSION['CSRFTOKEN'] ?>";
            var auth = <?= json_encode($auth) ?>;
        </script>
    </head>
    <body>
    </body>
</html>
