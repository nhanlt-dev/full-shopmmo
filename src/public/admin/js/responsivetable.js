$(document).ready(function () {
  "use strict";
  /*======== RESPONSIVE DATA TABLE ========*/
  var responsiveDataTable = $("#responsive-data-table");
  if (responsiveDataTable.length !== 0) {
    responsiveDataTable.DataTable({
      aLengthMenu: [
        [20, 30, 50, 75, -1],
        [20, 30, 50, 75, "All"],
      ],
      pageLength: 20,
      dom: '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
    });
  }
});
