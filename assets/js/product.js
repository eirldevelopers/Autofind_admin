$( document ).ready(function() {
    $('#dataTable').DataTable();
    product_img.onchange = evt => {
        const [file] = product_img.files
        if (file) {
            $('#new_product_img').html('<img id="blah" src="#" width="200" height="200" alt="your image" />');
            blah.src = URL.createObjectURL(file);
        } else {
            $('#new_product_img').html('');
        }
    }
    add_product_image.onchange = evt => {
        const [file] = add_product_image.files
        if (file) {
            $('#add_new_product_img').html('<img id="new_blah" src="#" width="200" height="200" alt="your image" />');
            new_blah.src = URL.createObjectURL(file);
        } else {
            $('#add_new_product_img').html('');
        }
      }
});

function add_product() {
    $('.modal-content').addClass('sk-loading');
    document.getElementById("myForm").reset();
    $('.value_data').html('');
    $(".modal-title").html("Add Product");
    $.ajax({
        type: "post",
        data: {
            "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("get_all_category1")
        },
        url: baseUrl+'controllers/product.php',
        dataType: "json",
        success: function (data) {
            $('.modal-content').removeClass('sk-loading');
            $("#productModal").modal("show");
            html = '<option value="">Select Category..</option>';
            data.forEach(element => {
                html += '<option value="'+element.id+'">'+element.l1_category+'</option>';
            });
            $("#l2c_id").html(html);
        },
        error: function () {

        }
    });
}

function load_categories_l2(l1c_id) {
    $('#category_l2_data').html('');
    $.ajax({
        url: baseUrl+'controllers/product.php',
        method: 'POST',
        dataType: "json",
        data: {l1c_id:l1c_id,"c1e7c5cfd3d06ee8ef28b5c807d50f3b":btoa("get_all_category1")},
        success: function(data){
            console.log(data);
            html = '<option value="">Select Category..</option>';
            data.forEach(element => {
                html += '<option value="'+element.id+'">'+element.l1_category+'</option>';
            });
            $(".l2c_id").html(html);
        }
    });
}

function load_unit_value(unit_id) {
    $('#category_l2_data').html('');
    $.ajax({
        url: baseUrl+'controllers/product.php',
        method: 'POST',
        dataType: "json",
        data: {
            unit_id : unit_id,
            "c1e7c5cfd3d06ee8ef28b5c807d50f3b":btoa("load_unit_value")},
        success: function(data){
            console.log(data);
            $(".value_data").html(data);
        }
    });
}


function create_product(e) {
	$('.modal-content').addClass('sk-loading');
    var form = new FormData(document.getElementById('myForm'));
    $.ajax({
        type: 'POST',
        url: baseUrl+"controllers/product.php",
        data: form,
        dataType: 'json',
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function(){},
        success : function(response){
            console.log(response);
            if (response.status == 1) {
                setTimeout(function() {
                    window.location.reload();
                }, 800);
            } else {
                $('.modal-content').removeClass('sk-loading');
            }
        }
    });
}

function update_product(e) {
	$('.modal-content').addClass('sk-loading');
    var form = new FormData(document.getElementById('editForm'));
    $.ajax({
        type: 'POST',
        url: baseUrl+"controllers/product.php",
        data: form,
        dataType: 'json',
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function(){},
        success : function(response){
            if (response.status == 1) {
                $('.modal-content').removeClass('sk-loading');
                setTimeout(function() {
                    window.location.reload();
                }, 800);
            } else {
                $('.modal-content').removeClass('sk-loading');
            }
        }
    });
}

function change_product_status(e) {
     if(confirm("Are you sure want to chnange status ?")) {
	var id = $(e).attr("data-itemid");
    if ($('#product_'+id).is(":checked")) {
		var status = 1;
	} else { var status = 0; }
	$.ajax({
        type: "post",
        data: {
            status: status,
            id : id,
            "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("change_product_status")
        },
        url: baseUrl+'controllers/product.php',
        dataType: "json",
        success: function (data) {},
        error: function () {}
    });
  }
}

function edit_product(id) {
    $(".modal-title").html("Edit Product");
    $("#editModal").modal("show");
    $('.modal-content').addClass('sk-loading');
    $.ajax({
        type: "post",
        data: {
            id : id,
            "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("edit_product")
        },
        url: baseUrl+'controllers/product.php',
        dataType: "json",
        success: function (response) {
            alert();
            $.ajax({
        type: "post",
        data: {
            "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("get_all_category1")
        },
        url: baseUrl+'controllers/product.php',
        dataType: "json",
        success: function (data) {
            //$('.modal-content').removeClass('sk-loading');
           // $("#productModal").modal("show");
            html = '<option value="">Select Category..</option>';
            data.forEach(element => {
                html += '<option value="'+element.id+'">'+element.l1_category+'</option>';
            });
            $(".edit_l2c_id").html(html);
        },
        error: function () {

        }
    });
            console.log(response);
            $('.modal-content').removeClass('sk-loading');
            if (response.status == 1) {
                result = response.data
                // $(".l1c_id").html(result.categories);
                $(".l2c_id").html(result.subcategories);
                $(".description").val(result.description);
                $(".product_name").val(result.product_name);
                // $(".value_data").html(result.unit_value);
                $(".units").val(result.unit);
                $(".product_img").html('<img src="'+ baseUrl+ '../images/product/' +result.product_image +'" alt="Product Image" width="200" height="200">');
                $("#product_id").val(result.id);
            } else {
                alert(data.message)
            }
        },
        error: function (error) {
            console.log(error);
        }
    });
}


function delete_product(id) {
    if(confirm("Are you sure?")) {
        $('.modal-content').addClass('sk-loading');
        $.ajax({
            type: "post",
            data: {
                id : id,
                "c1e7c5cfd3d06ee8ef28b5c807d50f3b": btoa("delete_product")
            },
            url: baseUrl+'controllers/product.php',
            dataType: "json",
            success : function(response) {
                if (response.status == 1) {
                    setTimeout(function() {
                        window.location.reload();
                    }, 800);
                    
                } else {
                    $('.modal-content').removeClass('sk-loading');
                }
            }
        });
    } else {
        return false;
    }

}
