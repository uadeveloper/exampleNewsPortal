$(function () {

    $(document).on("submit", ".coreAppAjaxForm", function (e) {

        var form = $(this);

        let actionUrl = $(this).attr("action");
        if (!actionUrl) {
            actionUrl = location.href;
        }

        let method = $(this).attr("method");
        if (!method) {
            method = "post";
        }

        $(this).find(".formError").stop().slideUp(500);

        $.ajax({
            url: actionUrl,
            type: method,
            dataType: "json",
            data: form.serialize(),
            success: function (json) {

                if (typeof (json.error) !== "undefined") {
                    form.find(".formError").stop().slideUp(500, function () {
                        $(this).html(json.error);
                        $(this).slideDown(500);
                    });
                    return;
                }

                if (typeof (json.result) !== "undefined" && json.result) {
                    if (form.data("success-url")) {
                        location.href = form.data("success-url");
                    }
                }

            },
            error: function (response) {
                form.find(".formError").stop().slideUp(500, function () {
                    $(this).html("Ошибка передачи данных.");
                    $(this).slideDown(500);
                });
                console.error("Fail send data", response);
            }
        });

        e.preventDefault();
    });

});