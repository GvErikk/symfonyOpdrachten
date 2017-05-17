/**
 * Created by Erik van Gemert- on 10-5-2017.
 */
$( document ).ready(function() {
    var searchField = $('#search-field');



    searchField.keyup(function(evt){

        $.ajax({
            type: "POST",
            url: "/symfonyOpdrachten/web/app_dev.php/inkoper/zoek",
            dataType: "json",
            data: {searchText : $(this).val()},
            success : function(response)
            {
                $("#artikelen").find("tr:gt(0)").remove();
                $.each(response, function(index,response){
                   $('#artikelen').append('<tr onclick="window.document.location=\'artikel/'+response.artikelnummer+' \';" ><td>'+response.artikelnummer+'</td><td>'+response.magazijnlocatie+'</td><td>'+response.inkoopprijs+'</td><td>'+response.vooraad+'</td></tr>');
                   // $('#artikelen').append('<tr onclick="window.document.location=\'artikel/'+response.artikelnummer+' \';" ><td>'+response.artikelnummer+'</td><td>'+response.magazijnlocatie+'</td><td>'+response.inkoopprijs+'</td><td>'+response.vooraad+'</td></tr>');
                });
            }
        });
    });
});