<?php require_once("assets/header/main.php"); ?>

<?php session_start(); ?>

        <form class="form-signup" method="post" action="/completed.php">
            <div class="text-center mb-4">
                <img class="mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
                <h1 class="h3 mb-3 font-weight-normal">YouTube Channel Video Monitor</h1>
                <p>Please select your YouTube channel and enter your email address below.</p>
            </div>

            <div class="form-label-group">
                <select id="yt_channel" name="yt_channel" class="form-control" required autofocus></select>
            </div>

            <div class="form-label-group">
                <input type="email" id="email" name="email" class="form-control" placeholder="Email address" required>
                <label for="email">Email address</label>
            </div>

            <button class="btn btn-lg btn-primary btn-block" id="submit" name="submit" value="submit" type="submit">Signup</button>
            <p class="mt-5 mb-3 text-muted text-center">&copy; 2018</p>
        </form>

        <script type="text/javascript">
            $(document).ready(function () {
                $.getJSON("get_channels.php", function(data) {
                    $.each(data, function(key, value) {   
                        $('#yt_channel').append($("<option></option>").attr("value",key).text(value)); 
                    });
                });
            });
        </script>

<?php require_once("assets/footer/main.php"); ?>
