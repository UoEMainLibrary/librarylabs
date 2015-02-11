<hr />
<div class="central">
<footer id="footer">
    <div class="container">
        <div class="uoe-logo">
            <a target="_blank" href="http://www.ed.ac.uk/">
                <img title="The University of Edinburgh" src="./../css/images/UoELogoFooter.png">
            </a>
        </div>
        <div class="footer-links">
            <ul class="links-top">
                <li>
                    <a href="./gameMenu.php">Game Menu</a></a>
                </li>
                <li>
                    <a href="http://librarylabs.ed.ac.uk">Library Labs Home</a>
                </li>
            </ul>
            <ul class="links-bottom">
                <li>
                    <a href="http://www.ed.ac.uk/about/website/website&#45;terms&#45;conditions">Terms &amp; conditions</a>
                </li>
                <li>
                    <a href="http://www.ed.ac.uk/about/website/privacy">Privacy &amp; cookies</a></li>

                <li>
                    <a href="http://www.ed.ac.uk/about/website/accessibility">Website accessibility</a>
                </li>

                <li>
                    <a href="http://www.ed.ac.uk/about/website/freedom&#45;information">Freedom of Information Publication Scheme</a>
                </li>
            </ul>
        </div>
        <div class="luc-logo">
            <a target="_blank" href="http://libraryblogs.is.ed.ac.uk/">
                <?php

                if($_SESSION['theme'] == 'photo')
                {
                ?>
                    <img title="Library and University Collections Blog" src="./../css/images/L&UCLogoFooter.png">
                <?php
                }
                else
                {
                ?>
                    <img title="Library and University Collections Blog" src="./../css/images/L&UCLogoFooter2.png">
                <?php
                }
                ?>
            </a>
        </div>
    </div>
</footer>
</div>