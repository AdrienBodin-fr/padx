<?php
  $pagesize = 5;

        // pagination
        $pagesize = 5;
        print "<form class=\"\">\n";

            // Compter le nombre de pages
            $sql = "select count(*) from app_article";
            $nrows = sqlone($sql);
            $nbpage = ceil($nrows/$pagesize);

            // Gestion du param�tre $p = num�ro de page
            @$p = clean_field($_REQUEST["p"]);
            if ((!$p)||($p<1))
            {
                $p = 1;
            }
            if ($p>$nbpage)
            {
                $p = $nbpage;
            }

            print "<div id=\"pagination\" class=\"w3-container w3-margin\">";
                    // Affichage des boutons de pagination
                    print "<button id=\"btnpagination\" type=\"submit\" name=\"p\" value=\"".($p - 1)."\""
                        ." class=\"btn\">\n";
                    print "Prev";
                    print "</button>\n";
                    for ($i=1; $i<=$nbpage; $i++)
                    {
                        print "<button id=\"btnpagination\" type=\"submit\" name=\"p\" value=\"".($i)."\""
                            ." class=\"btn ".($i==$p?"w3-red":"")."\">\n";
                        print $i;
                        print "</button>\n";

                    }
                    print "<button id=\"btnpagination\" type=\"submit\" name=\"p\" value=\"".($p + 1)."\""
                        ." class=\"btn\">\n";
                    print "Next";
                    print "</button>\n";
            print "</div>";

    print "</form>\n";

?>