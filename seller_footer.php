                        <div class="footer">
                    <div class="pull-right">
                        <b>Version</b> 1.0
                    </div>
                </div>
            </div>
        </div>


        <script type="text/javascript">
            var baseUrl = '<?php echo SERVER_BASE_URL; ?>';
        </script>
        
        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/popper.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/plugins/pace/pace.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/plugins/toastr/toastr.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/plugins/idle-timer/idle-timer.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/plugins/datepicker/datepicker.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/plugins/dataTables/datatables.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
        <script src="//cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/app.js"></script>
        <?php 
        //echo $url;
        if ($url == 'products') { ?>
            <script src="<?php echo SERVER_BASE_URL; ?>assets/js/product.js"></script>
        <?php } else if ($url == 'category1' || $url == 'category2') { ?>
            <script src="<?php echo SERVER_BASE_URL; ?>assets/js/custom.js"></script>
        <?php }else if ($url == 'banner') { ?>
            <script src="<?php echo SERVER_BASE_URL; ?>assets/js/banner.js"></script>
        <?php }else if ($url == 'promocode') { ?>
            <script src="<?php echo SERVER_BASE_URL; ?>assets/js/promocode.js"></script>
        <?php } ?>
            <script src="<?php echo SERVER_BASE_URL; ?>assets/js/delivery_charges.js"></script>
            
            <script src="<?php echo SERVER_BASE_URL; ?>assets/js/tax_setting.js"></script>
      

        
    </body>
</html>
<script>
    
function checkCharacterOnly(event) {
    var inputValue = event.which;

    if((!(inputValue >= 65 && inputValue <= 120) && (inputValue != 32 && inputValue != 0 && inputValue != 8 && inputValue != 9)) || (inputValue >= 96 && inputValue <= 105)) { 
        event.preventDefault(); 
    }
}

function checkNumberOnly(e) {
    -1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()
}

</script>