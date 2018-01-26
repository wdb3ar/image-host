<form method="post" enctype="multipart/form-data">
  <div class="form-group<?= !empty($errors['image']) ? ' has-danger' : '' ?>">
    <label for="inputFile">File input</label>
    <input type="file" name="inputFile" class="form-control-file" id="inputFile">
    <?= !empty($errors['image']) ? $errors['image'] : '' ?>
    <small class="form-text text-muted">Allowed image formats: png, jpg, gif.</small>
  </div>
  <div class="form-group<?= !empty($errors['tags']) ? ' has-danger' : '' ?>">
    <label for="inputTags">Tags</label>
    <input type="text" name="tags" id="inputTags" placeholder="Enter tags">
    <?= !empty($errors['tags']) ? $errors['tags'] : '' ?>
    <small class="form-text text-muted">Please enter at least one tag.</small>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
