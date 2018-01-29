$(document).ready(function() {
  $('#inputTags').selectize({
    plugins: ['remove_button'],
    delimiter: ',',
    persist: false,
    create: function(input) {
      return {
        value: input,
        text: input
      }
    }
  });

  $('.card .btn-delete').click(function() {
    if (confirm("Are you sure you want to delete the image?")) {
      var imageId = $(this).parent().attr('data-id');
      $.ajax({
        url: "/delete",
        method: "POST",
        data: {
          imageId: imageId
        },
        success: function(data) {
          data = JSON.parse(data)
          if (data.status) {
            window.location.reload(true);
          }
        }
      });
    }
  });

  $('.album .gallery-item').magnificPopup({
    type: 'image',
    gallery:{
      enabled:true
    }
  });

  $('.img-popup-link').magnificPopup({
    type: 'image'
  });
});
