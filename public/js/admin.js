$(document).ready(function() {
    $(".item").click(function() {
        let db_id = $(this).attr("id");
        $.post("toggle.php", {"id" : db_id }).done(function(data, status) {
            let response = JSON.parse(data);
            $("#" + response.id).toggleClass("not_in_stock", (response.lastState == "1"));
        });
    });

    $(".item").on("tap", function() {
        let db_id = $(this).attr("id");
        $.post("toggle.php", {"id" : db_id }).done(function(data, status) {
            let response = JSON.parse(data);
            $("#" + response.id).toggleClass("not_in_stock", (response.lastState == "1"));
        });
    });

});
