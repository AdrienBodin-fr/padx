<?php


  print "<form id=\"login_form\" action=\"/page/register\" method=\"post\">\n";

    print " <h1>&lt;/ Inscription /&gt;</h1>\n";

    if ($error)
      {
        print "<div class=\"w3-red\">\n";
        print "$error";
        print "</div>\n";
      }

    @$input_login = clean_field($_POST["input_login"]);
    @$input_passwd = clean_field($_POST["input_passwd"]);


    print "<div id=\"Container_login_form_input\" class=\"\">\n";
        print "<p><img id=\"asterisk\" src=\"/images/asterisk2.png\" alt=\"(obligatoire)\"> Tous les champs sont obligatoires.</p>\n";
        print "<br/>\n";
        print "<div id=\"login_form_input_name\" class=\"\">\n";
            print "<div id=\"login_form_input_firstname\" class=\"\">\n";
                print "<input type=\"text\" name=\"input_firstname\" id=\"input_firstname\""
                    ." placeholder=\"Prénom\" class=\"w3-input w3-round\" required>\n";
                print "<br/>\n";
            print "</div>\n";
            print "<div id=\"login_form_input_lastname\" class=\"\">\n";
                print "<input type=\"text\" name=\"input_lastname\" id=\"input_lastname\""
                    ." placeholder=\"Nom\" class=\"w3-input w3-round\" required>\n";
                print "<br/>\n";
            print "</div>\n";
        print "</div>\n";

        print "<input type=\"text\" name=\"input_pseudo\" id=\"input_pseudo\""
            ." placeholder=\"Pseudo\"  class=\"w3-input w3-round\" required>\n";
        print "<br/>\n";

        print "<input type=\"text\" name=\"input_login\" placeholder=\"&#xf0e0; Email\" class=\"w3-input w3-round\" value=\"$input_login\""
        //."  pattern=\"/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/\""
        ." required >\n";
        print "<br/>\n";

        print "<input type=\"password\" name=\"input_passwd\" placeholder=\"&#xf023; Mot de passe\" class=\"w3-input w3-round\" value=\"$input_passwd\" required>\n";
    print "</div>\n";

    print "<p>En cliquant sur S’inscrire, vous acceptez nos <a href=\"/page/cgu\">Conditions générales</a>.</p>\n";



    print "<div class=\"g-recaptcha\" data-theme=\"light\" data-sitekey=\"6LeKDK8fAAAAAPp5KNSZCQLxz0uBtvz2jitXcbwh\"></div>\n";
    print "<br/>\n";
    print "<div class=\"container_btnform\">\n";
        print "<button id=\"btnform2\" class=\"w3-round-xxlarge\" style=\"submit\" name=\"btnregister\" value=\"1\">\n";
        print "S'inscrire";
        print "</button>\n";
        //print "<input id=\"btnform2\"  class=\"w3-round-xxlarge\" type=\"submit\" value=\"S'inscrire\" name=\"btnregister\">\n";
    print "</div>\n";

    /*print "<button id=\"btnform2\" class=\"g-recaptcha\" data-sitekey=\"6Lcqea4fAAAAANsgVY2JOTR8-FSdPB6Eruv4U-hY\" data-callback='onSubmit' data-action='submit'  style=\"submit\" name=\"btnregister\" value=\"1\">\n";
    print "S'inscrire";
    print "</button>\n";
    print "<br/>\n";
*/
    print "<p>Vous avez déjà un compte ? <a href=\"/page/login\">Se connecter</a></p>";
  print "</form>\n";


?>

<script>
    function onSubmit(token) {
        document.getElementById("login_form").submit();
    }
</script>
