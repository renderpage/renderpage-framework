<?php

use vendor\pershin\renderpage\RenderPage;
?>
<!DOCTYPE html>
<html lang="en-us">
  <head>
    <meta charset="UTF-8">
    <title><?= $this->title ?></title>
  </head>
  <body>
    <?= $this->workarea ?>
    <hr>
    <p>RenderPage <?= RenderPage::RENDERPAGE_VERSION ?></p>
  </body>
</html>
