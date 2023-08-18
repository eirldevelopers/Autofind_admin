$( document ).ready(function() {
    $('#dataTable').DataTable();
});

function add_category1() {
    $("#l1_category, #image_file").val("");
    $(".modal-title").html("Add Category");
    $("#myModal").modal("show");
}

function edit_category1(id) {
    $(".modal-title").html("Edit Category");
    $.ajax({
        type: "post",
        data: {
            id : id,
            "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("edit_category1")
        },
        url: baseUrl+'controllers/category.php',
        dataType: "json",
        success: function (data) {
            $("#EditModal").modal("show");
            $("#category_one_id").val(data.id);
            $("#l_one_category").val(data.l1_category);
            $('.show-images').html('<img src="'+baseUrl+ 'assets/img/category/' +data.image_file + '" alt="category image" width="80" height="80">')
            // $("#image_file_one").val(data.image_file);
            // $("#image_file").val(data.image_file);
        },
        error: function () {

        }
    });
    
}


function create_category1(e) {

	$('.modal-content').addClass('sk-loading');
    var form = new FormData(document.getElementById('myForm'));
    //append files
    var file = document.getElementById('image_file').files[0];
    if (file) {   
        form.append('image_file', file);
    }
    $.ajax({
        type: 'POST',
        url: baseUrl+"controllers/category.php",
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

function update_category1(e) {

	$('.modal-content').addClass('sk-loading');
    var form = new FormData(document.getElementById('editForm'));
    //append files
    var file = document.getElementById('image_file_one').files[0];
    if (file) {   
        form.append('image_file', file);
    }
    $.ajax({
        type: 'POST',
        url: baseUrl+"controllers/category.php",
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

function delete_category1(id) {
    if(confirm("Are you sure?")) {
        $('.modal-content').addClass('sk-loading');
        $.ajax({
            type: "post",
            data: {
                id : id,
                "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("delete_category1")
            },
            url: baseUrl+'controllers/category.php',
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
function change_category1_status(e) {
	var id = $(e).attr("data-itemid");
    if ($('#category_'+id).is(":checked")) {
		var status = 1;
	} else {
		var status = 0;
	}

	$.ajax({
        type: "post",
        data: {
            status: status,
            item_id : id,
            "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("change_category1_status")
        },
        url: baseUrl+'controllers/category.php',
        dataType: "json",
        success: function (data) {
        },
        error: function () {

        }
    });
}

function add_category2() {
    // $("#l1_category, #image_file").val("");
    $(".modal-title").html("Add Category2");
    $.ajax({
        type: "post",
        data: {
            "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("get_all_category1")
        },
        url: baseUrl+'controllers/category.php',
        dataType: "json",
        success: function (data) {
            $("#myCategory2Modal").modal("show");
            // html = '<label for="l1c_id">Select Category</label>';
            html = '<select class="form-control" id="l1c_id" name="l1c_id" required>';
            html += '<option value="">Select Category..</option>';
            data.forEach(element => {
                html += '<option value="'+element.id+'">'+element.l1_category+'</option>';
            });
            html += '</select>';
            $(".selection").html(html);
        },
        error: function () {

        }
    });
}

function create_category2(e) {

	$('.modal-content').addClass('sk-loading');
    var form = new FormData(document.getElementById('myForm'));
    //append files
    var file = document.getElementById('image_file').files[0];
    if (file) {   
        form.append('image_file', file);
    }
    $.ajax({
        type: 'POST',
        url: baseUrl+"controllers/category.php",
        data: form,
        dataType: 'json',
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function(){
            $('.submit').attr("disabled","disabled");
            $('#editForm').css("opacity",".5");
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

function edit_category2(id, cid) {
    // $("#l1_category, #image_file").val("");
    $(".modal-title").html("Edit Category2");
    // $("#EditCategory2Modal").modal("show");
    $.ajax({
        type: "post",
        data: {
            id : id,
            "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("edit_category2")
        },
        url: baseUrl+'controllers/category.php',
        dataType: "json",
        success: function (data) {
            $("#EditCategory2Modal").modal("show");
            $("#category_two_id").val(data[1].id);
            $("#l_two_category").val(data[1].l2_category);
            $('.show-images').html('<img src="'+baseUrl+ 'assets/img/category/' +data[1].image_file + '" alt="category image" width="80" height="80">')
            html = '<label for="l1c_id">Select Category</label>';
            html += '<select class="form-control" id="l1c_id" name="l1c_id" required>';
            html += '<option value="">Select Category..</option>';
            data[0].forEach(element => {
                if (element.id == cid) {
                    html += '<option selected="selected" value="'+element.id+'">'+element.l1_category+'</option>';
                } else {
                    html += '<option value="'+element.id+'">'+element.l1_category+'</option>';
                }
            });
            html += '</select>';
            $(".selection").html(html);
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function change_category2_status(e) {
	var id = $(e).attr("data-itemid");
    if ($('#category_'+id).is(":checked")) {
		var status = 1;
	} else {
		var status = 0;
	}

	$.ajax({
        type: "post",
        data: {
            status: status,
            item_id : id,
            "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("change_category2_status")
        },
        url: baseUrl+'controllers/category.php',
        dataType: "json",
        success: function (data) {
        },
        error: function () {

        }
    });
}

function update_category2(e) {

	$('.modal-content').addClass('sk-loading');
    var form = new FormData(document.getElementById('editForm'));
    //append files
    var file = document.getElementById('image_file_one').files[0];
    if (file) {   
        form.append('image_file', file);
    }
    $.ajax({
        type: 'POST',
        url: baseUrl+"controllers/category.php",
        data: form,
        dataType: 'json',
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function(){
            $('.submit').attr("disabled","disabled");
            $('#editForm').css("opacity",".5");
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

function delete_category2(id) {
    if(confirm("Are you sure?")) {
        $('.modal-content').addClass('sk-loading');
        $.ajax({
            type: "post",
            data: {
                id : id,
                "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("delete_category2")
            },
            url: baseUrl+'controllers/category.php',
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

function add_category3() {
    // $("#l1_category, #image_file").val("");
    $(".modal-title").html("Add Category3");
    $.ajax({
        type: "post",
        data: {
            "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("get_all_category2")
        },
        url: baseUrl+'controllers/category.php',
        dataType: "json",
        success: function (data) {
            $("#myCategory3Modal").modal("show");
            html = '<label for="l2c_id">Select Category</label>';
            html += '<select class="form-control" id="l2c_id" name="l2c_id" required>';
            html += '<option value="">Select Category..</option>';
            data.forEach(element => {
                html += '<option value="'+element.id+'">'+element.l2_category+'</option>';
            });
            html += '</select>';
            $(".selection").html(html);
        },
        error: function () {

        }
    });
}

function create_category3(e) {

	$('.modal-content').addClass('sk-loading');
    var form = new FormData(document.getElementById('myForm'));
    //append files
    var file = document.getElementById('image_file').files[0];
    if (file) {   
        form.append('image_file', file);
    }
    $.ajax({
        type: 'POST',
        url: baseUrl+"controllers/category.php",
        data: form,
        dataType: 'json',
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function(){
            $('.submit').attr("disabled","disabled");
            $('#editForm').css("opacity",".5");
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

function edit_category3(id, cid) {
    $(".modal-title").html("Edit Category3");
    $.ajax({
        type: "post",
        data: {
            id : id,
            "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("edit_category3")
        },
        url: baseUrl+'controllers/category.php',
        dataType: "json",
        success: function (data) {
            $("#EditCategory3Modal").modal("show");
            $("#category_three_id").val(data[1].id);
            $("#l_three_category").val(data[1].l3_category);
            $('.show-images').html('<img src="'+baseUrl+ 'assets/img/category/' +data[1].image_file + '" alt="category image" width="80" height="80">')
            html = '<label for="l2c_id">Select Category</label>';
            html += '<select class="form-control" id="l2c_id" name="l2c_id" required>';
            html += '<option value="">Select Category..</option>';
            data[0].forEach(element => {
                if (element.id == cid) {
                    html += '<option selected="selected" value="'+element.id+'">'+element.l2_category+'</option>';
                } else {
                    html += '<option value="'+element.id+'">'+element.l2_category+'</option>';
                }
            });
            html += '</select>';
            $(".selection").html(html);
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function change_category3_status(e) {
	var id = $(e).attr("data-itemid");
    if ($('#category_'+id).is(":checked")) {
		var status = 1;
	} else {
		var status = 0;
	}

	$.ajax({
        type: "post",
        data: {
            status: status,
            item_id : id,
            "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("change_category3_status")
        },
        url: baseUrl+'controllers/category.php',
        dataType: "json",
        success: function (data) {
        },
        error: function () {

        }
    });
}

function update_category3(e) {

	$('.modal-content').addClass('sk-loading');
    var form = new FormData(document.getElementById('editForm'));
    //append files
    var file = document.getElementById('image_file_one').files[0];
    if (file) {   
        form.append('image_file', file);
    }
    $.ajax({
        type: 'POST',
        url: baseUrl+"controllers/category.php",
        data: form,
        dataType: 'json',
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function(){
            $('.submit').attr("disabled","disabled");
            $('#editForm').css("opacity",".5");
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

function delete_category3(id) {
    if(confirm("Are you sure?")) {
        $('.modal-content').addClass('sk-loading');
        $.ajax({
            type: "post",
            data: {
                id : id,
                "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("delete_category3")
            },
            url: baseUrl+'controllers/category.php',
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

function add_category4() {
    // $("#l1_category, #image_file").val("");
    $(".modal-title").html("Add Category4");
    $.ajax({
        type: "post",
        data: {
            "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("get_all_category3")
        },
        url: baseUrl+'controllers/category.php',
        dataType: "json",
        success: function (data) {
            $("#myCategory4Modal").modal("show");
            html = '<label for="l3c_id">Select Category</label>';
            html += '<select class="form-control" id="l3c_id" name="l3c_id" required>';
            html += '<option value="">Select Category..</option>';
            data.forEach(element => {
                html += '<option value="'+element.id+'">'+element.l3_category+'</option>';
            });
            html += '</select>';
            $(".selection").html(html);
        },
        error: function () {

        }
    });
}

function create_category4(e) {

	$('.modal-content').addClass('sk-loading');
    var form = new FormData(document.getElementById('myForm'));
    //append files
    var file = document.getElementById('image_file').files[0];
    if (file) {   
        form.append('image_file', file);
    }
    $.ajax({
        type: 'POST',
        url: baseUrl+"controllers/category.php",
        data: form,
        dataType: 'json',
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function(){
            $('.submit').attr("disabled","disabled");
            $('#editForm').css("opacity",".5");
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

function edit_category4(id, cid) {
    $(".modal-title").html("Edit Category4");
    $.ajax({
        type: "post",
        data: {
            id : id,
            "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("edit_category4")
        },
        url: baseUrl+'controllers/category.php',
        dataType: "json",
        success: function (data) {
            $("#EditCategory4Modal").modal("show");
            $("#category_four_id").val(data[1].id);
            $("#l_four_category").val(data[1].l4_category);
            $('.show-images').html('<img src="'+baseUrl+ 'assets/img/category/' +data[1].image_file + '" alt="category image" width="80" height="80">')
            html = '<label for="l3c_id">Select Category</label>';
            html += '<select class="form-control" id="l3c_id" name="l3c_id" required>';
            html += '<option value="">Select Category..</option>';
            data[0].forEach(element => {
                if (element.id == cid) {
                    html += '<option selected="selected" value="'+element.id+'">'+element.l3_category+'</option>';
                } else {
                    html += '<option value="'+element.id+'">'+element.l3_category+'</option>';
                }
            });
            html += '</select>';
            $(".selection").html(html);
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function change_category4_status(e) {
	var id = $(e).attr("data-itemid");
    if ($('#category_'+id).is(":checked")) {
		var status = 1;
	} else {
		var status = 0;
	}

	$.ajax({
        type: "post",
        data: {
            status: status,
            item_id : id,
            "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("change_category4_status")
        },
        url: baseUrl+'controllers/category.php',
        dataType: "json",
        success: function (data) {
        },
        error: function () {

        }
    });
}

function update_category4(e) {

	$('.modal-content').addClass('sk-loading');
    var form = new FormData(document.getElementById('editForm'));
    //append files
    var file = document.getElementById('image_file_one').files[0];
    if (file) {   
        form.append('image_file', file);
    }
    $.ajax({
        type: 'POST',
        url: baseUrl+"controllers/category.php",
        data: form,
        dataType: 'json',
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function(){
            $('.submit').attr("disabled","disabled");
            $('#editForm').css("opacity",".5");
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

function delete_category4(id) {
    if(confirm("Are you sure?")) {
        $('.modal-content').addClass('sk-loading');
        $.ajax({
            type: "post",
            data: {
                id : id,
                "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("delete_category4")
            },
            url: baseUrl+'controllers/category.php',
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

function add_category5() {
    // $("#l1_category, #image_file").val("");
    $(".modal-title").html("Add Category5");
    $.ajax({
        type: "post",
        data: {
            "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("get_all_category4")
        },
        url: baseUrl+'controllers/category.php',
        dataType: "json",
        success: function (data) {
            $("#myCategory5Modal").modal("show");
            html = '<label for="l4c_id">Select Category</label>';
            html += '<select class="form-control" id="l4c_id" name="l4c_id" required>';
            html += '<option value="">Select Category..</option>';
            data.forEach(element => {
                html += '<option value="'+element.id+'">'+element.l4_category+'</option>';
            });
            html += '</select>';
            $(".selection").html(html);
        },
        error: function () {

        }
    });
}

function create_category5(e) {

	$('.modal-content').addClass('sk-loading');
    var form = new FormData(document.getElementById('myForm'));
    //append files
    var file = document.getElementById('image_file').files[0];
    if (file) {   
        form.append('image_file', file);
    }
    $.ajax({
        type: 'POST',
        url: baseUrl+"controllers/category.php",
        data: form,
        dataType: 'json',
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function(){
            $('.submit').attr("disabled","disabled");
            $('#editForm').css("opacity",".5");
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

function edit_category5(id, cid) {
    $(".modal-title").html("Edit Category5");
    $.ajax({
        type: "post",
        data: {
            id : id,
            "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("edit_category5")
        },
        url: baseUrl+'controllers/category.php',
        dataType: "json",
        success: function (data) {
            $("#category_five_id").val(data[1].id);
            $("#l_five_category").val(data[1].l5_category);
            $('.show-images').html('<img src="'+baseUrl+ 'assets/img/category/' +data[1].image_file + '" alt="category image" width="80" height="80">')
            $("#EditCategory5Modal").modal("show");
            html = '<label for="l4c_id">Select Category</label>';
            html += '<select class="form-control" id="l4c_id" name="l4c_id" required placeholder="Select Category..">';
            data[0].forEach(element => {
                if (element.id == cid) {
                    html += '<option selected="selected" value="'+element.id+'">'+element.l4_category+'</option>';
                } else {
                    html += '<option value="'+element.id+'">'+element.l4_category+'</option>';
                }
            });
            html += '</select>';
            $(".selection").html(html);
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function change_category5_status(e) {
	var id = $(e).attr("data-itemid");
    if ($('#category_'+id).is(":checked")) {
		var status = 1;
	} else {
		var status = 0;
	}

	$.ajax({
        type: "post",
        data: {
            status: status,
            item_id : id,
            "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("change_category5_status")
        },
        url: baseUrl+'controllers/category.php',
        dataType: "json",
        success: function (data) {
        },
        error: function () {

        }
    });
}

function update_category5(e) {

	$('.modal-content').addClass('sk-loading');
    var form = new FormData(document.getElementById('editForm'));
    //append files
    var file = document.getElementById('image_file_one').files[0];
    if (file) {   
        form.append('image_file', file);
    }
    $.ajax({
        type: 'POST',
        url: baseUrl+"controllers/category.php",
        data: form,
        dataType: 'json',
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function(){
            $('.submit').attr("disabled","disabled");
            $('#editForm').css("opacity",".5");
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

function delete_category5(id) {
    if(confirm("Are you sure?")) {
        $('.modal-content').addClass('sk-loading');
        $.ajax({
            type: "post",
            data: {
                id : id,
                "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("delete_category5")
            },
            url: baseUrl+'controllers/category.php',
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

function edit_terms() {
    $.ajax({
        type: "post",
        data: {
            id : id,
            "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("edit_category2")
        },
        url: baseUrl+'controllers/category.php',
        dataType: "json",
        success: function (data) {
            $("#EditCategory2Modal").modal("show");
            $("#category_two_id").val(data[1].id);
            $("#l_two_category").val(data[1].l2_category);
            $('.show-images').html('<img src="'+baseUrl+ 'assets/img/category/' +data[1].image_file + '" alt="category image" width="80" height="80">')
            html = '<label for="l1c_id">Select Category</label>';
            html += '<select class="form-control" id="l1c_id" name="l1c_id" required>';
            html += '<option value="">Select Category..</option>';
            data[0].forEach(element => {
                if (element.id == cid) {
                    html += '<option selected="selected" value="'+element.id+'">'+element.l1_category+'</option>';
                } else {
                    html += '<option value="'+element.id+'">'+element.l1_category+'</option>';
                }
            });
            html += '</select>';
            $(".selection").html(html);
        },
        error: function (error) {
            console.log(error);
        }
    });
}

