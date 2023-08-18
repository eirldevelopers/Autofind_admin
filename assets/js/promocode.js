  /*-------------------promocode Section----------------------*/
function add_promocode() {
    $("#promocodeimg").val("");
    $(".modal-title").html("Add promocode");
    $("#myModal").modal("show");
}
/*-------------------promocode Section----------------------*/

function edit_promocode(id) {
    $(".modal-title").html("Edit promocode");
    $.ajax({
        type: "post",
        data: {
            id : id,
            "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("edit_promocode")
        },
        url: baseUrl+'controllers/promocode.php',
        dataType: "json",
        success: function (data) {
            $("#EditModal").modal("show");
            $("#promocode_one_id").val(data.id);
            $("#edit_promocode").val(data.promocode);
            $("#edit_promocode_title").val(data.promocode_title);
            $("#edit_promocode_description").val(data.promocode_description);
            $("#edit_number_of_time_usage").val(data.number_of_time_usage);
            $("#edit_per_user").val(data.per_user);
            $("#edit_promocode_type").val(data.promocode_type);
            $("#edit_discount").val(data.discount);
            $("#edit_expiry_date").val(data.expiry_date);
           // $('.show-images').html('<img src="'+baseUrl+ 'assets/img/category/' +data.image_file + '" alt="category image" width="80" height="80">')
            // $("#image_file_one").val(data.image_file);
            // $("#image_file").val(data.image_file);
        },
        error: function () {

        }
    });
    
}

function create_promocode(e) {
     //alert('hello');
    $('.modal-content').addClass('sk-loading');
    var form = new FormData(document.getElementById('myForm'));
    //append files
    $.ajax({
        type: 'POST',
        url: baseUrl+"controllers/promocode.php",
        data: form,
        dataType: 'json',
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function(){
            $('.submitBtn').attr("disabled","disabled");
            $('#fupForm').css("opacity",".5");
        },
            success : function(response) {
                if (response.status == 1) {
                    show_message(response.message, 'Success', MSG_SUCCESS);
                    setTimeout(function() {
                        window.location.reload();
                    }, 800);
                    
                } else {
                    show_message(response.message, 'Error', MSG_ERROR);
                    $('.modal-content').removeClass('sk-loading');
                }
        }
    });
}

function update_promocode(e) {

    $('.modal-content').addClass('sk-loading');
    var form = new FormData(document.getElementById('editForm'));
    //append files
   
    $.ajax({
        type: 'POST',
        url: baseUrl+"controllers/promocode.php",
        data: form,
        dataType: 'json',
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function(){
            $('.submitBtn').attr("disabled","disabled");
            $('#fupForm').css("opacity",".5");
        },
            success : function(response){
                if (response.status == 1) {
                    show_message(response.message, 'Success', MSG_SUCCESS);
                    setTimeout(function() {
                        window.location.reload();
                    }, 800);
                    
                } else {
                    show_message(response.message, 'Error', MSG_ERROR);
                    $('.modal-content').removeClass('sk-loading');
                }
        }
    });
}

function delete_promocode(id) {
    if(confirm("Are you sure?")) {
        $('.modal-content').addClass('sk-loading');
        $.ajax({
            type: "post",
            data: {
                id : id,
                "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("delete_promocode")
            },
            url: baseUrl+'controllers/promocode.php',
            dataType: "json",
            success : function(response) {
                if (response.status == 1) {
                    show_message(response.message, 'Success', MSG_SUCCESS);
                    setTimeout(function() {
                        window.location.reload();
                    }, 800);
                    
                } else {
                    show_message(response.message, 'Error', MSG_ERROR);
                    $('.modal-content').removeClass('sk-loading');
                }
            }
        });
    } else {
        return false;
    }

}
function change_promocode_status(e) {
    var id = $(e).attr("data-itemid");
    if ($('#promocode_'+id).is(":checked")) {
        var status = 1;
    } else {
        var status = 0;
    }

    $.ajax({
        type: "post",
        data: {
            status: status,
            item_id : id,
            "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("change_promocode_status")
        },
        url: baseUrl+'controllers/promocode.php',
        dataType: "json",
        success: function (data) {
        },
        error: function () {

        }
    });
}
var MSG_ERROR = 1;
var MSG_WARNING = 2;
var MSG_SUCCESS = 3;
var MSG_INFORMATION = 4;

function show_message(body, title, type)
{
    if(type == MSG_ERROR)
    {
        if(title == '') { title = "Error"; }
        toastr.error(body, title);
    }
    else if(type == MSG_WARNING)
    {
        if(title == '') { title = "Warning"; }
        toastr.warning(body, title);
    }
    else if(type == MSG_SUCCESS)
    {
        if(title == '') { title = "Success"; }
        toastr.success(body, title);
    }
    else if(type == MSG_INFORMATION)
    {
        if(title == '') { title = "Information"; }
        toastr.info(body, title);
    }
}