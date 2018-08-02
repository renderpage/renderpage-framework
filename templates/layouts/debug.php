<!DOCTYPE html>
<html lang="en-us">
  <head>
    <meta charset="UTF-8">
    <title><?= $this->title ?></title>
    <style>
      body {
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 12px;
        margin: 16px;
      }
      h1 {
        color: #ed1c24;
        font-size: 30px;
        font-weight: normal;
      }
      .source,
      .stack-trace {
        background: #3f3f3f;
        color: #fff;
        display: block;
        font-family: monospace;
        font-size: 18px;
      }
      .source .number {
        background: #0c0c0c;
        color: #8a8a8a;
        padding: 0 16px;
      }
      .source .highlight {
        background: #101010;
        display: block;
      }
      .stack-trace .type,
      .stack-trace .args {
        color: #9f9d6d;
      }
      .stack-trace .function {
        color: #fff;
      }
    </style>
  </head>
  <body>
    <?= $this->workarea ?>
    <hr>
    <p>RenderPage <?= \renderpage\libs\RenderPage::RENDERPAGE_VERSION ?></p>
  </body>
</html>
