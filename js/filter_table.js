$(document).ready(function(){
    //#myInput är namnet på filtreringsrutan
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    //myTable är id:t på tabellen som skall filtreras,
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});