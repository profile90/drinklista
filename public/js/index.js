


$(document).ready(function() {


    $("h2").click(function() {
        let id =  $(this).data("id");
        $("#" + id).toggle();
        $("#" + id).toggleClass("hide");
    })


    $("#search").on("keyup", function() {

        let value = $(this).val().toLowerCase();

        $(".item").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });


    $("#random").click(function() {
        let n_elements = $(".item").length;
        let random = Math.floor(Math.random() * n_elements);     

        while($(".item").eq(random).hasClass("not_in_stock")) {
            random = Math.floor(Math.random() * n_elements);     
        }
        $(".item").eq(random).clone().appendTo("#random-deposit")


    })
    
})


