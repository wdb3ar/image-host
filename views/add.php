<form method="post" enctype="multipart/form-data">
  <div class="form-group<?= !empty($errors['image']) ? ' has-danger' : '' ?>">
    <label for="inputFile">File input</label>
    <input type="file" name="inputFile" class="form-control-file" id="inputFile" aria-describedby="fileHelp">
    <?= !empty($errors['image']) ? $errors['image'] : '' ?>
  </div>
  <div class="form-group">
    <label class="form-control-label" for="exampleInputEmail1">Email address</label>
    <input type="text" name="test" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
