    <div class="row">

      <?php if ($images): ?>
        <?php foreach ($images as $image): ?>
        <div class="col-md-4">
          <div class="card mb-4 box-shadow">
            <img class="card-img-top gallery-item" src="<?=html(File::getFile($image->path))?>" alt="<?=html($image->name)?>">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group" data-id="<?=html($image->id)?>">
                  <a href="/edit/<?=html($image->id)?>" class="btn btn-sm btn-outline-secondary">Edit</a>
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
