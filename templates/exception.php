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
      .source,
      .stack-trace {
        background: #3f3f3f;
        color: #fff;
        font-size: 18px;
        width: 100%;
        display: block;
      }
      .source .number {
        background: #0c0c0c;
        color: #8a8a8a;
      }
      .source .number {
        padding: 0 16px;
      }
      .source .highlight {
        background: #101010;
        display: block;
      }
      .stack-trace .type {
        color: #9f9d6d;
      }
      .stack-trace .function {
        color: #dfc47d;
        font-weight: bold;
      }
      .source .t_open_tag,
      .source .t_doc_comment {
        color: #7f9f7f;
      }
      .source .t_if,
      .source .t_extends,
      .source .t_else,
      .source .t_echo,
      .source .t_return,
      .source .t_new,
      .source .t_namespace,
      .source .t_class,
      .source .t_use,
      .source .t_public,
      .source .t_function {
        color: #dfc47d;
        font-weight: bold;
      }
      .source .t_constant_encapsed_string {
        color: #cc9393;
      }
    </style>
  </head>
  <body>
    <h1><?php echo $e->getMessage(); ?></h1>
    <table>
      <tr>
        <td>File:</td>
        <td><?php echo $trace[0]['file']; ?></td>
      </tr>
      <tr>
        <td>Line:</td>
        <td><?php echo $trace[0]['line']; ?></td>
      </tr>
    </table>
    <pre class="source"><code><?php echo $source; ?></code></pre>
    <h2>Stack trace:</h2>
    <ol class="stack-trace">
<?php foreach ($e->getTrace() as $step) { ?>
      <li>
        <span class="file"><?php echo basename($step['file']); ?></span>
        (line: <span class="line"><?php echo $step['line']; ?></span>):
        <span class="class"><?php echo $step['class']; ?></span><!--
        --><span class="type"><?php echo $step['type']; ?></span><!--
        --><span class="function"><?php echo $step['function']; ?></span>()
      </li>
<?php } ?>
    </ol>
    <hr>
    <p>RenderPage <?php echo renderpage\libs\RenderPage::RENDERPAGE_VERSION; ?></p>
  </body>
</html>
