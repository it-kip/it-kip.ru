<? header("Content-Type: text/html; charset=utf-8") ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="robots" content="all" />
        <link rel='stylesheet' href='/public/css/style.css' />
        
        <script type="text/javascript" src="/public/js/main.js"></script>
        
        <?= $title; ?>
        <? if(!empty($css)) echo $css; ?>
        <? if(!empty($js)) echo $js; ?>
    </head>
    
    <body>


<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter22482505 = new Ya.Metrika({id:22482505,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/22482505" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->


        <div class="main block">
            <div id="nav">
                <ul>
                    <li><a href="/">Главная</a></li>
                    <li><a href="/webexcel">Web Excel</a></li>
                    <li><a href="/kolap">kolap</a>
                        <ul>
                            <li><a href="/kolap/conn_db">Подключиться к бд</a></li>
                            <li><a href="/kolap/create_dir">Создать справочник</a></li>
                            <li><a href="/kolap/select_dir">Посмотреть справочники</a></li>
                            <li><a href="/kolap/create_cube">Создать куб</a></li>
                            <li><a href="/kolap/select_cube">Посмотреть кубы</a></li>
                        </ul>
                    </li>
                    
                    <li><a href="javascript:void(0)">Другие проекты</a>
                        <ul>
                            <li><a href="http://ussr.it-kip.ru">Решения для ресторанов</a></li>
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

<script type="text/javascript">
    document.oncontextmenu = function() {return false;};
    disableSelection(document.body);
</script>