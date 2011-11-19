<h2>Cached files</h2>

<p id="error" style="display:none"></p>

<?php if (count($files) <= 0): ?>
  <p>No files cached yet.</p>
<?php else: ?>
  <ul>
  <?php foreach ($files as $file): ?>
    <li><a href="/uploads/<?php echo $file ?>" class="file"><?php echo $file ?></a> &nbsp;&nbsp;<a href="" class="delete">X</a></li>
  <?php endforeach ?>
  </ul>
<?php endif ?>

<script type="text/javascript">
jQuery(document).ready(function() {
  $(".delete").click(function(e){
    e.preventDefault();
    var $li = $(this).parent();
    var href = $li.find('a.file').attr('href');
    href = href.replace(/\/uploads\//, ""); // remove leading /uploads/
    $.post('/home/deletefile', {file: href}, function() {
      $li.remove();
    }).error(function(){
      $("#error").html('Failed to delete file').show();
    });
  });
});
</script>
