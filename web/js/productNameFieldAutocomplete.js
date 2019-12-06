$(document).ready(function () {
    $("#sale_productName").autocomplete({
        source: "/salesProductName",
        select: function (event, ui) {
            $('#sale_productName').val(ui.item.productName);
            return false;
        }
    }).data( "uiAutocomplete" )
        ._renderItem = function( ul, item ) {
        return $("<li class='form-control' style='width: 16%'></li>").data("item.autocomplete", item)
            .append(item.productName)
            .appendTo(ul);
    };
});