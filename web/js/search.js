/**
 * Created by Erik van Gemert- on 10-5-2017.
 */
$( document ).ready(function() {
    //als er een toets wordt ingedrukt komt hij in deze code
    searchField.keyup(function(evt){
        //maken van een ajax call naar de zoek functie
        $.ajax({
            type: "POST",
            url: "/app_dev.php/inkoper/zoek",
            dataType: "json",
            data: {searchText : $(this).val()},
            success : function(response)
            {
                //leeghalen van de bestaande tabel
                $("#artikelen").find("tr:gt(0)").remove();
                $.each(response, function(index,response){
                    //voor ieder object maken we een nieuwe tabel regel aan en vullen we deze met de gekregen data
                   $('#artikelen').append('<tr><td>'+response.artikelnummer+'</td><td>'+response.magazijnlocatie+'</td><td>'+response.inkoopprijs+'</td><td>'+response.vooraad+'</td></tr>');
                });
            }
        });
    });
});