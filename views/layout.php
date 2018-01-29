<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Image Host</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/selectize.default.css" rel="stylesheet">
    <link href="/css/magnific-popup.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="/css/custom.css" rel="stylesheet">
  </head>

  <body>

    <header>

      <div class="navbar navbar-dark bg-dark box-shadow">
        <div class="container d-flex justify-content-between">
          <div class="navbar-container">
          <a href="/" class="navbar-brand d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
            <strong>Image Host</strong>
          </a>
          <?php if ($uriPath != '/add'): ?>
            <a href="/add" class="btn btn-primary">Add an Image</a>
          <?php endif; ?>
        </div>

          <form method="get" action="/search" class="form-inline my-2 my-lg-0 form-search">
            <input name="q" value="<?= !empty($query) ? html($query) : '' ?>" class="form-control mr-sm-2" type="search" placeholder="Enter the tag ..." aria-label="Search">
            <button class="btn btn-secondary" type="submit">Search</button>
          </form>

        </div>
      </div>
    </header>

    <main role="main">

      <div class="album py-5 bg-light">
        <div class="container">
          <?php include __DIR__.'/'.$template.'.php' ?>
          <?php if (!empty($pager) && $pager->totalPages>1): ?>
          <ul id="pagination" class="pagination-sm"></ul>
        <?php endif; ?>
        </div>
      </div>

    </main>
    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="/js/selectize.min.js"></script>
    <script src="/js/jquery.magnific-popup.min.js"></script>
    <?php if (!empty($pager) && $pager->totalPages>1): ?>
      <script src="/js/jquery.twbsPagination.min.js"></script>
      <script>
      $('#pagination').twbsPagination({
        startPage: <?=$pager->page?>,
        totalPages: <?=$pager->totalPages?>,
        visiblePages: 7,
        initiateStartPageClick: false,
        onPageClick: function (event, page) {
          if (page == 1) {
            return window.location.href = "/";
          }
          return window.location.href = "/?page="+page;
        }
      });
      </script>
    <?php endif; ?>
    <script src="/js/custom.js"></script>
  </body>
</html>
