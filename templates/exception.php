<!DOCTYPE html>
<html lang="en-us">
  <head>
    <meta charset="<?= renderpage\libs\RenderPage::$charset ?>">
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
    <h1><?php echo $e->getMessage(); ?></h1>
    <table>
      <tr>
        <td>File:</td>
        <td><?php echo $file; ?></td>
      </tr>
      <tr>
        <td>Line:</td>
        <td><?php echo $line; ?></td>
      </tr>
    </table>
    <pre class="source"><code><?php echo $source; ?></code></pre>
    <h2>Stack trace:</h2>
    <ol class="stack-trace">
      <?php
      $i = 0;
      foreach ($trace as $step) {
          ?>
          <li value="<?php echo $i; ?>">
            <span class="file" title="<?php echo $step['file']; ?>"><?php echo basename($step['file']); ?></span>
            (line: <span class="line"><?php echo $step['line']; ?></span>):
            <span class="class"><?php echo $step['class']; ?></span><!--
            --><span class="type"><?php echo $step['type']; ?></span><!--
            --><span class="function"><?php echo $step['function']; ?></span><!--
            --><span class="args">(<?php echo count($step['args']) ? '...' : ''; ?>)</span>
          </li>
          <?php
          $i++;
      }
      ?>
    </ol>
    <hr>
    <p>RenderPage <?php echo renderpage\libs\RenderPage::RENDERPAGE_VERSION; ?></p>
  </body>
</html>
