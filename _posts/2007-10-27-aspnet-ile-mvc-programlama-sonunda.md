---
title: ASP.NET ile MVC programlama, sonunda!
author: Hüseyin Tüfekçilerli
layout: post
permalink: /2007/10/aspnet-ile-mvc-programlama-sonunda/
comments: true
categories:
  - .NET
tags:
  - asp.net
  - mvc
---
ASP.NET&#8217;in varsayılan programlama yaklaşımı [Web Forms][1]&#8216;a bir türlü ısınamıyorum, ısınamayacağım da. Oysa ki [klasik ASP][2] (3.0) ne güzeldi, bir Request bir de Response nesnesi yetiyordu. Tamam, sayfanın yenilenmesi gibi durumlarda form değerlerini olması gereken yerlere doldurma hammalığı programcıya kalıyordu, ama ASP.NET&#8217;in <form runat=&#8221;server&#8221;> mantığı da pek hoş değildi. Binbir türlü yol (postback, viewstate, vs.) ile Microsoft mühendisleri web programcıların daha az kod ile hızlı bir şekilde nam-ı diğer Web Form Uygulamaları yazmalarını sağlıyordu. Bu çabaların hakkını vermek lazım, zira ASP.NET ile web programlama öyle bir duruma gelmişti ki Visual Studio ortamında masaüstü uygulama yazmaktan pek bir farkı kalmamıştı. Asıl amaç da bu değil miydi zaten, Visual Studio ile yıllardan beri aşina olan Microsoft teknolojileri odaklı programcıların bu yeni web programlama yaşam döngüsüne hızlı bir şekilde uyum sağlamaları. İyi güzel de bir şey unutuldu gibi geliyor bana, yazılan uygulamalar web uygulamaları olacaktı. Yani masaüstünden tamamen ayrı bir ortamda hayatlarını sürdüreceklerdi. Öyle bir ortam ki sunucu tarafını ayrı düşünmek, istemci (browser) tarafını ayrı düşünmek ve ona göre kod yazmak gerekiyordu. Web&#8217;in eti-kemiği olan [<acronym class="uttInitialism" title="HyperText Transfer Protocol">HTTP</acronym> protokolünü][3] ve <acronym class="uttInitialism" title="HyperText Markup Language">HTML</acronym> dilini sular seller gibi bilmesek de bir formu [GET][4] veya [POST][5] ile göndermenin farkını, hangi etiketin nerede kullanıldığında [daha anlamlı][6] olacağını bilmek bir web programcısının boynunun borcu olsa gerek.

Oysa ki ASP.NET&#8217;in arkasında muazzam bir framework, [BCL][7] (Base Class Library) var. Klasik ASP&#8217;de olan Request, Response, Session, vs. nesneleri bunda yok mu; var tabii ki hem de çok daha zengin, çok daha kullanılabilir. Bana göre tek yanlış olan bunların üzerine kurulan ASP.NET Web Forms yapısı. [MonoRail][8] isimli proje ile <a href="http://www.rubyonrails.org/" class="ubernym uttInitialism"><acronym class="uttInitialism" title="Ruby on Rails">RoR</acronym></a>&#8217;daki web programlama mantığı .NET ortamına getirilmeye çalışılıyor. Bunun ile bir proje geliştirmeye fırsatım olmadı ancak muhtemelen Web Forms mantığından daha uygulanabilir olacağını tahmin ediyorum.

İşte bu noktada [ScottGu][9] geçtiğimiz haftalarda [ALT.NET][10] konferansında Microsoft tarafından geliştirilmekte olan ASP.NET <acronym class="uttInitialism" title="Model View Controller">MVC</acronym> Framework&#8217;unu tanıttı. Daha üzerinde çalışmalar süren bu frameworkun bu yıl sonuna doğru genel önizleme (&#8220;public preview&#8221; demek istiyorum :) ) sürümü geliştiricilere bir <acronym class="uttInitialism" title="Community Technology Preview">CTP</acronym> olarak sunulacak. .NET 3.5&#8242;e dahil olmayacak bu framework, aynı ASP.NET <acronym class="uttAcronym" title="Asynchronous Javascript And XML">AJAX</acronym> (Asynchronous Javascript And <acronym class="uttInitialism" title="eXtensible Markup Language">XML</acronym>) (daha önce Atlas olarak da bilinen) gibi ASP.NET üzerine ek bir framework olarak dağıtılacak. 2008&#8242;in ilk yarısında da son halinin çıkması planlanıyor.

<p style="text-align: center;">
  <img src="http://huseyint.com/wp-content/uploads/2007/10/aspnet-mvc.jpg" alt="ASP.NET MVC ile “Merhaba Dünya”" />
</p>

Orjinal duyuruyu [ScottGu&#8217;nun blogunda][11]; ayrıntılı bilgiyi, blog camiasından konu ile ilgili linkleri ve ScottGu&#8217;nun tanıtım videosunu (izlemeniz şiddetle tavsiye olunur) [ScottHa&#8217;nın blogunda][12] bulabilirsiniz. Konu ile ilgili (blogunu yeni keşfettiğim) [Muhammed Tahiroğlu&#8217;nun yazısını][13] da okumanız tavsiye ederim.

 [1]: http://msdn.microsoft.com/msdnmag/issues/01/05/webforms/ "ASP .NET: Web Forms Let You Drag and Drop Your Way to Powerful Web Apps"
 [2]: http://en.wikipedia.org/wiki/Active_Server_Pages
 [3]: http://www.w3.org/Protocols/rfc2616/rfc2616.html
 [4]: http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.3
 [5]: http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.5
 [6]: http://huseyint.com/projeler/aptal-tablolar/ "Aptal Tablolar"
 [7]: http://msdn2.microsoft.com/en-us/library/aa388745.aspx
 [8]: http://www.castleproject.org/monorail/index.html
 [9]: http://weblogs.asp.net/scottgu/
 [10]: http://www.altnetconf.com/
 [11]: http://weblogs.asp.net/scottgu/archive/2007/10/14/asp-net-mvc-framework.aspx
 [12]: http://www.hanselman.com/blog/ScottGuMVCPresentationAndScottHaScreencastFromALTNETConference.aspx
 [13]: http://www.tahiroglu.com/post/aspnet-mvc-framework-ve-dusundurdukleri.aspx "ASP.NET MVC Framework ve Düşündürdükleri"