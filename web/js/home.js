$('#selections .image-container').imagesLoaded()
  .always( function( instance ) {
  })
  .done( function( instance ) {
    for (let image of instance.elements) {
      $(image).addClass('loaded');
    }
  })
  .fail( function() {
  })
  .progress( function( instance, image ) {
    var result = image.isLoaded ? 'loaded' : 'broken';
  });

$('#brands .image-container').imagesLoaded()
  .always( function( instance ) {
  })
  .done( function( instance ) {
    for (let image of instance.elements) {
      $(image).addClass('loaded');
    }
  })
  .fail( function() {
  })
  .progress( function( instance, image ) {
    var result = image.isLoaded ? 'loaded' : 'broken';
  });
