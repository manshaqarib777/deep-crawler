<?php
$time_end = microtime(true);
#$elapsed_time = $time_end - $time_start;

if (basename($_SERVER['PHP_SELF']) == 'index.php' || basename($_SERVER['PHP_SELF']) == 'index-no-sef.php') {
    ?>

    <!-- BEGIN ALL BLOG PAGES; ELSE, GET and POST -->
    </div>


    <div class="my-clear"></div>

    <div><?php

        //include("includes/pagination.inc");
        echo "<br/>";

        echo "</div>";
        ?>
        <!-- end #content --></div>

    <div class="clear_both"></div>
    <!-- END ALL BLOG PAGES; ELSE, GET and POST -->

<?php
}
?>

<!-- BEGIN ALL PAGES ON WEBSITE -->
<div class="row features">
    <section class="4u feature">
        <div class="image-wrapper first">
            <a href="http://ineedchemicalx.deviantart.com/art/Time-goes-by-too-fast-335982438" class="image full"><img
                    src="admin-blogify/images/pic03.jpg" alt=""/></a>
        </div>
        <header>
            <h3>Use On Any Device</h3>
        </header>
        <p>Create stunning websites using today’s best themes. All pages will look ideal on all devices; laptop,
            desktop,
            ipad, Android and Iphone.</p>
        <ul class="actions">
            <li><a href="#" class="button">Elevate my awareness</a></li>
        </ul>
    </section>
    <section class="4u feature">
        <div class="image-wrapper">
            <a href="http://ineedchemicalx.deviantart.com/art/Kingdom-of-the-Wind-348268044" class="image full"><img
                    src="admin-blogify/images/pic04.jpg" alt=""/></a>
        </div>
        <header>
            <h3>Save Time & Money</h3>
        </header>
        <p>Sitemakin and cloner will save you countless hours building websites. Taking over existsing website? Just
            clone it with the clone tool. </p>
        <ul class="actions">
            <li><a href="#" class="button">Elevate my awareness</a></li>
        </ul>
    </section>
    <section class="4u feature">
        <div class="image-wrapper">
            <a href="http://ineedchemicalx.deviantart.com/art/Elysium-355393900" class="image full"><img
                    src="admin-blogify/images/pic05.jpg" alt=""/></a>
        </div>
        <header>
            <h3>Fast Loading / SEO</h3>
        </header>
        <p>Build custom webpages with text, images and video that will be delivered at lightning speed. Works well with
            stylish templates.</p>
        <ul class="actions">
            <li><a href="#" class="button">Elevate my awareness</a></li>
        </ul>
    </section>
</div>

<?php if (basename($_SERVER['PHP_SELF']) == 'index.php' || basename($_SERVER['PHP_SELF']) == 'index-no-sef.php') {
    ?>
    </div>
<?php
}
?>
</div>

<!-- Footer Wrapper -->
<div id="footer-wrapper">

    <!-- Footer -->
    <div id="footer" class="container">
        <header class="major">
            <h2>Contact Us For More Information</h2>
            <span>Feel free to enquire about more details to make your CMS needs simplified. <br/>Sitemakin and cloner was created to save time and money and deliver high performance. This invaluable tool will find a use for every web developer.</span>
        </header>
        <div class="row">
            <section class="6u" id="myform">
                <form method="post" action="contacted.php">
                    <div class="row half">
                        <div class="6u">
                            <input name="name" placeholder="Name" type="text" class="text"/>
                        </div>
                        <div class="6u">
                            <input name="email" placeholder="Email" type="text" class="text"/>
                        </div>
                    </div>
                    <div class="row half">
                        <div class="12u">
                            <textarea name="message" placeholder="Message"></textarea>
                        </div>
                    </div>
                    <div class="row half">
                        <div class="12u">
                            <ul class="actions">
                                <li><input type="submit" class="button" name="submit" value="Send Message"/></li>
                                <li><a href="#myform" class="button-clear">Clear Form</a></li>
                            </ul>
                        </div>
                    </div>
                </form>
            </section>
            <section class="6u">
                <div class="row no-collapse-1">
                    <ul class="divided icons 6u">
                        <li class="fa fa-twitter"><a href="#"><span class="extra">twitter.com/</span>untitled</a></li>
                        <li class="fa fa-facebook"><a href="#"><span class="extra">facebook.com/</span>untitled</a></li>
                        <li class="fa fa-dribbble"><a href="#"><span class="extra">dribbble.com/</span>untitled</a></li>
                    </ul>
                    <ul class="divided icons 6u">
                        <li class="fa fa-linkedin"><a href="#"><span class="extra">linkedin.com/</span>untitled</a></li>
                        <li class="fa fa-youtube"><a href="#"><span class="extra">youtube.com/</span>untitled</a></li>
                        <li class="fa fa-pinterest"><a href="#"><span class="extra">pinterest.com/</span>untitled</a>
                        </li>
                    </ul>
                </div>
            </section>
        </div>
    </div>

    <!-- Copyright -->
    <div id="copyright" class="container">
        <ul class="menu">
            <li>&copy; Kent Elchuk. All rights reserved.</li>           
            <li>Design: <a href="http://html5up.net/">HTML5 UP</a></li>
        </ul>
    </div>

</div>
<!-- END ALL PAGES ON WEBSITE -->

</body>
</html>
