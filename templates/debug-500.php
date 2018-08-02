<h1><?= $e->getMessage() ?></h1>
<table>
  <tr>
    <td>File:</td>
    <td><?= $e->getFile() ?></td>
  </tr>
  <tr>
    <td>Line:</td>
    <td><?= $e->getLine() ?></td>
  </tr>
</table>
<pre class="source"><code><?= $source ?></code></pre>
<h2>Stack trace:</h2>
<ol class="stack-trace">
  <?php
  $i = 0;
  foreach ($e->getTrace() as $step):
      ?>
      <li<?= ' value="', $i++, '"' ?>>
        <span class="file" title="<?= $step['file'] ?>"><?= basename($step['file']) ?></span>
        (line: <span class="line"><?= $step['line'] ?></span>):
        <span class="class"><?= $step['class'] ?? '' ?></span><!--
        --><span class="type"><?= $step['type'] ?? '' ?></span><!--
        --><span class="function"><?= $step['function'] ?></span><!--
        --><span class="args">(<?= count($step['args']) ? '...' : '' ?>)</span>
      </li>
      <?php
  endforeach;
  ?>
</ol>
