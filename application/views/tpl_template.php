<? header("Content-Type: text/html; charset=utf-8") ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="robots" content="all" />
        <?= $title; ?>
        <? if(!empty($css)) echo $css; ?>
        <? if(!empty($js)) echo $js; ?>
    </head>
    
    <body>
        <div class="main block">
            <div id="nav">
                <ul>
                    <li><a href="/">Главная</a></li>
                    <li><a href="#">About Us</a></li>

                    <li><a href="#">Our Portfolio</a></li>
                    <li><a href="#">One Dropdown</a>
                        <ul>
                            <li><a href="#">Level 2.1</a></li>
                            <li><a href="#">Level 2.2</a></li>
                            <li><a href="#">Level 2.3</a></li>
                            <li><a href="#">Level 2.4</a></li>

                            <li><a href="#">Level 2.5</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Three Levels</a>
                        <ul>
                            <li><a href="#">Level 2.1</a></li>
                            <li><a href="#">Level 2.2</a></li>
                            <li><a href="#">Level 2.3</a>

                                <ul>
                                    <li><a href="#">Level 2.3.1</a></li>
                                    <li><a href="#">Level 2.3.2</a></li>
                                    <li><a href="#">Level 2.3.3</a></li>
                                    <li><a href="#">Level 2.3.4</a></li>
                                    <li><a href="#">Level 2.3.5</a></li>

                                    <li><a href="#">Level 2.3.6</a></li>
                                    <li><a href="#">Level 2.3.7</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Level 2.4</a></li>
                            <li><a href="#">Level 2.5</a></li>
                        </ul>

                    </li>
                </ul>
            </div>

            <div class="shadow"></div>

            <div class="content">
                <?= $content; ?>
            </div>
        </div>

        <div id="footer">
            <p>©it-kip.ru</p>
        </div>
    </body>
</html>