var MSG_ERROR = 1;
var MSG_WARNING = 2;
var MSG_SUCCESS = 3;
var MSG_INFORMATION = 4;

$(document).on("click", ".colorbox-link", function(){
    event.preventDefault();
    var elementUrl = $(this).attr("href");
    $.colorbox({href:elementUrl, minWidth:"100%", minHeight:"100%", width:"100%", height:"100%", open:true, iframe:true});
});

$(document).on("click", ".colorbox-link-1", function(){
    event.preventDefault();
    var elementUrl = $(this).attr("href");
    $.colorbox({href:elementUrl, minWidth:"100%", minHeight:"100%", width:"100%", height:"100%", open:true, iframe:false});
});

$(document).on("hidden.bs.modal", "#myModal", function(){
    $(".modal-body").html("");
    $("#myForm").removeAttr("onsubmit");
    $("#myForm").removeAttr("action");
});

$(document).on("change", ".image_file", function(e){
    var fileExtension = ['jpg','jpeg','png'];
    for(var i=0;i<$(this).get(0).files.length;i++)
    {
        var file = $(this).get(0).files[i].name;
        if ($.inArray(file.split('.').pop().toLowerCase(), fileExtension) == -1) {
            $(this).val("");
            alert("Only image is allowed.");
            $(this).html("No File Selected");
            return;
        }
    }
});

$(document).on("change", "#icon_file", function(e){
    var fileExtension = ['jpg','jpeg','png'];
    for(var i=0;i<$(this).get(0).files.length;i++)
    {
        var file = $(this).get(0).files[i].name;
        if ($.inArray(file.split('.').pop().toLowerCase(), fileExtension) == -1) {
            $(this).val("");
            alert("Only image is allowed.");
            $(this).html("No File Selected");
            return;
        }
        
        var self = $(this);
                    
        var reader = new FileReader();
        reader.readAsDataURL($(this)[0].files[0]);
        reader.onload = function (e) {

            //Initiate the JavaScript Image object.
            var image = new Image();

            //Set the Base64 string return from FileReader as source.
            image.src = e.target.result;

            //Validate the File Height and Width.
            image.onload = function () {
              var height = this.height;
              var width = this.width;
              if (height > 64 || width > 64) {
                /*alert("Image should not be greater than 64x64 resolution.");
                $(self).val("");
                $(self).html("No File Selected");*/
                return;
              }
            };
        }
    }
});

$(document).on("change", ".video_file", function(e){
    var fileExtension = ['mp4'];
    for(var i=0;i<$(this).get(0).files.length;i++)
    {
        var file = $(this).get(0).files[i].name;
        if ($.inArray(file.split('.').pop().toLowerCase(), fileExtension) == -1) {
            $(this).val("");
            alert("Only video (mp4) is allowed.");
            $(this).html("No File Selected");
            return;
        }
    }
});

function add_function()
{
    $('.modal-body').load('url', function(){
        $(".modal-dialog").removeClass("modal-sm").removeClass("modal-lg").addClass("modal-lg");
        $(".modal-title").html("Title");
        $("#myForm").attr("onsubmit", "function_name(); return false;");
        $("#chosen").chosen({"allow_single_deselect": true,width: "100%"});
        $(".modal").modal("show");
    });
}

function submit_modal_form_ajax(update_parent=0)
{
    $('.modal-content').addClass('sk-loading');
    $.ajax({
        url: "../controllers/file.php",
        method: "POST",
        data: $("#myForm").serialize(),
        success: function(data){
            response = $.parseJSON(data);
            if(response.success == 1)
            {
                $('.modal-content').removeClass('sk-loading');
                show_message(response.msg, 'Success', MSG_SUCCESS);
                if(update_parent == 1)
                {
                    parent.location.reload();
                }
                else
                {
                    window.location.reload();
                }
            }
            else
            {
                $('.modal-content').removeClass('sk-loading');
                show_message(response.msg, 'Error', MSG_ERROR);
            }
        }
    });
}

function submit_modal_form_redirect()
{
    $('.modal-content').addClass('sk-loading');
    $("#myForm").attr("action", "../controllers/file.php");
    $("#myForm").submit();
}