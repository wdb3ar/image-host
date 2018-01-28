    <div class="row">

      <?php if ($images): ?>
        <?php foreach ($images as $image): ?>
        <div class="col-md-4">
          <div class="card mb-4 box-shadow">
            <img class="card-img-top" src="<?=File::getFile($image->path)?>" alt="<?=$image->name?>">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group" data-id="<?=$image->id?>">
                  <button type="button" class="btn btn-sm btn-outline-secondary">Download</button>
                  <a href="/edit/<?=$image->id?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                  <button type="button" class="btn btn-sm btn-outline-secondary btn-delete">Delete</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-md-12">
           No images.
      </div>
      <?php endif; ?>

    </div>
