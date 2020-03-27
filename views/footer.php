<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();

        $('.js-show-white-label').on('click', function () {
            $('.js-white-label').show();
        });
    });


    $(document).ready(function () {

        $('.js-action-update-installations').on('click', function () {

            var el = $("#domains-ajax-result");
            if (el) {

                var txt;
                var r = confirm("Are you sure you want to update existing installations with the new settings?");
                if (r == true) {

                    el[0].scrollIntoView({behavior: 'smooth'})

                    el.html('Updating installations, please wait...');
                    el.load("?ajax_view=update_installs_with_new_settings", function () {


                    });
                }

            }

        });
    });


</script>