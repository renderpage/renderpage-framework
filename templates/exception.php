<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Exception</title>
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
    </style>
  </head>
  <body>
    <h1><?php echo $errstr; ?></h1>
    <table>
      <tr>
        <td>File:</td>
        <td><?php echo $errfile; ?></td>
      </tr>
      <tr>
        <td>Line:</td>
        <td><?php echo $errline; ?></td>
      </tr>
    </table>
    <pre><?php echo $e; ?></pre>
    <hr>
    <p>RenderPage <?php echo renderpage\libs\RenderPage::RENDERPAGE_VERSION; ?></p>
  </body>
</html>
