$(document).ready(function () {
    $("#delivery_barcode").autocomplete({
        source: "/barcode",
        select: function (event, ui) {
            $('#delivery_barcode').val(ui.item.barcode);
            return false;
        }
    }).data( "uiAutocomplete" )
        ._renderItem = function( ul, item ) {
        return $("<li class='form-control' style='width: 16%'></li>").data("item.autocomplete", item)
            .append(item.barcode)
            .appendTo(ul);
    };
});