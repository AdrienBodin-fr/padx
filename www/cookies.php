<?php
    print "<div id=\"cgu\" class=\"\">\n";
    // Titre
    print "<div id=\"profil_container_biotitle\" class=\" w3-bar w3-container\"> ";
        print "<div class=\"w3-container w3-left\"> ";
                print "<h1>Politique relative aux cookies</h1>";
            print " </div> ";

            $text = file_get_contents("cookies.txt");

            if ($sup)
            {
                @$modify = clean_field($_REQUEST["modify"]);
                @$btnsave = clean_field($_POST["btnsave"]);

                if ($btnsave)
                {
                    $text = clean_field($_POST["text"]);
                    file_put_contents("cookies.txt", $text);
                    redirect("/page/$page");
                }

                print "<div class=\"w3-container w3-right\"> ";
                print "<a href=\"?modify=1\" class=\"\"><img class=\"Profil_card_icon\" src=\"/images/edit-button.png\" style=\"height:40px;\"  alt=\"icon edit\"></a>";
                print " </div> ";

            }
        print "</div> ";
        print "<div id=\"Container_cgu_txt\" class=\"w3-container\">\n";
            if (@$modify)
            {
                print "<form method=\"post\" class=\"\">\n";
                print "<textarea name=\"text\">";
                print "$text";
                print "</textarea>\n";
                print "<br/>\n";

                print "<button type=\"submit\" name=\"btnsave\" value=\"1\""
                    ." class=\"w3-btn w3-pink btnpos\" onclick=\"return confirm('Confirmer ?')\">\n";
                print "Enregistrer";
                print "</button>\n";
                print "</form>\n";
            }
            else
            {
                print nl2br($text);
            }
        print "</div>\n";
    print "</div>\n";
?>
<script>
    $(function(){
        $(".btnpos").click(function(e){
            localStorage.setItem("windowscroll", $(window).scrollTop());
        });
        var pos = localStorage.getItem("windowscroll", 0);
        if (pos > 0)
        {
            localStorage.setItem("windowscroll", 0);
            $(window).scrollTop(pos);
        }
    });
</script>