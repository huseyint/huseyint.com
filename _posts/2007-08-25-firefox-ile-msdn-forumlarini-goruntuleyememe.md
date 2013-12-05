---
title: Firefox ile MSDN forumlarını görüntüleyememe
author: Hüseyin Tüfekçilerli
layout: post
permalink: /2007/08/firefox-ile-msdn-forumlarini-goruntuleyememe/
comments: true
categories:
  - Firefox
---
Bir kaç haftadır hem evde hem de işteki bilgisayarımdan Firefox ile MSDN forumlarından bir sayfayı görüntülemeye çalıştığımda şöyle bir hata ile karşılaşıyordum:

> Firefox has detected that the server is redirecting the request for this address in a way that will never complete.

Google araması sonucunda bu sayfalara ulaştığım için sorunu çözmeye çalışmaktansa Google tarafından kaydedilmiş (cached) arama sonucuna bakıyordum. Bugün yine aynı durumla karşılaştım fakat bu sefer sorunun neden kaynaklandığını araştırdım. Gördüğüm kadarıyla yalnız değilmişim ve bir çok kişi aynı sorun ile karşı karşıyaymış. Sorunun nedeni ise MSDN forumlarına yapılan son güncellemelerde login mekanizmasında yapılan değişiklik imiş. Firefox&#8217;taki eski cookieleriniz ile güncellenmiş MSDN forumlarına bağlanınca böyle bir sorun oluşuyormuş. Çözüm basit, tarayıcınızın cookilerini ya tamamen ya da *.microsoft.com kaynaklı olanlarını temizleyeceksiniz.

Firefox ile tüm cookilerinizi silmek için: Tools > Clear Private Data&#8230; (Ctrl + Shift + Del) menüsüne gidip çıkan dialog içerisindeki &#8220;Cookies&#8221; onay kutusu işaretliyken &#8220;Clear Private Data Now&#8221; butonuna tıklamalısınız.

Yok ben sadece *.microsoft.com kaynaklı cookieleri sileceğim diyorsanız da: Tools > Options&#8230; menüsüne giderek Privacy sekmesine geçin. &#8220;Show Cookies&#8230;&#8221; butonu yardımı ile Firefox&#8217;un kaydettiği tüm cookileri görebilirsiniz. Listeyi filtrelemek için üst taraftaki &#8220;Search&#8221; kutusuna **microsoft.com** yazın. Gelen tüm cookieleri seçin ve &#8220;Remove Cookie&#8221; butonuna tıklayarak silin.

<p style="text-align: center">
  <img src="http://huseyint.com/wp-content/uploads/2007/08/cookies.png" alt="Firefox Cookies penceresi" />
</p>