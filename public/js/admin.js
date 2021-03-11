var adminNews = {
    modal: function(newsId) {
        $("#modalNews").modal('hide');
        $.get('/admin/news/' + newsId + '/', function (json) {
            if(json.result) {
                $("#modalNews").remove();
                $("body").append(json.content);
                $("#modalNews").modal('show');
                $(document).one('hidden.bs.modal', '#modalNews', function() {
                    $("#modalNews").remove();
                });
            }
            if (typeof (json.error) !== "undefined") {
                alert(json.error);
            }
        }, "json");
    },
    delete: function(newsId) {
        let state = confirm("Вы действительно хотите удалить?");
        if(!state) {
            return;
        }
        $.ajax({
            url: '/admin/news/' + newsId + '/',
            type: 'DELETE',
            success: function() {
                location.reload();
            }
        });
    }
};

var adminUsers = {
    modal: function(userId) {
        $("#modalUser").modal('hide');
        $.get('/admin/users/' + userId + '/', function (json) {
            if(json.result) {
                $("#modalUser").remove();
                $("body").append(json.content);
                $("#modalUser").modal('show');
                $(document).one('hidden.bs.modal', '#modalUser', function() {
                    $("#modalUser").remove();
                });
            }
            if (typeof (json.error) !== "undefined") {
                alert(json.error);
            }
        }, "json");
    },
    delete: function(userId) {
        let state = confirm("Вы действительно хотите удалить?");
        if(!state) {
            return;
        }
        $.ajax({
            url: '/admin/users/' + userId + '/',
            type: 'DELETE',
            success: function() {
                location.reload();
            }
        });
    }    
};