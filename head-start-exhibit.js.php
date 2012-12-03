SimileAjax.jQuery(document).ready(function() {
  window.database = Exhibit.Database.create();
  window.database.loadDataLinks(function() {
    window.exhibit = Exhibit.create();
    window.exhibit.configureFromDOM();
  });
});
