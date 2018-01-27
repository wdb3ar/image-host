<div class="card mb-3">
  <img class="card-img-top" src="<?=File::getFile($image->path)?>" alt="<?=$image->name?>">
  <div class="card-body">
    <form method="post" enctype="multipart/form-data">
      <div class="form-group<?= !empty($errors['tags']) ? ' has-danger' : '' ?>">
        <label for="inputTags">Tags</label>
        <input type="text" name="tags" id="inputTags" placeholder="Enter tags" value="<?=$image->tag_names?>">
        <?= !empty($errors['tags']) ? $errors['tags'] : '' ?>
        <small class="form-text text-muted">Please enter at least one tag.</small>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</div>
