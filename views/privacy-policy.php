<?php include_once(__DIR__ . "/../header.php"); ?>
<?php include_once(__DIR__ . "/../sidebar.php"); ?>
<?php include_once(__DIR__ . "/../topbar.php"); ?>
<?php include_once(__DIR__ ."/../models/typefunctions.php"); ?>


<script>
    $(function() {
        <?php
        if (isset($_SESSION['msg_type']) && $_SESSION['msg_type'] > 0) {
        ?>show_message('<?php echo $_SESSION['msg']; ?>', '', <?php echo $_SESSION['msg_type']; ?>);
    <?php
            $_SESSION['msg_type'] = 0;
            $_SESSION['msg'] = "";
        }
    ?>
    });
</script>

<div class="wrapper wrapper-content"></div>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-8">
        <h2>Privacy Policy</h2>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content">
                <form role="form" id="termForm" method="post" onsubmit="event.preventDefault(); edit_terms();">
                    <div class="">
                        <textarea name="editor1" id="terms" cols="30" rows="10"></textarea>
                    </div>
                    <div class="">
                    <button type="submit" class="btn btn-primary px-4 mt-3 submit">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once(__DIR__ . "/../footer.php"); ?>

<script>
CKEDITOR.replace( 'editor1' );
</script>