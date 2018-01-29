<div class="card mb-3">
  <a class="img-popup-link" href="<?=html(File::getFile($image->path))?>"><img class="card-img-top" src="<?=html(File::getFile($image->path))?>" alt="<?=html($image->name)?>"></a>
  <div class="card-body">
    <form method="post" enctype="multipart/form-data">
      <div class="form-group<?= !empty($errors['tags']) ? ' has-danger' : '' ?>">
        <label for="inputTags">Tags</label>
        <input type="text" name="tags" id="inputTags" placeholder="Enter tags" value="<?=html($image->tag_names)?>">
        <?= !empty($errors['tags']) ? $errors['tags'] : '' ?>
        <small class="form-text text-muted">Please enter at least one tag.</small>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</div>
