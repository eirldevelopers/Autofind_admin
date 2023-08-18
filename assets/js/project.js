var MSG_ERROR = 1;
var MSG_WARNING = 2;
var MSG_SUCCESS = 3;
var MSG_INFORMATION = 4;

toastr.options = {
  "closeButton": true,
  "debug": false,
  "progressBar": false,
  "preventDuplicates": false,
  "positionClass": "toast-top-center",
  "onclick": null,
  "showDuration": "400",
  "hideDuration": "1000",
  "timeOut": "8000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}

if (location.hostname === "localhost" || location.hostname === "127.0.0.1")
{}
else
{
    document.addEventListener('contextmenu', event => event.preventDefault());
    
    $(document).on('keydown', function(e) {
        if(e.ctrlKey && e.shiftKey && (e.key == "k" || e.keyCode == 75) ){
            e.cancelBubble = true;
            e.preventDefault();
            e.stopImmediatePropagation();
        }
        if(e.ctrlKey && e.shiftKey && (e.key == "j" || e.keyCode == 74) ){
            e.cancelBubble = true;
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    });
}

var root = (window.location.pathname == "/" || window.location.pathname == "/index.php" ? 1 : 0);

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

function IsNumeric(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function IsDecimal(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46) {
        return false;
    }
    return true;
}

function IsReadonly(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if(charCode != 9)
    return false;
}

function go(url, blank=0)
{
    if(blank)
    {
        window.open(url);
    }
    else
    {
        window.location.href = url;
    }
}

var idleTime = 0;
var idleInterval = "";

$(function(){
    if($(".chosen-select").length)
    { $(".chosen-select").chosen({width: "100%"}); }
    
    idleInterval = setInterval(timerIncrement, 60000); // 1 minute
    
    setInterval(function(){
        check_session();
    }, 30000);
    
    window.addEventListener('load', resetTimer, true);
    
    var events = ['scroll', 'mousemove', 'keydown', 'wheel', 'DOMMouseScroll', 'mousewheel', 'mousedown', 'touchstart', 'touchmove', 'MSPointerDown', 'MSPointerMove'];
    
    events.forEach(function(name) {
        document.addEventListener(name, resetTimer, true); 
    });
});

function timerIncrement()
{
    idleTime = idleTime + 1;
    if (idleTime > 14) { // 15 minutes
        if (location.hostname === "localhost" || location.hostname === "127.0.0.1")
        {}
        else
        {
            if(!root)
            {
                window.location.href = "../lock_access.php?rurl=" + btoa(window.location);
            }
        }
    }
    else if (idleTime > 10)
    {
        toastr.warning('The page will lock or session will be lost.', 'Inactivity Detected');
    }
}

function resetTimer()
{
    idleTime = 0;
    clearTimeout(idleInterval);
    idleInterval = setInterval(timerIncrement, 60000);
}

function check_session()
{
    $.ajax({
        url: (root ? "check_session.php" : "../check_session.php"),
        method: "POST",
        success: function(data){
            response = $.parseJSON(data);
            if(response.status == 0)
            {
                if(!root)
                {
                    window.location.href = "../index.php";
                }
            }
            else if(response.status == -1)
            {
                if(!root)
                {
                    window.location.href = "../lockscreen.php?rurl=" + btoa(window.location);
                }
                else
                {
                    window.location.href = "lockscreen.php?rurl=" + btoa(window.location);
                }
            }
            else
            {
                if(root)
                {
                    window.location.reload();
                }
            }
        }
    });
}