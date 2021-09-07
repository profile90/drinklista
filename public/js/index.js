

$(document).ready(function() {

    $(".list").each(function(index) {
        if($(this).attr('id') != "random-deposit") {
            $(this).toggle().toggleClass("hide");
        }
    });

    $("h2").click(function() {
        
        
        let id =  $(this).data("id");
        let element = $("#" + id);
        element.toggle();
        element.toggleClass("hide");

        let child = $(this).children()[0];
        
        if($(child).text().includes("expand_more")) {
            $(child).text("expand_less")
        }
        else 
        {
            $(child).text("expand_more")
        }

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


