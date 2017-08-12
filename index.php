<?php include("view/common/head.php"); ?>
<body>

<!--************************************************************************************************************-->
<!-- Html header -->
<div class="container">
    <div class="jumbotron" id="contactTitleDiv">
        <div class="container">
            <h1 class="text-center">PHP contacts <i class="fa fa-envelope"></i></h1>
            <br/>
            <div id="searchDiv"></div>
        </div>
    </div>
</div>

<!--************************************************************************************************************-->
<!-- Html body -->

<div class="container">
    <div id="paginationDiv"></div>
    <div id="contactDiv"></div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalIndex" data-backdrop="static" tabindex="-1" role="dialog"></div>

<!--************************************************************************************************************-->
<!-- Html footer -->

<?php include("view/common/footer.php"); ?>

<!--************************************************************************************************************-->
<!-- Javascript -->

<?php include("view/common/includeJs.php"); ?>
<script src="js/contact.js"></script>

<!--************************************************************************************************************-->
<!-- End -->

</body>
</html>