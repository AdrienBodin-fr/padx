<?php
    print "<form id=\"login_form\" action=\"/page/$page\" method=\"post\">\n";

      print " <h1> &lt;/ Hello Dev ! /&gt;</h1>";
      if ($error)
      {
          print "<div class=\"w3-red\">\n";
          print "$error";
          print "</div>\n";
      }

      @$input_login = clean_field($_POST["input_login"]);
      @$input_passwd = clean_field($_POST["input_passwd"]);
      print "<div id=\"Container_login_form_input\" class=\"\">\n";

          print "<input type=\"text\" name=\"input_login\" id=\"input_login\" placeholder=\"&#xf0e0; Email\" class=\"w3-input w3-round\" value=\"$input_login\""
          //."            pattern=\"/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/\""
          ." required >\n";
          print "<br/>\n";

          print "<input type=\"password\" name=\"input_passwd\" id=\"input_passwd\" class=\"w3-input w3-round\" placeholder=\"&#xf023; Mot de passe\" required>\n";
          print "<br/>\n";
      print "</div>\n";


        print "<div class=\"container_btnform\">\n";
            print "<button id=\"btnlogin\" class=\"w3-round-xxlarge\" style=\"submit\" name=\"btnlogin\" value=\"1\">\n";
            print "Se connecter";
            print "</button>\n";
        print "</div>\n";
        print "<br/>\n";

          print "<p>Vous n'avez pas encore de compte ? <a href=\"/page/register\">S'inscrire</a></p>";

    print "</form>\n";
?>
