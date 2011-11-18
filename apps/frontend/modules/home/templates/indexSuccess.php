<h2>Cached files</h2>

<?php if (count($files) <= 0): ?>
  <p>No files cached yet.</p>
<?php else: ?>
  <ul>
  <?php foreach ($files as $file): ?>
    <li><a href="/uploads/<?php echo $file ?>"><?php echo $file ?></a></li>
  <?php endforeach ?>
  </ul>
<?php endif ?>
