<? header("Content-Type: text/html; charset=utf-8") ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//RU" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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

        <div class="main">
            <div class="header">
                <div class="header_resize">
                    
                    <div class="logo">
                        <h1>
                            <a href="/"><span>it-kip</span>.ru <small>it решения</small></a>
                        </h1>
                    </div>
                    
                    <div class="clr"></div>
                    <div id="nav" class="menu_nav">

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
                                    <li><a target="blank" href="http://cafe.it-kip.ru">Решения для ресторанов</a></li>

                                    <li><a javascript="void(0)">Кредит Доверия</a>

                                        <ul>
		            <li><a href="/credibility">Скачать для Android</a></li>
                                            <li><a href="http://finbook.it-kip.ru">Web версия</a></li>
                                        </ul>
                                    </li>
                                </ul>

                            </li>
                        </ul>

                        <div class="searchform">
                          <form id="formsearch" name="formsearch" method="post" action="#">
                            <input name="button_search" src="/public/img/search_btn.gif" class="button_search" type="image" />
                            <span>
                                <input name="editbox_search" class="editbox_search" id="editbox_search" maxlength="80" placeholder="Поиск" type="text" />
                            </span>
                          </form>
                        </div>
                    </div>
                    <div class="clr"></div>
                        
                    </div>
                </div>

                <div class="content">
                    <div class="content_resize">
                        <div class="mainbar">
                            <?= $content; ?>
                        </div>
                        <div class="sidebar">
                          <div class="gadget">
                            <h2 class="star">Продукты</h2>
                            <div class="clr"></div>
                            <ul class="sb_menu">
                              <li><a href="/webexcel">WebExcel</a></li>
                              <li><a href="/kolap">KOLAP</a></li>
                              <li><a href="/credibility">Кредит доверия</a></li>
                              <li><a href="http://cafe.it-kip.ru" target="blank">Кафе</a></li>
                            </ul>
                          </div>
                          <div class="gadget">
                            <h2 class="star"><span>Sponsors</span></h2>
                            <div class="clr"></div>
                            <ul class="ex_menu">
                              <li><a href="#">Lorem ipsum dolor</a><br />
                                Donec libero. Suspendisse bibendum</li>
                              <li><a href="#">Dui pede condimentum</a><br />
                                Phasellus suscipit, leo a pharetra</li>
                              <li><a href="#">Condimentum lorem</a><br />
                                Tellus eleifend magna eget</li>
                              <li><a href="#">Fringilla velit magna</a><br />
                                Curabitur vel urna in tristique</li>
                              <li><a href="#">Suspendisse bibendum</a><br />
                                Cras id urna orbi tincidunt orci ac</li>
                              <li><a href="#">Donec mattis</a><br />
                                purus nec placerat bibendum</li>
                            </ul>
                          </div>
                        </div>
                        <div class="clr"></div>
                      </div>
                </div>
            
              <div class="fbg">
                <div class="fbg_resize">
                  <div class="col c1">
                    <h2><span>О нас</span></h2>
                    <a target="blank" href="finbook.it-kip.ru"><img src="/public/img/ic_launcher.png" width="56" height="56" alt="" style="border: none;" /></a>
                    <p>
		Оптимальные решения для вашего бизнеса
	</p>
                  </div>
                  <div class="col c2">
                    <h2><span>Lorem Ipsum</span></h2>
                    <ul class="sb_menu">
                      <li><a href="#">consequat molestie</a></li>
                      <li><a href="#">sem justo</a></li>
                      <li><a href="#">semper</a></li>
                      <li><a href="#">magna sed purus</a></li>
                      <li><a href="#">tincidunt</a></li>
                    </ul>
                  </div>
                  <div class="col c3">
                    <h2>Контакты</h2>
                    <p>Любые it решения</p>
                    <p><a href="mailto:it-kip@yandex.ru">it-kip@yandex.ru</a></p>
                    <p>+7 965 5665927</p>
                  </div>
                  <div class="clr"></div>
                </div>
              </div>
              <div class="footer">
                <div class="footer_resize">
                  <p class="lf">Copyright &copy; 2014 <a href="#">it-kip.ru</a> - it решения</p>
                  <p class="rf"><a href="mailto:it-kip@yandex.ru">it-kip@yandex.ru</a></p>
                  <div class="clr"></div>
                </div>
              </div>
            
        </div>

        <!--div id="footer">
            <p>©it-kip.ru</p>
        </div-->
    </body>
</html>

<script type="text/javascript">
    document.oncontextmenu = function() {return false;};
    disableSelection(document.body);
</script>