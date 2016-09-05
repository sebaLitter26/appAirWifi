

$(function () {

    $("#formulario").submit(function (e) {

        e.preventDefault();
        var valid = true;
        $("#formulario .success").hide();

        $(this).find('span').removeClass('error');
/*
        if (!validateName($("#name"))) {
            valid = false;
            $("#name").prev('span').addClass('error');
        }
        if (!validateName($("#lastname"))) {
            valid = false;
            $("#lastname").prev('span').addClass('error');
        }
		if (!validateEmail($("#email"))) {
            valid = false;
            $("#email").prev('span').addClass('error');
        }

        if ($("#duracion").val() == 0) {
            valid = false;
            $("#duracion").prev('span').addClass('error');
        }
*/
        if ($("#duracion option:selected").val() == '' || $("#duracion option:selected").val() == 0) {
            valid = false;
            $("#duracion").prev('span').addClass('error');
        }
        if ($("#modo option:selected").val() == '' || $("#modo option:selected").val() == 0) {
            valid = false;
            $("#modo").prev('span').addClass('error');
        }
		if ($("#temperatura option:selected").val() == '' || $("#temperatura option:selected").val() == 0) {
            valid = false;
            $("#temperatura").prev('span').addClass('error');
        }
        if ($("#velocidad option:selected").val() == '' || $("#velocidad option:selected").val() == 0) {
            valid = false;
            $("#velocidad").prev('span').addClass('error');
        }
		if (!$(".programa_group").is(':checked')) {
			valid = false;
			$("#programa").addClass('error');
		}
		
		
		

        if (valid) {

            $("#formulario .loading").show();
            $(".btnsave").attr("disabled", "disabled");
            var datos = $(this).serialize();

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'http://192.168.100.155:82/wifimicrochip/action/save.php',
                data: 'task=save&' + datos,
                success: function (msg) {
                    $("#formulario .loading").hide();
                    $(".btnsave").removeAttr("disabled");
                    if (msg.ok == "save") {
                        //$("#formulario .success").show();
                        $("#titulo").hide();
                        $("#formulario").slideUp('slow', function () {
                            $(".success").slideDown();
							//$(".success").css("overflow", "visible");
							/*if()
								$("#success").slideDown();
							else
								$("#success-smallscreen").slideDown();*/
                        })
                        $("#formulario input").val('');
                        $("#acepto").attr('checked', false);


                    } else {
                        $.each(msg.error, function (key, val) {
                            $("span." + val).addClass('error');
                        });
                    }
                },
                error: function () {
                    $(".btnsave").removeAttr("disabled");
                    $("#formulario .loading").hide();
                }
            });
        }
    });

    $(".sendagain").click(function (e) {
        e.preventDefault();
        $(this).parent().slideUp('fast', function () {
            $("#formulario").slideDown('slow');
        });
    });

    function validateName(o) {
        var validar = /^([A-Za-z áéíóúâêîôûñÑç])+$/i;
        if (!(validar.test(o.val())) || $.trim(o.val()) == "")
            return false;
        return true;
    }

    function validateEmail(o) {
        var validar = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;
        if (!(validar.test(o.val())) || $.trim(o.val()) == "")
            return false;
        return true;
    }

    function validateTelefono(o) {
        var validar = /^([0-9]{9,10})+$/i;
        if (!(validar.test(o.val())) || $.trim(o.val()) == "")
            return false;
        return true;
    }

    $('input[type="text"]').placeholder();

})