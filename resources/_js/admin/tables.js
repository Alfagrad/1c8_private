$(document).ready(function(e) {

    $(document).on('click','button#new-item',function(e) {
        $('div.new-item').show()
        $('button#new-item').hide();
    });

    $(document).on('click','button#search-item',function(e) {
        $('div.search-item').show()
        $('button#search-item').hide();
    });

    $( function() {
        $( "#exampleInputName21" ).datepicker();
    } );


});

