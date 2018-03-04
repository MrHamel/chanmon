<?php require_once("assets/header/main.php"); ?>

        <form class="form-signup">
            <div class="text-center mb-4">
                <img class="mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
                <h1 class="h3 mb-3 font-weight-normal">YouTube Channel Video Monitor</h1>
                <p>Receive daily emails about video status changes on your YouTube channel.</p>
            </div>

            <button class="btn btn-lg btn-primary btn-block" type="submit">Authorize YouTube Channel Monitoring</button>
        </form>

        <script type="text/javascript">
            $(document).ready(function () {
                $('.form-signup').on('submit', function(e) {
                    e.preventDefault();
                    window.location.href = "/stage1.php";
                });
            });
        </script>

<?php require_once("assets/footer/main.php"); ?>
